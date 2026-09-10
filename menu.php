<?php
session_start();
require_once 'db.php';
if(!$conn){ die("Connection Failed: ".mysqli_connect_error()); }

// Cart count for navbar badge
$cartCount = 0;
if(isset($_SESSION['user_id'])){
    $uid = intval($_SESSION['user_id']);
    $cc  = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS c FROM cart WHERE user_id='$uid'"));
    $cartCount = intval($cc['c']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menu | Bakery</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
    --orange-clr: #e96c28;
    --white-clr:#fff;
    --black-clr: #000;
    --gray-clr:#636363;
    --light-gray: #c4c4c4;
    --dawn-pink: #fae7e5;
    --frosted-mint: #dcfef9;
}
.container{ width:80%; margin:auto; }

/* Category Tabs */
.category-tabs{display:flex;justify-content:center;gap:1.5rem;margin:2rem 0;flex-wrap:wrap;}
.category-tabs button{background-color:var(--orange-clr);color:#fff;border:none;padding:.5rem 1.5rem;border-radius:50px;cursor:pointer;font-weight:600;transition:.3s;}
.category-tabs button.active,
.category-tabs button:hover{background-color:var(--black-clr);color:var(--orange-clr);}
.con{align-items:center;margin-left:380px;margin-bottom:50px;}

/* Menu Items Grid */
.menu-items{display:flex;flex-wrap:wrap;gap:2rem;justify-content:center;margin-bottom:3rem;}

/* Menu Card */
.menu-card{width:230px;text-align:center;border-radius:14px;box-shadow:0 4px 18px rgba(0,0,0,0.1);padding:1rem;display:flex;flex-direction:column;justify-content:space-between;transition:.3s;background:var(--white-clr);position:relative;}
.menu-card:hover{transform:translateY(-5px);box-shadow:0 8px 28px rgba(0,0,0,0.15);}
.menu-card img{width:100%;height:160px;object-fit:cover;border-radius:10px;}
.menu-card h3{margin:.6rem 0 .2rem;font-size:1rem;}
.menu-card p{font-size:.82rem;color:var(--gray-clr);margin-bottom:.4rem;line-height:1.4;}
.menu-card .price{font-weight:700;color:var(--black-clr);margin-bottom:.4rem;font-size:1rem;}

/* Stock badge */
.stock-badge{display:inline-block;font-size:.7rem;font-weight:600;padding:2px 10px;border-radius:50px;margin-bottom:.5rem;}
.badge-in{background:#e8f5e9;color:#2e7d32;}
.badge-low{background:#fff3e0;color:#e65100;}
.badge-out{background:#fde8e8;color:#c62828;}

/* Star display */
.star-display{display:flex;align-items:center;justify-content:center;gap:.2rem;margin-bottom:.5rem;}
.star-display i{font-size:.8rem;color:#ddd;}
.star-display i.filled{color:#ffa000;}
.star-display .rating-count{font-size:.75rem;color:var(--light-gray);margin-left:.2rem;}

/* Add to Cart btn */
.add-cart-btn{background:var(--orange-clr);color:#fff;padding:.55rem 1.2rem;border-radius:50px;border:none;font-weight:600;cursor:pointer;font-size:.88rem;transition:.3s;width:100%;display:flex;align-items:center;justify-content:center;gap:.5rem;}
.add-cart-btn:hover{background:var(--black-clr);color:var(--orange-clr);}
.add-cart-btn:disabled{background:var(--light-gray);cursor:not-allowed;color:#fff;}
.add-cart-btn.loading{opacity:.7;pointer-events:none;}

/* Cart icon in nav */
.cart-nav-icon{position:relative;}
.cart-nav-icon .badge{position:absolute;top:-7px;right:-7px;background:var(--orange-clr);color:#fff;font-size:10px;width:17px;height:17px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;transition:.3s;}

/* Toast notification */
#toast{position:fixed;bottom:2rem;right:2rem;background:#323232;color:#fff;padding:.85rem 1.5rem;border-radius:.8rem;font-size:.9rem;opacity:0;transform:translateY(1rem);transition:all .4s;z-index:9999;pointer-events:none;max-width:280px;display:flex;align-items:center;gap:.6rem;}
#toast.show{opacity:1;transform:translateY(0);}
#toast.success{background:#2e7d32;}
#toast.error{background:#c62828;}
</style>
<link rel="stylesheet" href="global.css">
</head>
<body>

<!-- Header -->
<header>
  <nav class="navbar container">
    <div class="logo">
      <a href="index.php" class="logo-txt">Bake<span>ry.</span></a>
    </div>
    <ul class="navlist" id="navlist">
      <li><a href="index.php"    class="navlinks">Home</a></li>
      <li><a href="aboutUs.php"  class="navlinks">About</a></li>
      <li><a href="menu.php"     class="navlinks active">Menu</a></li>
      <li><a href="myOrder.php"  class="navlinks">My Orders</a></li>
      <li><a href="contactUs.php" class="navlinks">Contact</a></li>
    </ul>
    <div class="nav-icons">
      <a href="cart.php" class="icons-link cart-nav-icon" title="Cart">
        <i class="fa-solid fa-cart-shopping"></i>
        <span class="badge" id="cart-badge" <?php echo $cartCount==0?'style="display:none"':''; ?>>
          <?php echo $cartCount; ?>
        </span>
      </a>
      <a href="login.php"  class="icons-link"><i class="fa-solid fa-user"></i></a>
      <a href="logout.php" class="icons-link"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    </div>
    <div class="hamburger" id="hamburger">
      <div class="burger"></div><div class="burger"></div><div class="burger"></div>
    </div>
  </nav>
</header>

<main class="container">
  <div class="con">
    <h2 class="centered-txt">Our Delicious Bakery Menu</h2>
    <p class="centered-txt">Click a category to explore our treats!</p>
  </div>

  <!-- Category Tabs -->
  <div class="category-tabs">
    <button class="tab-btn active" data-category="all">All</button>
    <?php
    $cats = mysqli_query($conn,"SELECT * FROM categories ORDER BY name");
    while($cat = mysqli_fetch_assoc($cats)){
        $slug = strtolower($cat['name']);
        echo "<button class='tab-btn' data-category='$slug'>".htmlspecialchars($cat['name'])."</button>";
    }
    ?>
  </div>

  <!-- Menu Items -->
  <div class="menu-items" id="menu-items">
    <?php
    $sql = "
        SELECT menu_items.*,
               categories.name AS category_name,
               COALESCE(ROUND(AVG(ratings.rating),1),0) AS avg_rating,
               COUNT(ratings.id) AS rating_count
        FROM menu_items
        INNER JOIN categories ON menu_items.category_id = categories.id
        LEFT  JOIN ratings    ON menu_items.id          = ratings.item_id
        GROUP BY menu_items.id
        ORDER BY categories.name, menu_items.name
    ";
    $result = mysqli_query($conn,$sql);

    while($row = mysqli_fetch_assoc($result)){
        $category   = strtolower($row['category_name']);
        $avgRating  = floatval($row['avg_rating']);
        $ratingCount= intval($row['rating_count']);
        $stock      = intval($row['stock']);
        $isOutOfStock = $stock <= 0;
        $isLowStock   = $stock > 0 && $stock <= 5;

        // Stock badge
        if($isOutOfStock){
            $badgeClass = 'badge-out'; $badgeText = 'Out of Stock';
        } elseif($isLowStock){
            $badgeClass = 'badge-low'; $badgeText = "Low Stock ($stock left)";
        } else {
            $badgeClass = 'badge-in';  $badgeText = 'In Stock';
        }

        // Stars HTML
        $starsHtml = '';
        for($i=1;$i<=5;$i++){
            $filled = $i <= round($avgRating) ? ' filled' : '';
            $starsHtml .= "<i class='fa-solid fa-star$filled'></i>";
        }
        $ratingLabel = $ratingCount > 0 ? "($avgRating &bull; $ratingCount)" : "(No reviews)";
    ?>
    <div class="menu-card" data-category="<?php echo $category; ?>">
      <img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
      <h3><?php echo htmlspecialchars($row['name']); ?></h3>
      <p><?php echo htmlspecialchars($row['description']); ?></p>

      <div class="star-display">
        <?php echo $starsHtml; ?>
        <span class="rating-count"><?php echo $ratingLabel; ?></span>
      </div>

      <span class="stock-badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></span>

      <div class="price"><?php echo number_format($row['price'],2); ?> LKR</div>

      <?php if($isOutOfStock): ?>
      <button class="add-cart-btn" disabled><i class="fa-solid fa-ban"></i> Out of Stock</button>
      <?php else: ?>
      <button class="add-cart-btn"
              onclick="addToCart(this, <?php echo $row['id']; ?>, '<?php echo htmlspecialchars(addslashes($row['name'])); ?>')">
        <i class="fa-solid fa-cart-plus"></i> Add to Cart
      </button>
      <?php endif; ?>
    </div>
    <?php } ?>
  </div>
</main>

<!-- Footer -->
<footer>
  <div class="column-container container mint-glow">
    <div class="card-container">
      <div class="column">
        <div class="logo"><a href="#" class="logo-txt">Bake<span>ry.</span></a></div>
        <p class="balanced-txt">Freshly baked delights made daily with quality ingredients and love.</p>
        <div class="icons">
          <a href="#" class="icons-link"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="#" class="icons-link"><i class="fa-brands fa-twitter"></i></a>
          <a href="#" class="icons-link"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="icons-link"><i class="fa-brands fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="column">
        <h4>Quick Links</h4>
        <ul class="footer-list">
          <li><a href="index.php"    class="footer-links">Home</a></li>
          <li><a href="menu.php"     class="footer-links">Menu</a></li>
          <li><a href="myOrder.php"  class="footer-links">My Orders</a></li>
          <li><a href="contactUs.php"class="footer-links">Contact</a></li>
        </ul>
      </div>
      <div class="column">
        <h4>Contact</h4>
        <ul class="footer-list">
          <li><i class="fa-solid fa-location-dot" style="color:var(--orange-clr);"></i>&nbsp;<a href="#" class="footer-links">ABC Street, Kurunegala</a></li>
          <li><i class="fa-solid fa-phone" style="color:var(--orange-clr);"></i>&nbsp;<a href="#" class="footer-links">0716260728</a></li>
          <li><i class="fa-solid fa-envelope" style="color:var(--orange-clr);"></i>&nbsp;<a href="#" class="footer-links">info@bakery.com</a></li>
        </ul>
      </div>
    </div>
    <div class="line">
      <h4 class="Copyright">&copy;Copyright Bakery All Rights Reserved</h4>
    </div>
  </div>
</footer>

<!-- Toast notification -->
<div id="toast"></div>

<script>
// Hamburger
document.getElementById('hamburger').addEventListener('click',()=>{
    document.getElementById('navlist').classList.toggle('navlist-active');
});

// Category filter
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const cat = btn.dataset.category;
        document.querySelectorAll('.menu-card').forEach(card => {
            card.style.display = (cat === 'all' || card.dataset.category === cat) ? 'flex' : 'none';
        });
    });
});

// Add to cart (AJAX)
function addToCart(btn, itemId, itemName){
    <?php if(!isset($_SESSION['user_id'])): ?>
    window.location.href = 'login.php';
    return;
    <?php endif; ?>

    btn.classList.add('loading');
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Adding...';

    fetch('cart_action.php', {
        method : 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body   : `action=add&item_id=${itemId}&quantity=1`
    })
    .then(r => r.json())
    .then(data => {
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Add to Cart';

        if(data.success){
            showToast('success', '🛒 ' + data.message);
            // Update cart badge
            const badge = document.getElementById('cart-badge');
            badge.textContent = data.cart_count;
            badge.style.display = 'flex';
        } else {
            showToast('error', '⚠️ ' + data.message);
        }
    })
    .catch(() => {
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="fa-solid fa-cart-plus"></i> Add to Cart';
        showToast('error', 'Something went wrong. Please try again.');
    });
}

function showToast(type, msg){
    const toast = document.getElementById('toast');
    toast.textContent = msg;
    toast.className   = 'show ' + type;
    setTimeout(() => { toast.className = ''; }, 3200);
}
</script>
</body>
</html>
