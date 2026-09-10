<?php
require_once 'db.php';

if(!$conn){
    die("Database connection failed");
}
$error = "";

if(isset($_POST['register'])){
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    if($_POST['password'] !== $_POST['confirm_password']){
    $error = "Passwords do not match";
} else {

     $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");

        if(mysqli_num_rows($check) > 0){
            $error = "Email already registered";
        } 
        else {

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(fullname,email,password) 
            VALUES('$name','$email','$password')";
    
    if(mysqli_query($conn,$sql)){
        header("Location: login.php");
    }
}
}}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register | Bakery</title>
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
    <link rel="stylesheet" href="global.css">
</head>
<body>

<section class="other-section container">
    <div class="contact-form" style="max-width:450px;margin:auto;">
        <h3>Create Account</h3>

        <form method="POST">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
             
<!-- email already register error message -->
             <?php if($error != ""){ ?>
                <p style="color:red;"><?php echo $error; ?></p>
               <?php } ?>

            <button type="submit" name="register">Register</button>
              
            <p style="margin-top:1rem;">
                Already have an account?
                <a href="login.php" style="color:#e96c28;">Login</a>
            </p>
        </form>
    </div>
</section>

</body>
</html>