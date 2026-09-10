

<?php
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}
require_once 'db.php';
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}

if(!isset($_GET['id'])){
    echo "No item selected.";
    exit;
}

$id = $_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM menu_items WHERE id='$id'");
if(mysqli_num_rows($result) == 0){
    echo "Item not found.";
    exit;
}

$item = mysqli_fetch_assoc($result);


if(isset($_POST['update'])){
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $image = time().'_'.$_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "images/".$image);
        $sql = "UPDATE menu_items SET name='$name', description='$description', price='$price', image='$image' WHERE id='$id'";
    } else {
        $sql = "UPDATE menu_items SET name='$name', description='$description', price='$price' WHERE id='$id'";
    }

    if(mysqli_query($conn,$sql)){
        header("Location: adminMenu.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Update Menu Item</title>
<style>

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    margin: 2rem 0;
    color: #e96c28;
}

/* Form Container */
form {
    max-width: 500px;
    margin: 2rem auto;
    background-color: #fff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    transition: 0.3s ease-in-out;
}

form:hover {
    box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
}


form label {
    font-weight: 600;
    display: block;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
    color: #333;
}


form input[type="text"],
form input[type="file"],
form textarea {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 1rem;
    font-family: inherit;
    transition: 0.3s;
}

form input[type="text"]:focus,
form textarea:focus,
form input[type="file"]:focus {
    outline: none;
    border-color: #e96c28;
    box-shadow: 0 0 5px rgba(233, 108, 40, 0.5);
}

form textarea {
    resize: vertical;
    min-height: 100px;
}


form img {
    max-width: 120px;
    margin: 0.5rem 0;
    border-radius: 8px;
    display: block;
}


form button {
    background-color: #e96c28;
    color: #fff;
    border: none;
    padding: 0.8rem;
    border-radius: 50px;
    font-size: 1rem;
    font-weight: 600;
    margin-top: 1.5rem;
    cursor: pointer;
    transition: 0.3s;
}

form button:hover {
    background-color: #fff;
    color: #e96c28;
    border: 1px solid #e96c28;
}


.msg {
    text-align: center;
    color: green;
    font-weight: bold;
    margin-bottom: 1rem;
}


@media screen and (max-width: 600px) {
    form {
        padding: 1.5rem;
    }

    form button {
        font-size: 0.95rem;
        padding: 0.7rem;
    }
}

</style>
</head>
<body>
<h2 style="text-align:center;">Update Menu Item</h2>

<?php if(!empty($msg)) echo "<p class='msg'>$msg</p>"; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Food Name:</label>
    <input type="text" name="name" value="<?php echo $item['name']; ?>" required>

    <label>Description:</label>
    <textarea name="description" required><?php echo $item['description']; ?></textarea>

    <label>Price:</label>
    <input type="text" name="price" value="<?php echo $item['price']; ?>" required>

    <label>Current Image:</label>
    <img src="images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">

    <label>Change Image (optional):</label>
    <input type="file" name="image">

    <button type="submit" name="update">Update Item</button>
</form>
</body>
</html>
