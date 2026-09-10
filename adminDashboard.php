<?php
session_start();

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php"); 
    exit;
}

require_once 'db.php';
if(!$conn){ die("Connection failed: ".mysqli_connect_error()); }

$totalMenu = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM menu_items")
)['total'];

$totalOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders_tab")
)['total'];

$pendingOrders = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders_tab WHERE status='Pending'")
)['total'];

$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard | Bakery</title>
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

@media screen and (max-width:768px){
    .sidebar{width:60px;}
    .sidebar h2, .sidebar a span{display:none;}
    .sidebar a{text-align:center;padding:15px 0;}
    .sidebar a i{margin:0;}
}

/* Dashboard specific */
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--white-clr);
    border-radius: 1rem;
    padding: 1.8rem;
    box-shadow: 0 4px 18px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    gap: 1.2rem;
    transition: 0.3s;
    border: 1px solid #f0f0f0;
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}
.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.icon-orange { background: #fff3e0; color: #e65100; }
.icon-blue   { background: #e3f2fd; color: #1565c0; }
.icon-green  { background: #e8f5e9; color: #2e7d32; }
.icon-purple { background: #f3e5f5; color: #6a1b9a; }

.stat-info h3 {
    font-size: 0.85rem;
    color: var(--gray-clr);
    font-weight: 500;
    margin-bottom: 0.2rem;
}
.stat-info p {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--black-clr);
}

.quick-actions h2 {
    font-size: 1.2rem;
    margin-bottom: 1rem;
}
.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
.action-btn {
    background: var(--white-clr);
    color: var(--black-clr);
    text-decoration: none;
    padding: 1rem;
    border-radius: 0.8rem;
    text-align: center;
    font-weight: 600;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: 0.3s;
    border: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
}
.action-btn i {
    font-size: 1.5rem;
    color: var(--orange-clr);
}
.action-btn:hover {
    background: var(--orange-clr);
    color: var(--white-clr);
    border-color: var(--orange-clr);
}
.action-btn:hover i {
    color: var(--white-clr);
}
</style>
</head>
<body>

<div class="sidebar">
  <h2>Bakery Admin</h2>
  <a href="adminDashboard.php" class="active"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a>
  <a href="adminMenu.php"><i class="fa-solid fa-utensils"></i><span>Menu</span></a>
  <a href="adminOrder.php"><i class="fa-solid fa-bag-shopping"></i><span>Orders</span></a>
  <a href="adminUser.php"><i class="fa-solid fa-users"></i><span>Users</span></a>
  <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
</div>

<div class="main-content">
  <div class="page-header">
    <h1>Dashboard <span>Overview</span></h1>
    <span style="font-size:.85rem;color:var(--gray-clr);">Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?></span>
  </div>

  <div class="dashboard-grid">
    <div class="stat-card">
      <div class="stat-icon icon-orange"><i class="fa-solid fa-utensils"></i></div>
      <div class="stat-info">
        <h3>Total Menu Items</h3>
        <p><?php echo $totalMenu; ?></p>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon icon-blue"><i class="fa-solid fa-receipt"></i></div>
      <div class="stat-info">
        <h3>Total Orders</h3>
        <p><?php echo $totalOrders; ?></p>
      </div>
    </div>

    <div class="stat-card" style="border-left: 4px solid var(--orange-clr);">
      <div class="stat-icon icon-purple"><i class="fa-solid fa-clock"></i></div>
      <div class="stat-info">
        <h3>Pending Orders</h3>
        <p style="color:var(--orange-clr);"><?php echo $pendingOrders; ?></p>
      </div>
    </div>

    <div class="stat-card">
      <div class="stat-icon icon-green"><i class="fa-solid fa-users"></i></div>
      <div class="stat-info">
        <h3>Total Users</h3>
        <p><?php echo $totalUsers; ?></p>
      </div>
    </div>
  </div>

  <div class="quick-actions">
    <h2>Quick Actions</h2>
    <div class="action-grid">
      <a href="adminMenu.php" class="action-btn">
        <i class="fa-solid fa-plus-circle"></i>
        Add New Menu Item
      </a>
      <a href="adminOrder.php" class="action-btn">
        <i class="fa-solid fa-boxes-packing"></i>
        Process Orders
      </a>
      <a href="adminUser.php" class="action-btn">
        <i class="fa-solid fa-user-shield"></i>
        Manage Users
      </a>
    </div>
  </div>
</div>

</body>
</html>
