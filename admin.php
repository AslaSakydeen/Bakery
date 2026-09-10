!<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | Bakery</title>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>

.admin-container{
    width: 90%;
    margin: 3rem auto;
}

.admin-header{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.admin-header h2{
    font-size: 1.8rem;
}

.add-btn{
    background: var(--orange-clr);
    color: #fff;
    padding: .6rem 1.4rem;
    border-radius: 2rem;
    text-decoration: none;
}

.food-grid{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px,1fr));
    gap: 2rem;
}

.admin-card{
    background: #fff;
    border-radius: 1rem;
    box-shadow: rgba(0,0,0,0.15) 0px 3px 6px;
    overflow: hidden;
}

.admin-card img{
    width: 100%;
    height: 160px;
    object-fit: cover;
}

.admin-card .info{
    padding: 1rem;
    text-align: center;
}

.admin-card h4{
    margin-bottom: .3rem;
}

.admin-card p{
    color: var(--gray-clr);
    font-size: .9rem;
}

.admin-actions{
    display: flex;
    justify-content: space-around;
    margin-top: 1rem;
}

.admin-actions a{
    text-decoration: none;
    font-size: .9rem;
    padding: .4rem 1rem;
    border-radius: 1.5rem;
}

.edit-btn{
    background: #4caf50;
    color: #fff;
}

.delete-btn{
    background: #e53935;
    color: #fff;
}
</style>
<link rel="stylesheet" href="global.css">
</head>

<body>

<header>
<nav class="navbar container">
    <div class="logo">
        <a class="logo-txt">Bake<span>ry.</span> Admin</a>
    </div>
    <a href="..User/index.html" class="navlinks">Back to Website</a>
</nav>
</header>

<main class="admin-container">

<div class="admin-header">
    <h2>Food Management</h2>
    <a href="add_food.php" class="add-btn">
        <i class="fa fa-plus"></i> Add Food
    </a>
</div>

<div class="food-grid">

<!-- Food Card -->
<div class="admin-card">
    <img src="../images/1.jpg">
    <div class="info">
        <h4>Chocolate Cake</h4>
        <p>1500 LKR</p>
        <div class="admin-actions">
            <a href="edit_food.php?id=1" class="edit-btn">Edit</a>
            <a href="delete_food.php?id=1" class="delete-btn">Delete</a>
        </div>
    </div>
</div>

<div class="admin-card">
    <img src="../images/pastry.jpg">
    <div class="info">
        <h4>Fruit Pastry</h4>
        <p>300 LKR</p>
        <div class="admin-actions">
            <a class="edit-btn">Edit</a>
            <a class="delete-btn">Delete</a>
        </div>
    </div>
</div>

</div>
</main>

</body>
</html>
