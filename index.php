<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Website</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="global.css">
</head>
<body>
    
<!-- Header part -->
     <header>
       <nav class="navbar container">
        <div class="logo">
          <a href="#" class="logo-txt">
            Bake<span>ry.</span>
          </a>
        </div>
        <ul class="navlist" id="navlist">
            <li>
                <a href="#" class="navlinks active">
                    Home
                </a>
            </li>
            <li>
                <a href="aboutUs.php" class="navlinks">
                    About
                </a>
            </li>
            <li>
                <a href="menu.php" class="navlinks">
                    Menu
                </a>
            </li>
            <li>
                <a href="myOrder.php" class="navlinks">
                    My Orders
                </a>
            </li>
            <li>
                <a href="contactUs.php" class="navlinks">
                    Contact
                </a>
            </li>
        </ul>
        <div class="nav-icons">
            <a href="login.php" class="icons-link">
                <i class="fa-solid fa-user"></i>
            </a>
            <a href="logout.php" class="icons-link">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </div>
        <div class="hamburger" id="hamburger">
            <div class="burger"></div>
            <div class="burger"></div>
            <div class="burger"></div>
        </div>
       </nav>
     </header>

<!-- Section part -->
     <main>
        <!-- hero section -->
       <section class="hero-section container mint-glow">
        <div class="content">
          <h1>The perfect baked food everyday!</h1>
          <p>We bring you freshly baked delights made daily with quality ingredients and a lot of care. 
            From soft breads to delicious cakes and pastries, every bite is crafted to give you the perfect balance of taste,
             freshness, and happiness—just like homemade, but better.!</p>
          <div class="btn-container">
            <a href="aboutUs.php" class="but">Read More</a>
            <a href="menu.php" class="bordered-btn but">Order Now</a>
          </div>
        </div>
        <div class="image-container">
          <img src="images/cake4.jpg" class="pic">
        </div>
       </section>

       <!-- store section -->
       <section class="other-section container">
        <div class="column-container">
            <h2>Welcome To Our Store</h2>
            <p class="centered-txt">Step into our bakery where warmth, freshness, and flavor come together.
                 We offer a delightful range of freshly baked cakes, pastries, and treats made with quality ingredients and love.
                 Whether it’s a small craving or a special celebration, we’re here to make every moment sweeter.</p>
         </div>   
            <div class="card-container">
                <div class="card" data-type="category">
                    <img src="images/turnover.jpg" >
                    <h3>Pastry</h4>
                </div>
                <div class="card" data-type="category">
                    <img src="images/cake3.png">
                    <h3>Cake</h3>
                </div>
                <div class="card" data-type="category">
                    <img src="images/12.jpg">
                    <h3>Cookie</h3>
                </div>
                <div class="card" data-type="category">
                    <img src="images/3.jpg">
                    <h3>Sandwich</h3>
                </div>
            </div>
        </div>
       </section>

       <!-- about section -->
       <section class="other-section container">
         <div class="row-container">
            <div class="image-container">
                <img src="images/15.jpg" class="pic">
            </div>

            <div class="content">
                <h2>About Bakery</h2>
                <p>At Bakery, we believe baking is an art filled with passion, quality, and love. Every product we create is made using fresh ingredients and traditional recipes.
                     From cakes to pastries, our goal is to bring joy to your everyday moments and celebrations.give details for about us page</p>
                <a href="aboutUs.php" class="but">Read More</a>
            </div>
         </div>
       </section>

       <!-- featured food -->
       <section class="other-section container">
         <div class="column-container">
            <h2>Our Featured Food</h2>
            <p class="centered-txt">Discover our most loved bakery specials, freshly baked to perfection. 
                Each featured item is carefully selected for its rich taste,
                 quality ingredients, and beautiful presentation, perfect for treating yourself or sharing with loved ones !</p>
            <div class="card-container">
                <div class="card" data-type="items">
                    <div class="image-part">
                        <img src="images/2.jpg">
                    </div>
                    <div class="details">
                        <h4>500 LKR</h4>
                        <h4>Birthday Cake</h4>
                    </div>
                </div>
                <div class="card" data-type="items">
                    <div class="image-part">
                        <img src="images/1.jpg">
                    </div>
                    <div class="details">
                        <h4>500 LKR</h4>
                        <h4>Birthday Cake</h4>
                    </div>
                </div>
                <div class="card" data-type="items">
                    <div class="image-part">
                        <img src="images/3.jpg">
                    </div>
                    <div class="details">
                        <h4>500 LKR</h4>
                        <h4>Birthday Cake</h4>
                    </div>
                </div>
                <div class="card" data-type="items">
                    <div class="image-part">
                        <img src="images/4.jpg">
                    </div>
                    <div class="details">
                        <h4>500 LKR</h4>
                        <h4>Birthday Cake</h4>
                    </div>
                </div>
            </div>
            <div class="card-container">
                <div class="card" data-type="items">
                    <div class="image-part">
                        <img src="images/5.jpg">
                    </div>
                    <div class="details">
                        <h4>500 LKR</h4>
                        <h4>Birthday Cake</h4>
                    </div>
                </div>
                <div class="card" data-type="items">
                    <div class="image-part">
                        <img src="images/6.jpg">
                    </div>
                    <div class="details">
                        <h4>500 LKR</h4>
                        <h4>Birthday Cake</h4>
                    </div>
                </div>
                <div class="card" data-type="items">
                    <div class="image-part">
                        <img src="images/7.jpg">
                    </div>
                    <div class="details">
                        <h4>500 LKR</h4>
                        <h4>Birthday Cake</h4>
                    </div>
                </div>
                <div class="card" data-type="items">
                    <div class="image-part">
                        <img src="images/8.jpg">
                    </div>
                    <div class="details">
                        <h4>500 LKR</h4>
                        <h4>Birthday Cake</h4>
                    </div>
                </div>
            </div>
         </div>
       </section>
     </main>

<!-- Footer part -->
     <footer>
        <div class="column-container container mint-glow">
          <div class="card-container">
          <div class="column">
            <div class="logo">
               <a href="#" class="logo-txt">
                Bake<span>ry.</span>
               </a>
            </div>
            <p class="balanced-txt">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Officia blanditiis laboriosam quo unde quos totam!</p>
            <div class="icons">
                <a href="#" class="icons-link">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="#" class="icons-link">
                    <i class="fa-brands fa-twitter"></i>
                </a>
                <a href="#" class="icons-link">
                    <i class="fa-brands fa-instagram"></i>
                </a>
                <a href="#" class="icons-link">
                    <i class="fa-brands fa-linkedin-in"></i>
                </a>
            </div>  
        </div>
          <div class="column">
             <h4>Resources</h4>
             <ul class="footer-list">
                <li>
                    <a href="#" class="footer-links">Resource</a>
                </li>
                <li>
                    <a href="#" class="footer-links">Contact Us</a>
                </li>
                <li>
                    <a href="#" class="footer-links">FAQ</a>
                </li>
                <li>
                    <a href="#" class="footer-links">Email Support</a>
                </li>
                <li>
                    <a href="#" class="footer-links">API Documentation</a>
                </li>
             </ul>
          </div>
          <div class="column">
              <h4>Menu</h4>
              <ul class="footer-list">
                <li>
                    <a href="#" class="footer-links">Cupcake</a>
                </li>
                <li>
                    <a href="#" class="footer-links">Cookies</a>
                </li>
                <li>
                    <a href="#" class="footer-links">Cake</a>
                </li>
                <li>
                    <a href="#" class="footer-links">Pastry</a>
                </li>
                <li>
                    <a href="#" class="footer-links">Sandwich</a>
                </li>
             </ul>
          </div>
          <div class="column">
              <h4>Services</h4>
              <ul class="footer-list">
                <li>
                    <a href="#" class="footer-links">Event</a>
                </li>
                <li>
                    <a href="#" class="footer-links">Birthday</a>
                </li>
                <li>
                    <a href="#" class="footer-links">Restaurant</a>
                </li>
                <li>
                    <a href="#" class="footer-links">Wedding</a>
                </li>
             </ul>
          </div>
          <div class="column">
               <h4>Contact</h4>
               <ul class="footer-list">
                <li>
                    <a href="#" class="icons-link">
                        <i class="fa-solid fa-location-dot"></i>
                    </a>
                    <a href="#" class="footer-links">ABC Street, Kurunegala</a>
                </li>
                <li>
                    <a href="#" class="icons-link">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                    <a href="#" class="footer-links">0716260728</a>
                </li>
                <li>
                    <a href="#" class="icons-link">
                        <i class="fa-solid fa-envelope"></i>
                    </a>
                    <a href="#" class="footer-links">Info@emaple.com</a>
                </li>
             </ul>
          </div>
          </div>
          <div class="line">
            <h4 class="Copyright">
                &copy;Copyright Bakery All Right Reserved
            </h4>
          </div>
        </div>
     </footer>

    <script src="script.js"></script>
</body>
</html>