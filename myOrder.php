<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php"); exit;
}

require_once 'db.php';
$user_id = intval($_SESSION['user_id']);

$query = mysqli_query($conn,"
    SELECT o.*, m.name AS item_name, m.price AS item_price, m.image AS item_image,
           c.name AS category_name,
           r.id AS rating_id, r.rating AS my_rating
    FROM orders_tab o
    JOIN menu_items m  ON o.item_id           = m.id
    JOIN categories c  ON m.category_id       = c.id
    LEFT JOIN ratings r ON r.order_id         = o.id AND r.user_id = '$user_id'
    WHERE o.user_id = '$user_id'
    ORDER BY o.id DESC
");

// Status badge helper
function statusBadge($s){
    $map = [
        'Pending'          => ['#fff3e0','#e65100','⏳'],
        'Confirmed'        => ['#e3f2fd','#1565c0','✅'],
        'Preparing'        => ['#f3e5f5','#6a1b9a','👨‍🍳'],
        'Out for Delivery' => ['#e0f7fa','#006064','🚗'],
        'Delivered'        => ['#e8f5e9','#2e7d32','🎉'],
        'Cancelled'        => ['#fde8e8','#c62828','❌'],
    ];
    $c = $map[$s] ?? ['#f0f0f0','#333',''];
    return "<span style='background:{$c[0]};color:{$c[1]};padding:4px 12px;border-radius:50px;font-size:.78rem;font-weight:600;white-space:nowrap;'>{$c[2]} $s</span>";
}

// Star rating display
function starDisplay($rating){
    $html = '';
    for($i=1;$i<=5;$i++){
        $color = $i <= $rating ? '#ffa000' : '#ddd';
        $html .= "<i class='fa-solid fa-star' style='color:$color;font-size:.8rem;'></i>";
    }
    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Orders | Bakery</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--orange-clr:#e96c28;--black-clr:#000;--white-clr:#fff;--gray-clr:#636363;--light-gray:#c4c4c4;--dawn-pink:#fae7e5;}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:#f5f5f5;min-height:100vh;}

.orders-page{max-width:1050px;margin:2.5rem auto;padding:0 1.2rem;}
.orders-page h1{font-size:2rem;margin-bottom:1.8rem;}
.orders-page h1 span{color:var(--orange-clr);}

/* Alert */
.alert{padding:1rem 1.5rem;border-radius:.8rem;margin-bottom:1.5rem;font-size:.92rem;display:flex;align-items:center;gap:.6rem;}
.alert-success{background:#e8f5e9;color:#2e7d32;border-left:4px solid #2e7d32;}
.alert-info{background:#e3f2fd;color:#1565c0;border-left:4px solid #1565c0;}

/* Order card */
.order-card{background:var(--white-clr);border-radius:1rem;box-shadow:0 4px 18px rgba(0,0,0,0.07);margin-bottom:1.2rem;overflow:hidden;transition:.2s;}
.order-card:hover{box-shadow:0 6px 24px rgba(0,0,0,0.1);}

.order-card-header{display:flex;justify-content:space-between;align-items:center;padding:1rem 1.5rem;border-bottom:1px solid #f0f0f0;flex-wrap:wrap;gap:.5rem;}
.order-id{font-weight:700;font-size:.95rem;}
.order-id span{color:var(--orange-clr);}
.order-date{font-size:.8rem;color:var(--light-gray);}

.order-card-body{display:grid;grid-template-columns:70px 1fr auto;gap:1rem;align-items:center;padding:1rem 1.5rem;}
.order-item-img{width:70px;height:70px;object-fit:cover;border-radius:.6rem;}
.order-item-info h3{font-size:.95rem;font-weight:600;margin-bottom:.2rem;}
.order-item-info .cat{font-size:.75rem;background:#f0f0f0;color:var(--gray-clr);padding:2px 8px;border-radius:50px;display:inline-block;margin-bottom:.4rem;}
.order-item-info .details{font-size:.82rem;color:var(--gray-clr);}

.order-right{text-align:right;}
.order-total{font-size:1.1rem;font-weight:700;color:var(--orange-clr);}
.order-qty{font-size:.82rem;color:var(--gray-clr);margin-bottom:.4rem;}

/* Rate button */
.rate-btn{display:inline-flex;align-items:center;gap:.4rem;background:var(--orange-clr);color:#fff;padding:.45rem 1.1rem;border-radius:50px;text-decoration:none;font-size:.82rem;font-weight:600;transition:.3s;margin-top:.5rem;}
.rate-btn:hover{background:var(--black-clr);}
.rated-badge{display:inline-flex;align-items:center;gap:.3rem;font-size:.8rem;color:#2e7d32;font-weight:500;margin-top:.5rem;}

/* Empty state */
.empty-state{text-align:center;padding:4rem 2rem;background:var(--white-clr);border-radius:1.2rem;box-shadow:0 4px 18px rgba(0,0,0,0.07);}
.empty-state i{font-size:4rem;color:var(--light-gray);margin-bottom:1rem;display:block;}
.empty-state h2{color:var(--gray-clr);margin-bottom:1rem;}
.empty-state a{background:var(--orange-clr);color:#fff;padding:.7rem 2rem;border-radius:50px;text-decoration:none;font-weight:600;transition:.3s;display:inline-block;}
.empty-state a:hover{background:var(--black-clr);}

/* Address cell */
.order-address{font-size:.8rem;color:var(--gray-clr);padding:.5rem 1.5rem 1rem;display:flex;align-items:flex-start;gap:.4rem;}
.order-address i{color:var(--orange-clr);margin-top:2px;flex-shrink:0;}

@media(max-width:600px){
    .order-card-body{grid-template-columns:60px 1fr;} 
    .order-right{grid-column:2;}
}
</style>
<link rel="stylesheet" href="global.css">
</head>
<body>

<header>
  <nav class="navbar container">
    <div class="logo"><a href="index.php" class="logo-txt">Bake<span>ry.</span></a></div>
    <ul class="navlist" id="navlist">
      <li><a href="index.php"    class="navlinks">Home</a></li>
      <li><a href="aboutUs.php"  class="navlinks">About</a></li>
      <li><a href="menu.php"     class="navlinks">Menu</a></li>
      <li><a href="myOrder.php"  class="navlinks active">My Orders</a></li>
      <li><a href="contactUs.php" class="navlinks">Contact</a></li>
    </ul>
    <div class="nav-icons">
      <a href="cart.php" class="icons-link"><i class="fa-solid fa-cart-shopping"></i></a>
      <a href="login.php"  class="icons-link"><i class="fa-solid fa-user"></i></a>
      <a href="logout.php" class="icons-link"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    </div>
    <div class="hamburger" id="hamburger"><div class="burger"></div><div class="burger"></div><div class="burger"></div></div>
  </nav>
</header>

<div class="orders-page">
  <h1>My <span>Orders</span></h1>

  <?php if(isset($_GET['order_placed'])): ?>
  <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> Your order was placed successfully! We'll confirm it shortly.</div>
  <?php endif; ?>

  <?php if(isset($_GET['rated'])): ?>
  <div class="alert alert-info"><i class="fa-solid fa-star"></i> Thank you for your review!</div>
  <?php endif; ?>

  <?php if(isset($_GET['already_rated'])): ?>
  <div class="alert alert-info"><i class="fa-solid fa-info-circle"></i> You've already rated this order.</div>
  <?php endif; ?>

  <?php if(mysqli_num_rows($query) > 0): ?>

    <?php while($row = mysqli_fetch_assoc($query)):
      $total = $row['item_price'] * $row['quantity'];
      $isDelivered = $row['status'] === 'Delivered';
      $alreadyRated= !is_null($row['rating_id']);
    ?>
    <div class="order-card">
      <!-- Header -->
      <div class="order-card-header">
        <div>
          <span class="order-id">Order <span>#<?php echo $row['id']; ?></span></span>
          <span class="order-date" style="margin-left:.8rem;"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
        </div>
        <?php echo statusBadge($row['status']); ?>
      </div>

      <!-- Body -->
      <div class="order-card-body">
        <img src="images/<?php echo htmlspecialchars($row['item_image']); ?>"
             alt="<?php echo htmlspecialchars($row['item_name']); ?>" class="order-item-img">

        <div class="order-item-info">
          <h3><?php echo htmlspecialchars($row['item_name']); ?></h3>
          <span class="cat"><?php echo htmlspecialchars($row['category_name']); ?></span>
          <div class="details">
            <i class="fa-solid fa-phone" style="color:var(--orange-clr);font-size:.75rem;"></i>
            <?php echo htmlspecialchars($row['phone']); ?>
          </div>
        </div>

        <div class="order-right">
          <div class="order-qty">x<?php echo $row['quantity']; ?> &times; <?php echo number_format($row['item_price'],2); ?> LKR</div>
          <div class="order-total"><?php echo number_format($total,2); ?> LKR</div>
          <?php if($isDelivered && !$alreadyRated): ?>
            <a href="rate_order.php?order_id=<?php echo $row['id']; ?>" class="rate-btn">
              <i class="fa-solid fa-star"></i> Rate
            </a>
          <?php elseif($isDelivered && $alreadyRated): ?>
            <div class="rated-badge">
              <?php echo starDisplay($row['my_rating']); ?>
              <span>Rated</span>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Address -->
      <div class="order-address">
        <i class="fa-solid fa-location-dot"></i>
        <?php echo htmlspecialchars($row['address']); ?>
      </div>
    </div>
    <?php endwhile; ?>

  <?php else: ?>
  <div class="empty-state">
    <i class="fa-solid fa-bag-shopping"></i>
    <h2>No orders yet!</h2>
    <p style="color:var(--light-gray);margin-bottom:1.5rem;">Browse our menu and place your first order.</p>
    <a href="menu.php">Browse Menu</a>
  </div>
  <?php endif; ?>
</div>

<script>
document.getElementById('hamburger').addEventListener('click',()=>{
    document.getElementById('navlist').classList.toggle('navlist-active');
});
</script>
</body>
</html>
