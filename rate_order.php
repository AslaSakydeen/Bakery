<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit; }

require_once 'db.php';
$user_id = intval($_SESSION['user_id']);
$order_id = intval($_GET['order_id'] ?? 0);

if(!$order_id){ header("Location: myOrder.php"); exit; }

// Fetch order — must belong to this user, and status = Delivered
$orderRow = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT o.id, o.status, o.created_at,
           m.id AS item_id, m.name AS item_name, m.image
    FROM orders_tab o
    JOIN menu_items m ON o.item_id = m.id
    WHERE o.id = '$order_id' AND o.user_id = '$user_id'
"));

if(!$orderRow){ header("Location: myOrder.php"); exit; }
if($orderRow['status'] !== 'Delivered'){ header("Location: myOrder.php"); exit; }

// Check already rated
$already = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id FROM ratings WHERE order_id='$order_id'"));
if($already){ header("Location: myOrder.php?already_rated=1"); exit; }

$successMsg = '';
$errorMsg   = '';

if(isset($_POST['submit_rating'])){
    $rating = intval($_POST['rating']);
    $review = mysqli_real_escape_string($conn, trim($_POST['review']));
    $item_id = $orderRow['item_id'];

    if($rating < 1 || $rating > 5){
        $errorMsg = "Please select a star rating.";
    } else {
        mysqli_query($conn,"INSERT INTO ratings(user_id, item_id, order_id, rating, review)
            VALUES('$user_id','$item_id','$order_id','$rating','$review')");
        header("Location: myOrder.php?rated=1");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rate Your Order | Bakery</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--orange-clr:#e96c28;--black-clr:#000;--white-clr:#fff;--gray-clr:#636363;--light-gray:#c4c4c4;--dawn-pink:#fae7e5;}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{background:linear-gradient(135deg,#fff8f4 0%,#f5f5f5 100%);min-height:100vh;display:flex;flex-direction:column;}

.rate-wrapper{max-width:480px;width:90%;margin:3rem auto;background:var(--white-clr);border-radius:1.5rem;box-shadow:0 10px 40px rgba(233,108,40,.1);overflow:hidden;}

/* Item preview */
.item-preview{background:linear-gradient(135deg,var(--orange-clr),#ff8c00);padding:2rem;text-align:center;color:#fff;}
.item-preview img{width:100px;height:100px;object-fit:cover;border-radius:50%;border:4px solid rgba(255,255,255,.4);margin-bottom:1rem;}
.item-preview h2{font-size:1.3rem;margin-bottom:.3rem;}
.item-preview p{font-size:.85rem;opacity:.85;}

/* Rating form body */
.rate-body{padding:2rem;}
.rate-title{text-align:center;font-size:1.05rem;color:var(--gray-clr);margin-bottom:1.8rem;}

/* Star widget */
.star-widget{display:flex;flex-direction:row-reverse;justify-content:center;gap:.4rem;margin-bottom:1.5rem;}
.star-widget input[type="radio"]{display:none;}
.star-widget label{font-size:2.2rem;cursor:pointer;color:#ddd;transition:.2s;}
.star-widget label:hover,
.star-widget label:hover ~ label,
.star-widget input:checked ~ label{color:#ffa000;}

.star-hint{text-align:center;font-size:.82rem;color:var(--light-gray);margin-top:-.8rem;margin-bottom:1.2rem;height:1.2rem;}

.form-group{margin-bottom:1.2rem;}
.form-group label{display:block;font-size:.85rem;font-weight:500;color:var(--gray-clr);margin-bottom:.4rem;}
.form-group textarea{width:100%;padding:.75rem 1rem;border:1.5px solid #eee;border-radius:.7rem;font-size:.9rem;font-family:inherit;resize:vertical;min-height:90px;outline:none;transition:.2s;}
.form-group textarea:focus{border-color:var(--orange-clr);box-shadow:0 0 0 3px rgba(233,108,40,.08);}

.submit-btn{width:100%;background:var(--orange-clr);color:#fff;border:none;padding:1rem;border-radius:50px;font-size:1rem;font-weight:700;cursor:pointer;transition:.3s;display:flex;align-items:center;justify-content:center;gap:.5rem;}
.submit-btn:hover{background:var(--black-clr);}
.back-link{display:block;text-align:center;color:var(--gray-clr);text-decoration:none;margin-top:1rem;font-size:.85rem;}
.back-link:hover{color:var(--orange-clr);}
.error-msg{background:#fde8e8;border-left:4px solid #e53935;color:#c62828;padding:.8rem 1rem;border-radius:.5rem;margin-bottom:1rem;font-size:.88rem;}

/* Star label hints */
</style>
<link rel="stylesheet" href="global.css">
</head>
<body>

<header>
  <nav class="navbar container">
    <div class="logo"><a href="index.php" class="logo-txt">Bake<span>ry.</span></a></div>
    <ul class="navlist"><li><a href="myOrder.php" class="navlinks">← Back to My Orders</a></li></ul>
    <div class="nav-icons">
      <a href="login.php"  class="icons-link"><i class="fa-solid fa-user"></i></a>
      <a href="logout.php" class="icons-link"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    </div>
  </nav>
</header>

<div class="rate-wrapper">
  <!-- Item Preview -->
  <div class="item-preview">
    <img src="images/<?php echo htmlspecialchars($orderRow['image']); ?>" alt="<?php echo htmlspecialchars($orderRow['item_name']); ?>">
    <h2><?php echo htmlspecialchars($orderRow['item_name']); ?></h2>
    <p>Order #<?php echo $order_id; ?> &bull; <?php echo date('M d, Y', strtotime($orderRow['created_at'])); ?></p>
  </div>

  <div class="rate-body">
    <p class="rate-title">How was your experience? Leave a rating!</p>

    <?php if(!empty($errorMsg)): ?>
    <div class="error-msg"><?php echo $errorMsg; ?></div>
    <?php endif; ?>

    <form method="POST" id="rateForm">
      <!-- Star Rating -->
      <div class="star-widget" id="starWidget">
        <input type="radio" id="s5" name="rating" value="5">
        <label for="s5" title="Excellent">&#9733;</label>
        <input type="radio" id="s4" name="rating" value="4">
        <label for="s4" title="Good">&#9733;</label>
        <input type="radio" id="s3" name="rating" value="3">
        <label for="s3" title="Average">&#9733;</label>
        <input type="radio" id="s2" name="rating" value="2">
        <label for="s2" title="Poor">&#9733;</label>
        <input type="radio" id="s1" name="rating" value="1">
        <label for="s1" title="Terrible">&#9733;</label>
      </div>
      <div class="star-hint" id="starHint">Click a star to rate</div>

      <div class="form-group">
        <label>Your Review <span style="color:var(--light-gray);font-weight:400;">(optional)</span></label>
        <textarea name="review" placeholder="Tell us what you thought about this item..."><?php echo htmlspecialchars($_POST['review'] ?? ''); ?></textarea>
      </div>

      <button type="submit" name="submit_rating" class="submit-btn">
        <i class="fa-solid fa-star"></i> Submit Rating
      </button>
    </form>
    <a href="myOrder.php" class="back-link">← Back to My Orders</a>
  </div>
</div>

<script>
const hints = {1:'Terrible',2:'Poor',3:'Average',4:'Good',5:'Excellent'};
const hintEl = document.getElementById('starHint');
document.querySelectorAll('#starWidget input[type="radio"]').forEach(radio => {
    radio.addEventListener('change', () => {
        hintEl.textContent = hints[radio.value];
        hintEl.style.color = '#ffa000';
    });
});
</script>
</body>
</html>
