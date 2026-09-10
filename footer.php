<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* footer section */
footer{
    margin-bottom: 1rem;
    isolation: isolate;
    
}
.footer-links{
    font-size: .95rem;
    color: var(--gray-clr);
}
.footer-links:hover{
    color: var(--orange-clr);
}
.footer-list{
    margin-top: 2rem;
}
.footer-list li{
    margin-block: .5rem;
}
.balanced-txt{
    max-width: 300px;
}
footer .card-container{
    justify-content: space-between;
}
.line{
    width: 100%;
    height: .1rem;
    background-color: var(--light-gray);
    margin-block: 4rem;
}
.copyright{
    
   display: flex;
   justify-content: center;
    align-items: center;
}
@media screen and (max-width: 680px){
    .container{
        
        width: 90%;
    }
    .image-container{
        display: none;
    }
    h1{
        font-size: 2.8rem;
    }
    .nav-icons{
        display: none;
    }

    .hamburger{
        display: block;
    }

    .navlist{
        position: absolute;
        top: 0;
        left: 0;
        flex-direction: column;
        justify-content: center;
        background-color: var(--dawn-pink);
        height: 100vh;
        width: 60%;
        z-index: 5;
        transform: translate(-100%);
        transition: .5s ease-in-out;
    }
    .navlist-active{
        transform: translate(0%);
    }
}






    </style>
    <link rel="stylesheet" href="global.css">
</head>
<body>
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
            <p class="balanced-txt">yuuyyu!</p>
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

</body>
</html>