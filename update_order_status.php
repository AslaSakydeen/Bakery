<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php"); exit;
}

$conn = mysqli_connect("localhost","root","","bakerydb");
if(!$conn){ die("DB error"); }

$valid_statuses = ['Pending','Confirmed','Preparing','Out for Delivery','Delivered','Cancelled'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $order_id = intval($_POST['order_id']);
    $status   = $_POST['status'] ?? '';
    if(in_array($status, $valid_statuses)){
        mysqli_query($conn, "UPDATE orders_tab SET status='$status' WHERE id='$order_id'");
    }
}

header("Location: adminOrder.php");
exit;
