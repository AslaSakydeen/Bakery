<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }

$conn    = mysqli_connect("localhost","root","","bakerydb");
$user_id = intval($_SESSION['user_id']);

// Redirect if cart empty
$countRow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM cart WHERE user_id='$user_id'"));
if($countRow['c'] == 0){ header("Location: cart.php"); exit; }

// Fetch cart items for display
$cartItems = mysqli_query($conn,"
    SELECT cart.id AS cart_id, cart.quantity,
           menu_items.id AS item_id, menu_items.name, menu_items.price,
           menu_items.image, menu_items.stock
    FROM cart
    JOIN menu_items ON cart.item_id = menu_items.id
    WHERE cart.user_id = '$user_id'
    ORDER BY cart.added_at DESC
");
$cartData = [];
$subtotal = 0;
while($r = mysqli_fetch_assoc($cartItems)){
    $r['line_total'] = $r['price'] * $r['quantity'];
    $subtotal += $r['line_total'];
    $cartData[] = $r;
}

// Fetch user info
$userRow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT fullname FROM users WHERE id='$user_id'"));

// --- Handle order placement ---
$orderSuccess = false;
$errorMsg     = '';

if(isset($_POST['place_order'])){
    $phone   = mysqli_real_escape_string($conn, trim($_POST['phone']));
    $address = mysqli_real_escape_string($conn, trim($_POST['address']));

    if(empty($phone) || empty($address)){
        $errorMsg = "Please fill in all fields.";
    } else {
        // Insert one row per cart item into orders_tab
        $allOk = true;
        foreach($cartData as $item){
            $item_id = $item['item_id'];
            $qty     = $item['quantity'];

            // Check stock again before placing
            $stockRow = mysqli_fetch_assoc(mysqli_query($conn,"SELECT stock FROM menu_items WHERE id='$item_id'"));
            if(!$stockRow || $stockRow['stock'] < $qty){
                $errorMsg = "Sorry, '" . htmlspecialchars($item['name']) . "' does not have enough stock.";
                $allOk = false;
                break;
            }
        }

        if($allOk){
            foreach($cartData as $item){
                $item_id = $item['item_id'];
                $qty     = $item['quantity'];
                mysqli_query($conn,"INSERT INTO orders_tab(user_id,item_id,quantity,phone,address,status)
                    VALUES('$user_id','$item_id','$qty','$phone','$address','Pending')");
                // Decrement stock
                mysqli_query($conn,"UPDATE menu_items SET stock = stock - '$qty' WHERE id='$item_id'");
            }
            // Clear cart
            mysqli_query($conn,"DELETE FROM cart WHERE user_id='$user_id'");
            // Redirect with success flag
            header("Location: myOrder.php?order_placed=1");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Checkout | Bakery</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--orange-clr:#e96c28;--black-clr:#000;--white-clr:#fff;--gray-clr:#636363;--light-gray:#c4c4c4;--dawn-pink:#fae7e5;}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f5f5f5;min-height:100vh;}
.checkout-page{max-width:1000px;margin:2.5rem auto;padding:0 1.2rem;}
.checkout-page h1{font-size:2rem;margin-bottom:1.8rem;} 
.checkout-page h1 span{color:var(--orange-clr);}
.checkout-layout{display:grid;grid-template-columns:1fr 380px;gap:1.5rem;align-items:start;}

/* Order summary */
.order-summary-box{background:var(--white-clr);border-radius:1.2rem;box-shadow:0 4px 20px rgba(0,0,0,0.07);overflow:hidden;position:sticky;top:1rem;}
.order-summary-box h2{padding:1.2rem 1.5rem;border-bottom:1px solid #f0f0f0;font-size:1rem;font-weight:600;}
.checkout-item{display:flex;align-items:center;gap:.9rem;padding:.9rem 1.5rem;border-bottom:1px solid #f7f7f7;}
.checkout-item img{width:50px;height:50px;object-fit:cover;border-radius:.5rem;flex-shrink:0;}
.checkout-item-name{font-size:.88rem;font-weight:600;flex:1;}
.checkout-item-qty{font-size:.8rem;color:var(--gray-clr);}
.checkout-item-price{font-weight:700;font-size:.9rem;white-space:nowrap;}

.totals-section{padding:1.2rem 1.5rem;}
.t-row{display:flex;justify-content:space-between;font-size:.9rem;color:var(--gray-clr);margin-bottom:.7rem;}
.t-total{display:flex;justify-content:space-between;font-size:1.2rem;font-weight:700;padding-top:.9rem;border-top:2px solid #f0f0f0;}
.t-total span:last-child{color:var(--orange-clr);}

/* Checkout form */
.checkout-form-box{background:var(--white-clr);border-radius:1.2rem;box-shadow:0 4px 20px rgba(0,0,0,0.07);padding:2rem;}
.checkout-form-box h2{font-size:1rem;font-weight:600;margin-bottom:1.5rem;}
.form-group{margin-bottom:1.2rem;}
.form-group label{display:block;font-size:.85rem;font-weight:500;color:var(--gray-clr);margin-bottom:.4rem;}
.form-group input,
.form-group textarea{width:100%;padding:.75rem 1rem;border:1.5px solid #eee;border-radius:.7rem;font-size:.92rem;font-family:inherit;transition:.2s;outline:none;}
.form-group input:focus,
.form-group textarea:focus{border-color:var(--orange-clr);box-shadow:0 0 0 3px rgba(233,108,40,.1);}
.form-group input[readonly]{background:#f9f9f9;color:var(--gray-clr);}
.form-group textarea{resize:vertical;min-height:90px;}

.place-order-btn{width:100%;background:var(--orange-clr);color:var(--white-clr);border:none;padding:1rem;border-radius:50px;font-size:1rem;font-weight:700;cursor:pointer;transition:.3s;display:flex;align-items:center;justify-content:center;gap:.6rem;margin-top:.5rem;}
.place-order-btn:hover{background:var(--black-clr);}

.error-msg{background:#fde8e8;border-left:4px solid #e53935;color:#c62828;padding:.9rem 1.2rem;border-radius:.6rem;margin-bottom:1.2rem;font-size:.9rem;}

.steps{display:flex;align-items:center;gap:.5rem;margin-bottom:2rem;}
.step{display:flex;align-items:center;gap:.4rem;font-size:.85rem;color:var(--light-gray);}
.step.done{color:var(--orange-clr);font-weight:600;}
.step-num{width:22px;height:22px;border-radius:50%;background:#eee;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;}
.step.done .step-num{background:var(--orange-clr);color:#fff;}
.step-sep{flex:1;height:2px;background:#eee;}
.step-sep.done{background:var(--orange-clr);}

@media(max-width:768px){.checkout-layout{grid-template-columns:1fr;} .order-summary-box{order:-1;position:static;}}
</style>
</head>
<body>

<header>
  <nav class="navbar container">
    <div class="logo"><a href="index.php" class="logo-txt">Bake<span>ry.</span></a></div>
    <ul class="navlist" id="navlist">
      <li><a href="index.php"    class="navlinks">Home</a></li>
      <li><a href="menu.php"     class="navlinks">Menu</a></li>
      <li><a href="cart.php"     class="navlinks">Cart</a></li>
      <li><a href="myOrder.php"  class="navlinks">My Orders</a></li>
    </ul>
    <div class="nav-icons">
      <a href="cart.php" class="icons-link"><i class="fa-solid fa-cart-shopping"></i></a>
      <a href="login.php" class="icons-link"><i class="fa-solid fa-user"></i></a>
      <a href="logout.php" class="icons-link"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    </div>
    <div class="hamburger" id="hamburger"><div class="burger"></div><div class="burger"></div><div class="burger"></div></div>
  </nav>
</header>

<div class="checkout-page">
  <h1>Check<span>out</span></h1>

  <!-- Breadcrumb steps -->
  <div class="steps">
    <div class="step done"><span class="step-num">1</span> Cart</div>
    <div class="step-sep done"></div>
    <div class="step done"><span class="step-num">2</span> Checkout</div>
    <div class="step-sep"></div>
    <div class="step"><span class="step-num">3</span> Confirmed</div>
  </div>

  <div class="checkout-layout">

    <!-- Form -->
    <div class="checkout-form-box">
      <h2><i class="fa-solid fa-location-dot" style="color:var(--orange-clr);margin-right:.4rem;"></i> Delivery Details</h2>

      <?php if(!empty($errorMsg)): ?>
      <div class="error-msg"><i class="fa-solid fa-circle-exclamation"></i> <?php echo $errorMsg; ?></div>
      <?php endif; ?>

      <form method="POST" id="checkoutForm">
        <div class="form-group">
          <label>Your Name</label>
          <input type="text" value="<?php echo htmlspecialchars($userRow['fullname']); ?>" readonly>
        </div>
        <div class="form-group">
          <label>Phone Number <span style="color:red">*</span></label>
          <input type="tel" name="phone" placeholder="e.g. 071 234 5678" required
                 value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
        </div>
        <div class="form-group">
          <label>Delivery Address <span style="color:red">*</span></label>
          <textarea name="address" placeholder="Enter your full delivery address" required><?php echo htmlspecialchars($_POST['address'] ?? ''); ?></textarea>
        </div>
        <div style="background:#fff8f4;border-radius:.7rem;padding:1rem;margin-bottom:1.2rem;font-size:.85rem;color:var(--gray-clr);">
          <i class="fa-solid fa-circle-info" style="color:var(--orange-clr);margin-right:.4rem;"></i>
          Payment is done <strong>upon delivery</strong>. No online payment required.
        </div>
        <button type="submit" name="place_order" class="place-order-btn">
          <i class="fa-solid fa-check-circle"></i> Place Order — <?php echo number_format($subtotal,2); ?> LKR
        </button>
      </form>
    </div>

    <!-- Summary -->
    <div class="order-summary-box">
      <h2><i class="fa-solid fa-receipt" style="color:var(--orange-clr);margin-right:.4rem;"></i> Order Summary</h2>
      <?php foreach($cartData as $item): ?>
      <div class="checkout-item">
        <img src="images/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
        <div style="flex:1;">
          <div class="checkout-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
          <div class="checkout-item-qty">x<?php echo $item['quantity']; ?></div>
        </div>
        <div class="checkout-item-price"><?php echo number_format($item['line_total'],2); ?> LKR</div>
      </div>
      <?php endforeach; ?>
      <div class="totals-section">
        <div class="t-row"><span>Items (<?php echo count($cartData); ?>)</span><span><?php echo number_format($subtotal,2); ?> LKR</span></div>
        <div class="t-row"><span>Delivery</span><span style="color:green;font-weight:500;">Free</span></div>
        <div class="t-total"><span>Total</span><span><?php echo number_format($subtotal,2); ?> LKR</span></div>
      </div>
    </div>

  </div>
</div>

<script>
document.getElementById('hamburger').addEventListener('click',()=>{
    document.getElementById('navlist').classList.toggle('navlist-active');
});
</script>
</body>
</html>
