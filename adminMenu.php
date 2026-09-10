<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}
require_once 'db.php';
if(!$conn){ die("Connection failed: ".mysqli_connect_error()); }

// Insert
if(isset($_POST['save'])){
    $category_id = $_POST['category_id'];
    $name  = mysqli_real_escape_string($conn, $_POST['name']);
    $desc  = mysqli_real_escape_string($conn, $_POST['description']);
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock'] ?? 100);
    $image = "";
    if(isset($_FILES['image']) && $_FILES['image']['error']==0){
        $image = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/".$image);
    }
    mysqli_query($conn, "INSERT INTO menu_items(category_id,name,description,price,image,stock) VALUES('$category_id','$name','$desc','$price','$image','$stock')");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Delete
if(isset($_POST['delete'])){
    $id = intval($_POST['id']);
    mysqli_query($conn,"DELETE FROM menu_items WHERE id='$id'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Update Stock
if(isset($_POST['update_stock'])){
    $id    = intval($_POST['id']);
    $stock = intval($_POST['stock']);
    mysqli_query($conn,"UPDATE menu_items SET stock='$stock' WHERE id='$id'");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// Fetch items
$result = mysqli_query($conn,"
    SELECT menu_items.*, categories.name AS category_name
    FROM menu_items
    JOIN categories ON menu_items.category_id = categories.id
");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Menu | Bakery</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
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
.main-content{flex:1;padding:20px 30px;overflow-x:auto;}
@media screen and (max-width:768px){
    .sidebar{width:60px;}
    .sidebar h2, .sidebar a span{display:none;}
    .sidebar a{text-align:center;padding:15px 0;}
    .sidebar a i{margin:0;}
}
h2 {
    text-align: center;
    color: var(--black-clr);
    margin-bottom: 20px;
}
.insert-form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    background: var(--white-clr);
    padding: 15px;
    border-radius: 10px;
    max-width: 1000px;
    margin: 0 auto 30px auto;
    box-shadow: rgba(0,0,0,0.1) 0px 2px 6px;
}
.insert-form input[type="text"], 
.insert-form input[type="file"] {
    flex: 1 1 200px;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid var(--light-gray);
}
.insert-form button {
    background-color: var(--orange-clr);
    color: var(--white-clr);
    border: none;
    padding: 10px 20px;
    border-radius: 50px;
    cursor: pointer;
    transition: 0.3s;
}
.insert-form button:hover {
    background-color: var(--black-clr);
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
    background: var(--white-clr);
    border-radius: 10px;
    overflow: hidden;
    box-shadow: rgba(0,0,0,0.1) 0px 2px 6px;
}
th, td {
    padding: 12px;
    text-align: center;
}
th {
    background-color: var(--orange-clr);
    color: var(--white-clr);
}
td img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 5px;
}
td button {
    padding: 5px 10px;
    border-radius: 50px;
    border: none;
    cursor: pointer;
    margin: 2px;
    transition: 0.3s;
}
td button.delete {
    background: #e74c3c;
    color: #fff;
}
td button.delete:hover {
    background: #c0392b;
}
td button.edit {
    background: var(--orange-clr);
    color: var(--white-clr);
}
td button.edit:hover {
    background: var(--black-clr);
}
</style>

</head>
<body>

<div class="sidebar">
  <h2>Bakery Admin</h2>
  <a href="adminDashboard.php"><i class="fa-solid fa-gauge"></i><span>Dashboard</span></a>
  <a href="adminMenu.php" class="active"><i class="fa-solid fa-utensils"></i><span>Menu</span></a>
  <a href="adminOrder.php"><i class="fa-solid fa-bag-shopping"></i><span>Orders</span></a>
  <a href="adminUser.php"><i class="fa-solid fa-users"></i><span>Users</span></a>
  <a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i><span>Logout</span></a>
</div>

<div class="main-content">
<h2>Admin Menu Management</h2>

<!-- Insert Form -->
<form class="insert-form" method="POST" enctype="multipart/form-data">
    <select name="category_id" required>
    <option value="">Select Category</option>

    <?php
    $catResult = mysqli_query($conn, "SELECT * FROM categories");
    while($cat = mysqli_fetch_assoc($catResult)){
        echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
    }
    ?>
</select>
    <input type="text" name="name" placeholder="Food Name" required>
    <input type="text" name="description" placeholder="Description" required>
    <input type="text" name="price" placeholder="Price (LKR)" required>
    <input type="number" name="stock" placeholder="Initial Stock" min="0" value="100" required>
    <input type="file" name="image" required>
    <button type="submit" name="save">Add Item</button>
</form>

<!-- Table -->
<table>
<tr>
    <th>ID</th>
    <th>Category</th>
    <th>Name</th>
    <th>Description</th>
    <th>Price (LKR)</th>
    <th>Stock</th>
    <th>Image</th>
    <th>Actions</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo htmlspecialchars($row['category_name']); ?></td>
    <td><?php echo htmlspecialchars($row['name']); ?></td>
    <td><?php echo htmlspecialchars($row['description']); ?></td>
    <td><?php echo number_format($row['price'],2); ?></td>
    <td>
        <?php
        $stock = intval($row['stock']);
        if($stock <= 0) echo "<span style='color:#c62828;font-weight:600;'>Out (0)</span>";
        elseif($stock <= 5) echo "<span style='color:#e65100;font-weight:600;'>Low ($stock)</span>";
        else echo "<span style='color:#000;font-weight:600;'>$stock</span>";
        ?>
        <form method="POST" style="display:inline;margin-left:4px;">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <input type="number" name="stock" value="<?php echo $row['stock']; ?>" min="0" style="width:60px;padding:3px 5px;border:1px solid #ddd;border-radius:5px;font-size:.8rem;">
            <button type="submit" name="update_stock" style="background:var(--orange-clr);color:#fff;border:none;padding:4px 8px;border-radius:5px;cursor:pointer;font-size:.78rem;font-weight:600;">Set</button>
        </form>
    </td>
    <td><img src="images/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>"></td>
    <td>
        <form method="GET" action="update_item.php" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <button type="submit" class="edit">Edit</button>
        </form>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <button type="submit" name="delete" class="delete" onclick="return confirm('Delete this item?')">Delete</button>
        </form>
    </td>
</tr>
<?php } ?>
</table>
</div>
</body>
</html>
