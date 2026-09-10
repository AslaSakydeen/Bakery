<?php
require_once 'db.php';
if(!$conn){ die("Connection failed: ".mysqli_connect_error()); }

$results = [];

// 1. Add stock column to menu_items
$r1 = mysqli_query($conn, "ALTER TABLE menu_items ADD COLUMN IF NOT EXISTS stock INT DEFAULT 100");
$results[] = $r1 ? "✅ Added 'stock' column to menu_items" : "⚠️ menu_items.stock: ".mysqli_error($conn);

// 2. Add status column to orders_tab
$r2 = mysqli_query($conn, "ALTER TABLE orders_tab ADD COLUMN IF NOT EXISTS status ENUM('Pending','Confirmed','Preparing','Out for Delivery','Delivered','Cancelled') DEFAULT 'Pending'");
$results[] = $r2 ? "✅ Added 'status' column to orders_tab" : "⚠️ orders_tab.status: ".mysqli_error($conn);

// 3. Create cart table
$r3 = mysqli_query($conn, "CREATE TABLE IF NOT EXISTS cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    quantity INT DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX(user_id)
)");
$results[] = $r3 ? "✅ Created 'cart' table" : "⚠️ cart table: ".mysqli_error($conn);

// 4. Create ratings table
$r4 = mysqli_query($conn, "CREATE TABLE IF NOT EXISTS ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    order_id INT NOT NULL,
    rating INT NOT NULL,
    review TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_order_rating (order_id)
)");
$results[] = $r4 ? "✅ Created 'ratings' table" : "⚠️ ratings table: ".mysqli_error($conn);

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Setup</title>
        <link rel="stylesheet" href="global.css">
    <style>
        body { font-family: Poppins, sans-serif; max-width: 600px; margin: 4rem auto; padding: 2rem; background: #f9f9f9; }
        h2 { color: #e96c28; margin-bottom: 1.5rem; }
        .result { background: #fff; padding: 1rem 1.5rem; border-radius: 8px; margin-bottom: 0.8rem; box-shadow: 0 2px 6px rgba(0,0,0,0.08); font-size: 0.95rem; }
        a { display: inline-block; margin-top: 1.5rem; background: #e96c28; color: #fff; padding: 0.7rem 1.5rem; border-radius: 50px; text-decoration: none; font-weight: 600; }
        a:hover { background: #000; }
    </style>
</head>
<body>
    <h2>Database Setup Results</h2>
    <?php foreach($results as $r): ?>
        <div class="result"><?php echo $r; ?></div>
    <?php endforeach; ?>
    <a href="index.php">Go to Website</a>
</body>
</html>
