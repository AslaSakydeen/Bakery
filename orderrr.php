<?php
session_start();

require_once 'db.php';
if(!$conn){
    die("Connection Failed");
}

 $user_id = $_SESSION['user_id'];

$checkUser = mysqli_query($conn, "SELECT id, fullname FROM users WHERE id='$user_id'");
if(mysqli_num_rows($checkUser) === 0){
    
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$user = mysqli_fetch_assoc($checkUser);
$_SESSION['name'] = $user['fullname']; 




$item = [];

// Fetch item
if(isset($_GET['id']) && !empty($_GET['id'])){
    $item_id = $_GET['id'];

    $query = mysqli_query($conn,"
        SELECT menu_items.*, categories.name AS category
        FROM menu_items
        JOIN categories ON menu_items.category_id = categories.id
        WHERE menu_items.id = '$item_id'
    ");

    if(mysqli_num_rows($query) > 0){
        $item = mysqli_fetch_assoc($query);
    }
}

// If item not found then redirect
if(empty($item)){
    header("Location: menu.php");
    exit;
}

// Save order
if(isset($_POST['place_order'])){
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['item_id'];
    $qty = $_POST['quantity'];
    $phn = $_POST['phone'];
    $address = $_POST['address'];

    mysqli_query($conn,"INSERT INTO orders_tab(user_id,item_id,quantity,phone,address)
        VALUES('$user_id','$item_id','$qty','$phn','$address')");

    
    $_SESSION['order_success'] = true;

    
    header("Location: orderrr.php?id=$item_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Order | Bakery</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="global.css">

<style>
.order-wrapper{
    max-width: 600px;
    margin: 3rem auto;
    background: #fff;
    padding: 2rem;
    border-radius: 1rem;
    box-shadow: rgba(0,0,0,0.15) 0px 4px 12px;
}
.order-wrapper h2{
    text-align: center;
    margin-bottom: 1.5rem;
}
.order-form input,
.order-form textarea{
    width: 100%;
    padding: .7rem;
    margin-bottom: 1rem;
    border: 1px solid var(--light-gray);
    border-radius: .5rem;
}
button{
     background-color: var(--orange-clr);
    color: var(--white-clr);
    border: .15rem solid var(--orange-clr);
    padding: .55rem 1.5rem;
    border-radius: 5rem;
    font-size: 1rem;
    margin-top: 1rem;
    text-decoration: none;
    font-weight: bold;
}
button:hover{
    background-color: var(--black-clr);
    color: var(--orange-clr);
}
</style>
</head>
<body>

<header>
    <nav class="navbar container">
        <div class="logo">
            <a href="index.html" class="logo-txt">Bake<span>ry.</span></a>
        </div>
        <ul class="navlist">
            <li><a href="index.php" class="navlinks">Home</a></li>
            <li><a href="menu.php" class="navlinks">Menu</a></li>
            <li><a href="orderrr.php" class="navlinks active">Order</a></li>
        </ul>
    </nav>
</header>

<div class="order-wrapper">
    <h2>Place Your Order</h2>

   
    <?php
    if(isset($_SESSION['order_success'])){
        echo "<p style='color:green; text-align:center;'>✅ Order placed successfully!</p>";
        unset($_SESSION['order_success']); 
    }
    ?>

    <form method="POST" class="order-form" id="orderForm">

        <input type="text" value="<?php echo $_SESSION['name']; ?>" readonly>
        <input type="text" value="<?php echo $item['name']; ?>" readonly>
        <input type="text" value="<?php echo $item['category']; ?>" readonly>
        <input type="text" id="price" value="<?php echo $item['price']; ?>" readonly>
        <input type="number" name="quantity" id="quantity" min="1" value="1" required>
        <input type="tel" name="phone" id="phone" placeholder="Phone Number" required>
        <input type="text" id="total" value="<?php echo $item['price']; ?>" readonly>
        <textarea name="address" placeholder="Delivery Address" required></textarea>
        <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
        <button type="submit" name="place_order">Confirm Order</button>

    </form>
</div>

<script>
// Calculate total price 
const priceInput = document.getElementById('price');
const quantityInput = document.getElementById('quantity');
const totalInput = document.getElementById('total');

function updateTotal() {
    const price = parseFloat(priceInput.value);
    const quantity = parseInt(quantityInput.value);
    const total = price * quantity;
    totalInput.value = total.toFixed(2) + " LKR";
}

quantityInput.addEventListener('input', updateTotal);
updateTotal();
</script>

</body>
</html>
