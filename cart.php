<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php"); exit;
}

require_once 'db.php';
$user_id = intval($_SESSION['user_id']);

// Fetch cart items
$cartItems = mysqli_query($conn, "
    SELECT cart.id AS cart_id, cart.quantity,
           menu_items.id AS item_id, menu_items.name, menu_items.price,
           menu_items.image, menu_items.stock,
           categories.name AS category_name
    FROM cart
    JOIN menu_items  ON cart.item_id          = menu_items.id
    JOIN categories  ON menu_items.category_id = categories.id
    WHERE cart.user_id = '$user_id'
    ORDER BY cart.added_at DESC
");

$subtotal = 0;
$cartData = [];
while($row = mysqli_fetch_assoc($cartItems)){
    $row['line_total'] = $row['price'] * $row['quantity'];
    $subtotal         += $row['line_total'];
    $cartData[]        = $row;
}
$cartCount = count($cartData);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Cart | Bakery</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--orange-clr:#e96c28;--black-clr:#000;--white-clr:#fff;--gray-clr:#636363;--light-gray:#c4c4c4;--dawn-pink:#fae7e5;}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f5f5f5;min-height:100vh;}

/* ---- Cart page ---- */
.cart-page{max-width:1050px;margin:2.5rem auto;padding:0 1.2rem;}
.cart-page h1{font-size:2rem;margin-bottom:1.8rem;color:var(--black-clr);}
.cart-page h1 span{color:var(--orange-clr);}

/* Empty cart */
.empty-cart{text-align:center;padding:5rem 2rem;background:var(--white-clr);border-radius:1.2rem;box-shadow:0 4px 20px rgba(0,0,0,0.07);}
.empty-cart i{font-size:5rem;color:var(--light-gray);margin-bottom:1.2rem;display:block;}
.empty-cart h2{font-size:1.6rem;color:var(--gray-clr);margin-bottom:.6rem;}
.empty-cart p{color:var(--light-gray);margin-bottom:1.8rem;}
.empty-cart a{background:var(--orange-clr);color:var(--white-clr);padding:.75rem 2rem;border-radius:50px;text-decoration:none;font-weight:600;transition:.3s;}
.empty-cart a:hover{background:var(--black-clr);}

/* Layout */
.cart-layout{display:grid;grid-template-columns:1fr 310px;gap:1.5rem;align-items:start;}

/* Items box */
.cart-items-box{background:var(--white-clr);border-radius:1.2rem;box-shadow:0 4px 20px rgba(0,0,0,0.07);overflow:hidden;}
.box-header{padding:1.2rem 1.5rem;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;}
.box-header h2{font-size:1.05rem;font-weight:600;}
.box-header span{font-size:.85rem;color:var(--gray-clr);}

/* Cart item row */
.cart-item{display:grid;grid-template-columns:80px 1fr 110px 120px 45px;gap:1rem;align-items:center;padding:1.2rem 1.5rem;border-bottom:1px solid #f7f7f7;transition:background .2s;}
.cart-item:last-child{border-bottom:none;}
.cart-item:hover{background:#fafafa;}
.cart-item-img{width:80px;height:80px;object-fit:cover;border-radius:.6rem;}
.item-info h3{font-size:.9rem;font-weight:600;margin-bottom:.3rem;color:var(--black-clr);}
.item-info .cat-tag{font-size:.75rem;background:#f0f0f0;color:var(--gray-clr);padding:2px 8px;border-radius:50px;}
.item-unit-price{font-size:.95rem;font-weight:500;color:var(--gray-clr);}

/* Qty stepper */
.qty-stepper{display:flex;align-items:center;gap:.4rem;border:1.5px solid #eee;border-radius:50px;padding:.28rem .6rem;width:fit-content;}
.qty-stepper button{background:none;border:none;cursor:pointer;font-size:.85rem;color:var(--gray-clr);width:22px;height:22px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:.2s;}
.qty-stepper button:hover{background:var(--dawn-pink);color:var(--orange-clr);}
.qty-stepper .qty-num{font-weight:600;min-width:22px;text-align:center;font-size:.9rem;}

/* Line total */
.line-total-cell{font-weight:700;font-size:.95rem;color:var(--black-clr);text-align:center;}
.remove-btn{background:none;border:none;cursor:pointer;color:var(--light-gray);font-size:1rem;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;transition:.2s;}
.remove-btn:hover{color:#e53935;background:#fde8e8;}

/* Summary box */
.summary-box{background:var(--white-clr);border-radius:1.2rem;box-shadow:0 4px 20px rgba(0,0,0,0.07);padding:1.8rem;position:sticky;top:1rem;}
.summary-box h2{font-size:1.05rem;font-weight:600;margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid #f0f0f0;}
.s-row{display:flex;justify-content:space-between;margin-bottom:.9rem;font-size:.9rem;color:var(--gray-clr);}
.s-row.free{color:#2e7d32;font-weight:500;}
.s-total{display:flex;justify-content:space-between;font-size:1.15rem;font-weight:700;padding-top:1rem;border-top:2px solid #f0f0f0;margin-top:.5rem;}
.s-total span:last-child{color:var(--orange-clr);}
.checkout-btn{display:flex;align-items:center;justify-content:center;gap:.6rem;background:var(--orange-clr);color:var(--white-clr);padding:1rem;border-radius:50px;text-decoration:none;font-weight:700;margin-top:1.5rem;transition:.3s;font-size:1rem;}
.checkout-btn:hover{background:var(--black-clr);}
.continue-link{display:block;text-align:center;color:var(--gray-clr);text-decoration:none;margin-top:.9rem;font-size:.88rem;transition:.2s;}
.continue-link:hover{color:var(--orange-clr);}

/* Toast */
#toast{position:fixed;bottom:2rem;right:2rem;background:#323232;color:#fff;padding:.85rem 1.5rem;border-radius:.7rem;font-size:.9rem;opacity:0;transform:translateY(1rem);transition:.4s;z-index:9999;pointer-events:none;}
#toast.show{opacity:1;transform:translateY(0);}

@media(max-width:768px){
    .cart-layout{grid-template-columns:1fr;}
    .cart-item{grid-template-columns:64px 1fr;row-gap:.5rem;}
    .qty-stepper,.line-total-cell,.remove-btn{grid-column:2;}
    .item-unit-price{display:none;}
}
</style>
</head>
<body>

<header>
  <nav class="navbar container">
    <div class="logo"><a href="index.php" class="logo-txt">Bake<span>ry.</span></a></div>
    <ul class="navlist" id="navlist">
      <li><a href="index.php"    class="navlinks">Home</a></li>
      <li><a href="aboutUs.php"  class="navlinks">About</a></li>
      <li><a href="menu.php"     class="navlinks">Menu</a></li>
      <li><a href="myOrder.php"  class="navlinks">My Orders</a></li>
      <li><a href="contactUs.php" class="navlinks">Contact</a></li>
    </ul>
    <div class="nav-icons">
      <a href="cart.php" class="icons-link" style="position:relative;" title="Cart">
        <i class="fa-solid fa-cart-shopping"></i>
        <?php if($cartCount>0): ?>
        <span style="position:absolute;top:-7px;right:-7px;background:var(--orange-clr);color:#fff;font-size:10px;width:17px;height:17px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;" id="cart-badge"><?php echo $cartCount; ?></span>
        <?php endif; ?>
      </a>
      <a href="login.php"  class="icons-link"><i class="fa-solid fa-user"></i></a>
      <a href="logout.php" class="icons-link"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    </div>
    <div class="hamburger" id="hamburger"><div class="burger"></div><div class="burger"></div><div class="burger"></div></div>
  </nav>
</header>

<div class="cart-page">
  <h1>My <span>Cart</span></h1>

  <?php if(empty($cartData)): ?>
  <div class="empty-cart">
    <i class="fa-solid fa-cart-shopping"></i>
    <h2>Your cart is empty!</h2>
    <p>Browse our menu and add some delicious items.</p>
    <a href="menu.php">Browse Menu</a>
  </div>

  <?php else: ?>
  <div class="cart-layout">

    <!-- Items -->
    <div class="cart-items-box">
      <div class="box-header">
        <h2>Cart Items</h2>
        <span><?php echo $cartCount; ?> item<?php echo $cartCount>1?'s':''; ?></span>
      </div>
      <?php foreach($cartData as $item): ?>
      <div class="cart-item" id="cart-row-<?php echo $item['cart_id']; ?>">
        <img src="images/<?php echo htmlspecialchars($item['image']); ?>"
             alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-item-img">

        <div class="item-info">
          <h3><?php echo htmlspecialchars($item['name']); ?></h3>
          <span class="cat-tag"><?php echo htmlspecialchars($item['category_name']); ?></span>
        </div>

        <div class="item-unit-price"><?php echo number_format($item['price'],2); ?> LKR each</div>

        <div class="qty-stepper">
          <button onclick="changeQty(<?php echo $item['cart_id'].','; ?><?php echo $item['price'].','; ?>-1)" title="Decrease"><i class="fa-solid fa-minus"></i></button>
          <span class="qty-num" id="qty-<?php echo $item['cart_id']; ?>"><?php echo $item['quantity']; ?></span>
          <button onclick="changeQty(<?php echo $item['cart_id'].','; ?><?php echo $item['price'].','; ?>1)" title="Increase"><i class="fa-solid fa-plus"></i></button>
        </div>

        <div class="line-total-cell" id="line-<?php echo $item['cart_id']; ?>"><?php echo number_format($item['line_total'],2); ?> LKR</div>

        <button class="remove-btn" onclick="removeItem(<?php echo $item['cart_id']; ?>)" title="Remove"><i class="fa-solid fa-trash"></i></button>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Summary -->
    <div class="summary-box">
      <h2>Order Summary</h2>
      <div class="s-row">
        <span>Subtotal (<?php echo $cartCount; ?> item<?php echo $cartCount>1?'s':''; ?>)</span>
        <span id="subtotal-display"><?php echo number_format($subtotal,2); ?> LKR</span>
      </div>
      <div class="s-row free">
        <span>Delivery</span>
        <span>Free</span>
      </div>
      <div class="s-total">
        <span>Total</span>
        <span id="grand-total"><?php echo number_format($subtotal,2); ?> LKR</span>
      </div>
      <a href="checkout.php" class="checkout-btn">
        <i class="fa-solid fa-bag-shopping"></i> Proceed to Checkout
      </a>
      <a href="menu.php" class="continue-link">← Continue Shopping</a>
    </div>

  </div><!-- .cart-layout -->
  <?php endif; ?>
</div>

<div id="toast"></div>

<script>
let runningTotal = <?php echo round($subtotal, 2); ?>;

function changeQty(cartId, price, delta){
    const qtyEl  = document.getElementById('qty-' + cartId);
    const lineEl = document.getElementById('line-' + cartId);
    let qty = parseInt(qtyEl.textContent) + delta;
    if(qty < 1) qty = 1;

    qtyEl.textContent  = qty;
    lineEl.textContent = (price * qty).toFixed(2) + ' LKR';
    recalc();

    fetch('cart_action.php', {
        method : 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body   : `action=update&cart_id=${cartId}&quantity=${qty}`
    });
}

function removeItem(cartId){
    const row = document.getElementById('cart-row-' + cartId);
    row.style.transition = 'opacity .3s, transform .3s';
    row.style.opacity    = '0';
    row.style.transform  = 'translateX(20px)';
    setTimeout(()=>{
        row.remove();
        recalc();
        if(document.querySelectorAll('.cart-item').length === 0) location.reload();
    }, 320);
    fetch('cart_action.php', {
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:`action=remove&cart_id=${cartId}`
    });
}

function recalc(){
    let total = 0;
    document.querySelectorAll('[id^="line-"]').forEach(el=>{
        total += parseFloat(el.textContent) || 0;
    });
    document.getElementById('grand-total').textContent    = total.toFixed(2) + ' LKR';
    document.getElementById('subtotal-display').textContent = total.toFixed(2) + ' LKR';
}

// Hamburger
document.getElementById('hamburger').addEventListener('click',()=>{
    document.getElementById('navlist').classList.toggle('navlist-active');
});
</script>
</body>
</html>
