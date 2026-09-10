<?php
session_start();


if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php"); 
    exit;
}

require_once 'db.php';
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// delete 
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

   
    if ($delete_id === $_SESSION['user_id']) {
        echo "<script>
                alert('You cannot delete your own account.');
                window.location='adminUser.php';
              </script>";
        exit;
    }

    // Delete user from database
    $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

   
    header("Location: adminUser.php");
    exit;
}


$result = mysqli_query($conn, "SELECT id, fullname, email, role, created_at FROM users WHERE role!='admin' ORDER BY id");

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Users</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="global.css">

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
.main-content{flex:1;padding:20px 30px;overflow-x:auto;}
@media screen and (max-width:768px){
    .sidebar{width:60px;}
    .sidebar h2, .sidebar a span{display:none;}
    .sidebar a{text-align:center;padding:15px 0;}
    .sidebar a i{margin:0;}
}

h2 { color: #e96c28; text-align: center; margin-bottom: 20px; }
table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 18px rgba(0,0,0,0.07); border-radius: 10px; overflow: hidden; }
th, td { padding: 12px; text-align: center; }
th { background-color: var(--orange-clr); color: #fff; text-align: center;}
td { border-bottom: 1px solid #f0f0f0;}
tr:last-child td { border-bottom: none; }
tr:hover { background-color: #fdf5f1; }
.action-btn { text-decoration: none; padding: 5px 10px; border-radius: 5px; color: #fff; font-weight: 500; transition: 0.3s; }
.action-btn.delete { background-color: #dc3545; }
.action-btn.delete:hover { background-color: #a71d2a; }
</style>
</head>
<body>

<div class="sidebar">
  <h2>Bakery Admin</h2>
  <a href="adminDashboard.php"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a>
  <a href="adminMenu.php"><i class="fa-solid fa-utensils"></i><span>Menu</span></a>
  <a href="adminOrder.php"><i class="fa-solid fa-bag-shopping"></i><span>Orders</span></a>
  <a href="adminUser.php" class="active"><i class="fa-solid fa-users"></i><span>Users</span></a>
  <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
</div>

<div class="main-content">
<h2>Registered Users</h2>

<?php if(mysqli_num_rows($result) > 0): ?>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Created At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['fullname']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo $row['role']; ?></td>
            <td><?php echo $row['created_at']; ?></td>
            <td>
                <a href="adminUser.php?delete_id=<?php echo $row['id']; ?>" class="action-btn delete" 
                   onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php else: ?>
<p style="text-align:center; color:#e96c28;">No users registered yet.</p>
<?php endif; ?>
</div>
</body>
</html>
