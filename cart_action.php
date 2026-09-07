<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(['success' => false, 'message' => 'Please login first']);
    exit;
}

$conn = mysqli_connect("localhost","root","","bakerydb");
if(!$conn){ echo json_encode(['success' => false, 'message' => 'DB error']); exit; }

$user_id = intval($_SESSION['user_id']);
$action  = $_POST['action'] ?? '';

if($action === 'add'){
    $item_id = intval($_POST['item_id']);
    $qty     = max(1, intval($_POST['quantity'] ?? 1));

    // Check stock
    $stockRow = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT stock, name FROM menu_items WHERE id='$item_id'"));
    if(!$stockRow){
        echo json_encode(['success' => false, 'message' => 'Item not found']); exit;
    }
    if($stockRow['stock'] <= 0){
        echo json_encode(['success' => false, 'message' => 'Sorry, this item is out of stock']); exit;
    }

    // Check if already in cart
    $existing = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT id, quantity FROM cart WHERE user_id='$user_id' AND item_id='$item_id'"));
    if($existing){
        $newQty = $existing['quantity'] + $qty;
        mysqli_query($conn, "UPDATE cart SET quantity='$newQty' WHERE id='{$existing['id']}'");
    } else {
        mysqli_query($conn, "INSERT INTO cart(user_id,item_id,quantity) VALUES('$user_id','$item_id','$qty')");
    }

    $countRow = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) as total FROM cart WHERE user_id='$user_id'"));
    echo json_encode([
        'success'    => true,
        'message'    => htmlspecialchars($stockRow['name']).' added to cart!',
        'cart_count' => intval($countRow['total'])
    ]);

} elseif($action === 'remove'){
    $cart_id = intval($_POST['cart_id']);
    mysqli_query($conn, "DELETE FROM cart WHERE id='$cart_id' AND user_id='$user_id'");
    echo json_encode(['success' => true]);

} elseif($action === 'update'){
    $cart_id = intval($_POST['cart_id']);
    $qty     = intval($_POST['quantity']);
    if($qty <= 0){
        mysqli_query($conn, "DELETE FROM cart WHERE id='$cart_id' AND user_id='$user_id'");
    } else {
        mysqli_query($conn, "UPDATE cart SET quantity='$qty' WHERE id='$cart_id' AND user_id='$user_id'");
    }
    echo json_encode(['success' => true]);

} elseif($action === 'count'){
    $countRow = mysqli_fetch_assoc(mysqli_query($conn,
        "SELECT COUNT(*) as total FROM cart WHERE user_id='$user_id'"));
    echo json_encode(['count' => intval($countRow['total'])]);

} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
