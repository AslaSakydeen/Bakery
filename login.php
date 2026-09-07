<?php

session_start();

$conn = mysqli_connect("localhost","root","","bakerydb");

if(!$conn){
    die("Database connection failed");
}

$error = "";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result)==1){
        $row = mysqli_fetch_assoc($result);

        if(password_verify($password,$row['password'])){
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['name'] = $row['fullname'];

             if($row['role'] == 'admin'){
                header("Location: adminDashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }else{
            $error = "Invalid password";
        }
    }else{
        $error = "User not found";
    }
}


?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | Bakery</title>
    <link rel="stylesheet" href="style.css">
    <style>
        
:root{
    --orange-clr: #e96c28;
    --white-clr: #ffffff;
    --black-clr: #000000;
    --gray-clr: #636363;
    --light-gray: #e0e0e0;
}
*{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}
body{
    background: #f9f9f9;
}

.contact-form{
    background: var(--white-clr);
    padding: 2.5rem;
    border-radius: 1rem;
    box-shadow: rgba(0,0,0,0.15) 0px 8px 20px;
}

.contact-form h3{
    text-align: center;
    margin-bottom: 1.5rem;
    color: var(--black-clr);
    font-size: 1.5rem;
}

.contact-form input{
    width: 100%;
    padding: 0.8rem 1rem;
    margin-bottom: 1rem;
    border-radius: 0.5rem;
    border: 1px solid var(--light-gray);
    font-size: 0.95rem;
    outline: none;
    transition: 0.3s;
}

.contact-form input:focus{
    border-color: var(--orange-clr);
}

.contact-form button{
    width: 100%;
    padding: 0.8rem;
    background: var(--orange-clr);
    color: var(--white-clr);
    border: none;
    border-radius: 2rem;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: 0.3s ease-in-out;
}

.contact-form button:hover{
    background: var(--black-clr);
}

.contact-form p{
    text-align: center;
    font-size: 0.9rem;
}

.contact-form a{
    text-decoration: none;
    font-weight: 500;
}

.contact-form a:hover{
    text-decoration: underline;
}

@media (max-width: 480px){
    .contact-form{
        padding: 2rem 1.5rem;
    }
}

    </style>
</head>
<body>

<section class="other-section container">
    <div class="contact-form" style="max-width:450px;margin:auto;">
        <h3>Login</h3>

        <?php if($error){ ?>
            <p style="color:red;"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit" name="login">Login</button>

            <p style="margin-top:1rem;">
                Don’t have an account?
                <a href="register.php" style="color:#e96c28;">Register</a>
            </p>
        </form>
    </div>
</section>

</body>
</html>