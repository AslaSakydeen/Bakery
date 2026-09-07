
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Bakery</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
</head>
<body>

<header>
    <nav class="navbar container">
        <div class="logo">
            <a href="index.html" class="logo-txt">Bake<span>ry.</span></a>
        </div>
        <ul class="navlist">
            <li><a href="index.php" class="navlinks">Home</a></li>
            <li><a href="aboutUs.php" class="navlinks active">About</a></li>
            <li><a href="menu.php" class="navlinks">Menu</a></li>
            <li><a href="contactUs.php" class="navlinks">Contact</a></li>
        </ul>
    </nav>
</header>

<main>
    <section class="other-section container">
        <div class="row-container">
            <div class="image-container">
                <img src="images/cake3.png" class="pic" alt="Bakery">
            </div>
            <div class="content">
                <h2>About Our Bakery</h2>
                <p>At Bakery we create fresh, delicious baked goods made with love and quality ingredients. 
                From cakes to pastries, every item is carefully prepared to bring joy to your everyday moments and special celebrations.</p>
               <strong>Our Mission</strong>
                <p>Our mission is to bake fresh, high-quality treats with care and creativity, using the best ingredients to deliver great taste,
                     beautiful designs, and happiness in every bite while ensuring complete customer satisfaction.</p>
            </div>
        </div>
    </section>
  

    <section class="other-section container">
        <div class="column-container">
            <h2>Why Choose Us</h2>
            <p class="centered-txt">Fresh ingredients, experienced bakers, custom designs, and exceptional taste.</p>
            <div class="card-container">
                <div class="card" data-type="category">
                    <img src="images/fresh.jpg">
                    <h3>Freshly Baked</h3>
                </div>
                <div class="card" data-type="category">
                    <img src="images/prem.jpg">
                    <h3>Premium Quality</h3>
                </div>
                <div class="card" data-type="category">
                    <img src="images/price.jpg">
                    <h3>Affordable Price</h3>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- <footer>
    <div class="column-container container">
        <p class="centered-txt">© 2025 Bakery. All Rights Reserved</p>
    </div>
</footer> -->

</body>
</html>
<?php include 'footer.php'; ?>