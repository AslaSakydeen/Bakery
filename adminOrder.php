<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php"); exit;
}

$conn = mysqli_connect("localhost","root","","bakerydb");
if(!$conn) die("Connection failed: ".mysqli_connect_error());

// Handle status update
if(isset($_POST['update_status'])){
    $valid = ['Pending','Confirmed','Preparing','Out for Delivery','Delivered','Cancelled'];
    $oid   = intval($_POST['order_id']);
    $stat  = $_POST['status'] ?? '';
    if(in_array($stat, $valid)){
        mysqli_query($conn,"UPDATE orders_tab SET status='$stat' WHERE id='$oid'");
    }
    header("Location: adminOrder.php"); exit;
}

// Fetch all orders
$orders = mysqli_query($conn,"
    SELECT o.*, u.fullname AS customer_name, m.name AS item_name,
           c.name AS category_name, o.phone AS customer_phone, m.price
    FROM orders_tab o
    JOIN users u        ON o.user_id  = u.id
    JOIN menu_items m   ON o.item_id  = m.id
    JOIN categories c   ON m.category_id = c.id
    ORDER BY o.id DESC
");

// Status badge colours
function statusBadge($status){
    $map = [
        'Pending'          => ['#fff3e0','#e65100'],
        'Confirmed'        => ['#e3f2fd','#1565c0'],
        'Preparing'        => ['#f3e5f5','#6a1b9a'],
        'Out for Delivery' => ['#e0f7fa','#006064'],
        'Delivered'        => ['#e8f5e9','#2e7d32'],
        'Cancelled'        => ['#fde8e8','#c62828'],
    ];
    $colors = $map[$status] ?? ['#f0f0f0','#333'];
    return "<span style='background:{$colors[0]};color:{$colors[1]};padding:3px 10px;border-radius:50px;font-size:.78rem;font-weight:600;white-space:nowrap;'>$status</span>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin — Orders | Bakery</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{--orange-clr:#e96c28;--white-clr:#fff;--black-clr:#000;--gray-clr:#636363;--light-gray:#c4c4c4;}
*{margin:0;padding:0;box-sizing:border-box;font-family:'Poppins',sans-serif;}
body{display:flex;min-height:100vh;background:#f9f9f9;}

/* Sidebar */
.sidebar{width:220px;background:var(--black-clr);color:var(--white-clr);display:flex;flex-direction:column;flex-shrink:0;}
.sidebar h2{text-align:center;margin:1.2rem 0;color:var(--orange-clr);font-size:1.1rem;}
.sidebar a{color:var(--white-clr);padding:13px 20px;text-decoration:none;display:block;transition:.3s;font-size:.9rem;}
.sidebar a:hover,.sidebar a.active{background:var(--orange-clr);}
.sidebar a i{margin-right:8px;width:16px;}

/* Main */
.main-content{flex:1;padding:20px 30px;overflow-x:auto;}
.page-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;}
.page-header h1{font-size:1.5rem;color:var(--black-clr);}
.page-header h1 span{color:var(--orange-clr);}

/* Filter bar */
.filter-bar{display:flex;gap:.6rem;flex-wrap:wrap;margin-bottom:1.2rem;}
.filter-btn{padding:.35rem 1rem;border:1.5px solid #ddd;border-radius:50px;background:#fff;cursor:pointer;font-size:.82rem;font-weight:500;transition:.2s;}
.filter-btn.active,.filter-btn:hover{border-color:var(--orange-clr);color:var(--orange-clr);}

/* Table */
.table-wrapper{background:var(--white-clr);border-radius:1rem;box-shadow:0 4px 18px rgba(0,0,0,0.07);overflow:hidden;}
table{width:100%;border-collapse:collapse;min-width:900px;}
thead th{background:var(--orange-clr);color:#fff;padding:12px 14px;font-size:.85rem;font-weight:600;text-align:center;white-space:nowrap;}
tbody td{padding:11px 14px;text-align:center;border-bottom:1px solid #f0f0f0;font-size:.83rem;color:#444;vertical-align:middle;}
tbody tr:last-child td{border-bottom:none;}
tbody tr:hover{background:#fdf5f1;}

/* Status update form inside table */
.status-form{display:flex;align-items:center;gap:.4rem;justify-content:center;}
.status-form select{padding:5px 8px;border:1.5px solid #eee;border-radius:.5rem;font-size:.78rem;font-family:inherit;outline:none;cursor:pointer;}
.status-form select:focus{border-color:var(--orange-clr);}
.status-form button{background:var(--orange-clr);color:#fff;border:none;padding:5px 12px;border-radius:50px;font-size:.78rem;cursor:pointer;transition:.2s;font-weight:600;}
.status-form button:hover{background:var(--black-clr);}

.no-orders{text-align:center;padding:3rem;color:var(--gray-clr);}
.no-orders i{font-size:3rem;margin-bottom:1rem;display:block;color:var(--light-gray);}

@media(max-width:768px){.sidebar{width:60px;}.sidebar h2,.sidebar a span{display:none;}.sidebar a{text-align:center;padding:15px 0;}.sidebar a i{margin:0;}}
</style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
  <h2>Bakery Admin</h2>
  <a href="adminDashboard.php"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a>
  <a href="adminMenu.php"><i class="fa-solid fa-utensils"></i><span>Menu</span></a>
  <a href="adminOrder.php" class="active"><i class="fa-solid fa-bag-shopping"></i><span>Orders</span></a>
  <a href="adminUser.php"><i class="fa-solid fa-users"></i><span>Users</span></a>
  <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
</div>

<div class="main-content">
  <div class="page-header">
    <h1>All <span>Orders</span></h1>
    <span style="font-size:.85rem;color:var(--gray-clr);">
      Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?>
    </span>
  </div>

  <!-- Status filter buttons -->
  <div class="filter-bar">
    <button class="filter-btn active" onclick="filterOrders('all',this)">All</button>
    <button class="filter-btn" onclick="filterOrders('Pending',this)">Pending</button>
    <button class="filter-btn" onclick="filterOrders('Confirmed',this)">Confirmed</button>
    <button class="filter-btn" onclick="filterOrders('Preparing',this)">Preparing</button>
    <button class="filter-btn" onclick="filterOrders('Out for Delivery',this)">Out for Delivery</button>
    <button class="filter-btn" onclick="filterOrders('Delivered',this)">Delivered</button>
    <button class="filter-btn" onclick="filterOrders('Cancelled',this)">Cancelled</button>
  </div>

  <div class="table-wrapper">
    <?php if(mysqli_num_rows($orders) > 0): ?>
    <table id="ordersTable">
      <thead>
        <tr>
          <th>#</th>
          <th>Customer</th>
          <th>Item</th>
          <th>Category</th>
          <th>Phone</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Total (LKR)</th>
          <th>Address</th>
          <th>Date</th>
          <th>Status</th>
          <th>Update</th>
        </tr>
      </thead>
      <tbody>
        <?php while($row = mysqli_fetch_assoc($orders)): ?>
        <tr data-status="<?php echo htmlspecialchars($row['status']); ?>">
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
          <td><?php echo htmlspecialchars($row['item_name']); ?></td>
          <td><?php echo htmlspecialchars($row['category_name']); ?></td>
          <td><?php echo htmlspecialchars($row['customer_phone']); ?></td>
          <td><?php echo number_format($row['price'],2); ?></td>
          <td><?php echo $row['quantity']; ?></td>
          <td><?php echo number_format($row['price']*$row['quantity'],2); ?></td>
          <td style="max-width:140px;word-break:break-word;"><?php echo htmlspecialchars($row['address']); ?></td>
          <td><?php echo $row['created_at'] ?? 'N/A'; ?></td>
          <td><?php echo statusBadge($row['status']); ?></td>
          <td>
            <form method="POST" class="status-form">
              <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
              <select name="status">
                <?php
                $statuses = ['Pending','Confirmed','Preparing','Out for Delivery','Delivered','Cancelled'];
                foreach($statuses as $s){
                    $sel = $row['status'] === $s ? 'selected' : '';
                    echo "<option value='$s' $sel>$s</option>";
                }
                ?>
              </select>
              <button type="submit" name="update_status">Save</button>
            </form>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <?php else: ?>
    <div class="no-orders">
      <i class="fa-solid fa-inbox"></i>
      No orders found.
    </div>
    <?php endif; ?>
  </div>
</div>

<script>
function filterOrders(status, btn){
    document.querySelectorAll('.filter-btn').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('#ordersTable tbody tr').forEach(row=>{
        if(status === 'all' || row.dataset.status === status){
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
</body>
</html>
