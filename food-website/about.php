<?php
session_start();
include('db.php');

// Function to destroy session and logout
function logout() {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.php"); // Redirect to index.php after logout
    exit();
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];

    // Handle logout if logout parameter is passed in URL
    if (isset($_GET['logout'])) {
        logout();
    }
} else {
    $username = null;
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="icon" href="pictures/logo-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
    <style>
               *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: sans-serif;
    color: white;
}

:root {
    --main-color: #ffba08;
    --text-color:#fff;
    --bg-color: black;
    --big-font: 5rem;
    --h2-font: 2.25rem;
    --p-font: 0.9rem;
}

*::selection {
    background: var(--main-color);
    color: #fff;
}

body {
    color: var(--text-color);
    background-color: var(--bg-color);
}

html {
    scroll-behavior: smooth;
}

header {
    position: fixed;
    top: 0;
    right: 0;
    width: 100%;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 30px 170px;
    background-color: black;
    height: 80px;
    align-items: center;
    justify-content: space-between;
    padding: 30px 170px;
    background-color: black;
    height: 80px;
}

header .logo {
    color: var(--main-color);
    font-weight: 600;
    font-size: 2rem;
    text-decoration: none;
    display: flex;
    align-items: center;
}

header .logo .logo-img {
    width: 40px; /* Adjust size as needed */
    height: auto;
    margin-right: 10px;
}

header img {
    background-color: white;
    width: 30px;
    height: 30px;
    border-radius: 50px;
    font-size: 2rem;
    margin-left: 10px;
}

.userlogo:hover {
    background-color: #ffba08;
}

#menu-icon {
    font-size: 2rem;
    cursor: pointer;
    display: none;
}

.navbar {
    display: flex;
    align-items: center;
    list-style: none;
}

.welcome {
    color: #ffba08;
    margin-left: 40px;
}

.navbar li a.active {
    color: #ffba08;
    border-bottom: 2px solid #ffba08;
    font-weight: bold;
}

.navbar a {
    color: var(--text-color);
    font-size: 1.1rem;
    padding: 10px 20px;
    font-weight: 500;
    text-decoration: none;
}

.navbar a:hover {
    color: var(--main-color);
    transition: .4s;
}
section {
    padding: 70px 17%;
}

.about {
    margin-top: 100px;
    display: grid;
    grid-template-columns: repeat(2, 2fr);
    grid-gap: 1.5rem;
    align-items: center;
}


.about-img img {
    max-width: 100%;
    width: 600px;
}

.about-text span {
    color: var(--main-color);
    font-weight: 600;

}

.about-text h2 {
    font-size: var(--h2-font);
}

.about-text p {
  margin: 0.8rem 0 1.8rem;
  line-height: 1.7;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background: var(--main-color);
    color: #fff;
    border-radius: 0.5rem;
    text-decoration: none;
}

.btn:hover {
    transform: scale(1.2) translateX(10px);
    transition: .4s;
}

@media (max-width: 1560px) {
    header {
        padding: 15px 40px;
    }
    :root {
        --big-font: 3.5rem;
        --h2-font: 2rem;
    }
}

@media (max-width: 1140px) {
    section {
       padding: 50px 8%;
    }

    #menu-icon {
        display: initial;
        color: var(--text-color);
    }

    header .navbar {
        position: absolute;
        top: -500px;
        left: 0;
        right: 0;
        display: flex;
        flex-direction: column;
        text-align: center;
        background: black;
        transition: .3s;
    }

    header .navbar.active {
        top: 70px;

    }

    .navbar a {
        padding: 1.5rem;
        display: block;
    }
}

@media (max-width: 720px){
    header {
        padding: 10px 16px;

    }

    .home {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .about {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .about-img {
        order: 2;
    }

    section {
        padding: 100px 7%;
    }

    .welcome {
      margin: auto;
    }

    .shoplocation {
    grid-template-columns: 1fr;
    padding: 0 10px; /* Adjust padding for smaller screens */
  }

  iframe {
    height: 300px; /* Adjust height for smaller screens */
    margin: 20px 0;
  }

  .iframe-container {
    margin-bottom: 100px;
  }

  .location-text h1, .location-text p {
    text-align: center;
  }
}


@media (max-width: 575px) {

}

.footer {
    display: flex;
    flex-flow: row wrap;
    padding: 30px 30px 20px 30px;
    color: #2f2f2f;
    background-color: black;
    border-top: 1px solid gray;
  }
  
  .footer > * {
    flex:  1 100%;
  }
  
  .footer__addr {
    margin-right: 1.25em;
    margin-bottom: 2em;
  }
  
  .footer__logo {
    font-weight: 400;
    text-transform: uppercase;
    font-size: 1rem;
    color: #ffba08;
  }
  
  .footer__addr h2 {
    margin-top: 1.3em;
    font-size: 15px;
    font-weight: 400;
    padding: 2px;
  }
  
  .nav__title {
    font-weight: 400;
    font-size: 15px;
    color: #ffba08;
  }
  
  .footer address {
    font-style: normal;
    color: #999;
    font-size: 14px;
  }
  
  .footer__btn {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 36px;
    max-width: max-content;
    background-color: #ffba08;
    border-radius: 100px;
    color: #2f2f2f;
    line-height: 0;
    margin: 0.6em 0;
    font-size: 1rem;
    padding: 0 1.3em;
  }

  .footer__btn:hover {
    background-color: #c48c00;
  }
  
  .footer ul {
    list-style: none;
    padding-left: 0;
  }
  
  .footer li {
    line-height: 2em;
  }
  
  .footer a {
    text-decoration: none;
  }
  
  .footer__nav {
    display: flex;
    flex-flow: row wrap;
  }
  
  .footer__nav > * {
    flex: 1 50%;
    margin-right: 1.25em;
  }
  
  .nav__ul a {
    color: #999;
  }
  
  .nav__ul--extra {
    column-count: 2;
    column-gap: 1.25em;
  }
  
  .legal {
    display: flex;
    flex-wrap: wrap;
    color: #999;
  }
    
  .legal__links {
    display: flex;
    align-items: center;
  }
  
  .heart {
    color: #2f2f2f;
  }
  
  @media screen and (min-width: 24.375em) {
    .legal .legal__links {
      margin-left: auto;
    }
  }
  
  @media screen and (min-width: 40.375em) {
    .footer__nav > * {
      flex: 1;
    }
    
    .nav__item--extra {
      flex-grow: 2;
    }
    
    .footer__addr {
      flex: 1 0px;
    }
    
    .footer__nav {
      flex: 2 0px;
    }
  }

  /* Initial hidden state */
.animate {
  opacity: 0;
  transform: translateY(20px);
  transition: opacity 0.6s ease-out, transform 0.6s ease-out;
}

/* When element is in view */
.animate.show {
  opacity: 1;
  transform: translateY(0);
}

.background-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: linear-gradient(rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0.75)),url('foods/bg1.jpg'); 
    background-size: cover;
    background-position: center;
    opacity: 0.7; 
    z-index: -1;
  
}


.shoplocation {
    width: 100%;
    min-height: 90vh;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 1.5rem;
    align-items: start;
}

.location-img img {
    max-width: 100%;
    width: 600px;
    height: auto;
}

.location-text {
  display: flex;
  flex-direction: column;
  justify-content: flex-start; /* Align text to the top */
}

.location-text h1 {
    font-size: 3rem;
    color: var(--main-color);
    
}

.location-text h2{
    font-size: 1.2rem;
    margin: 1rem 0 2rem;
    font-weight: 400;
}

.location-text h1 span {
    color: #ffba08;
    font-family: mv boli;
    text-decoration: underline 3px;
    text-underline-offset: 5px; /* Add margin to the underline */
}

.iframe-container {
  width: 100%;
  max-width: 800px; /* Adjust as needed */
  margin: 0 auto;
  padding: 10px;
  background: black; /* Outer box background color */
  border: 3px solid white;
  border-radius: 10px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
}

.iframe-container iframe {
  width: 100%;
  height: 450px;
  border: 0;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}




@media (max-width: 1560px) {
    header {
        padding: 15px 40px;
    }
    :root {
        --big-font: 3.5rem;
        --h2-font: 2rem;
    }
}

@media (max-width: 1140px) {
    section {
       padding: 50px 8%;
    }

    #menu-icon {
        display: initial;
        color: var(--text-color);
    }

    header .navbar {
        position: absolute;
        top: -500px;
        left: 0;
        right: 0;
        display: flex;
        flex-direction: column;
        text-align: center;
        background: black;
        transition: .3s;
    }

    header .navbar.active {
        top: 70px;

    }

    .navbar a {
        padding: 1.5rem;
        display: block;
    }

    .userlogo {
      margin: auto;
    }

    .welcome {
      margin: auto;
    }

    .logoutBtn {
      margin: auto;
    }
}

@media (max-width: 720px){
    header {
        padding: 10px 16px;

    }

    .home {
        grid-template-columns: 1fr;
        text-align: center;
    }

    section {
    padding: 70px 17%;
    margin-top: 100px;
    }

    .shoplocation {
      grid-template-columns: 1fr;
        text-align: center;
    }
    
    header img {
      margin-left: auto;
    }

    .welcome {
      margin: auto;
    }

 


    

}


@media (max-width: 575px) {

}

    </style>
<div class="background-overlay"></div>
<header>
<a href="index.php" class="logo">
        <img src="pictures/logo-removebg-preview.png" alt="The Big Mallem Logo" class="logo-img"> The Big Mallem Shawarma
    </a>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
    <li class="navLinks"><a href="index.php" <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?>>Home</a></li>
    <li class="navLinks"><a href="menu.php" <?php if (basename($_SERVER['PHP_SELF']) == 'menu.php') echo 'class="active"'; ?>>Menu</a></li>
    <li class="navLinks"><a href="#About" <?php if (basename($_SERVER['PHP_SELF']) == 'about.php') echo 'class="active"'; ?>>About</a></li>
        <?php if ($username): ?>
            <li class="welcome"> <a href="profile.php">Welcome, <?php echo $username; ?>! </a><a href="?logout" class="logoutBtn">Logout</a></li>
        <?php else: ?>
            <li><a href="register.php"><li><a href="register.php" id="sl">Sign up  |  Register</a></li></a></li>
        <?php endif; ?>
        <!--<li><a href="#" onclick="tAndC()">Terms and Conditions</a></li>-->
    </ul>
</header>


<section class="about animate" id="About">

<div class="about-img animate">
    <img src="foods/bg1.jpg" alt="">
</div>

<div class="about-text animate">
  <span>Our Story</span>
    <h2>Why Order with Us?</h2>
    <p>Welcome to The Big Mallem Shawarma and Kebab, your gateway to the rich and vibrant culinary traditions of the Middle East. Our passion for authentic flavors and time-honored recipes drives us to bring you the most delectable dishes from this culturally diverse region.
        At The Big Mallem, we believe that food is not just sustenance but a journey through history, culture, and community. Each dish we serve is a testament to the centuries-old recipes passed down through generations, capturing the essence of Middle Eastern hospitality and warmth.</p>
    <a href="menu.php" class="btn">View Menu</a>
</div>

</section>

<section class="shoplocation animate" id="Home">
        <div class="location-text animate">
            <h1>Come <span>Visit</span> Us!</h1>
            <h2>We warmly invite you to experience the rich flavors of authentic Arabic cuisine at our restaurant, 
              located in the picturesque town of Pulong Buhangin, Santa Maria, Bulacan. Infront of Polytechnic University of the Philippines - Sta. Maria Campus,
              Near Nuestra Señora del Carmen Institute, Inc.</h2>
        </div>

        <div class="location-img animate">
          <div class="iframe-container animate">
          <p>
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3856.252661196266!2d120.99709610447694!3d14.867143752868786!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397a9f757ddff23%3A0x77c5a92003de7a9a!2sThe%20Big%20Mallem%20Shawarma!5e0!3m2!1sen!2sph!4v1720846853678!5m2!1sen!2sph" 
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </p>  
          </div>
        </div>
  </section>

<footer class="footer">
    <div class="footer__addr">
    <h1 class="footer__logo">The Big Mallem Shawarma and Kebab</h1>
        
    <h2>Location</h2>
    
    <address>
      Km. 38 Pulong Buhangin, Santa Maria Bulacan, Philippines<br>
      Infront of PUPSMB Campus
          
      <a class="footer__btn" href="mailto:Thebigmallemshawarma@outlook.com">Email Us</a>
    </address>
  </div>
  
  
  <ul class="footer__nav">
    <li class="nav__item">
      <h2 class="nav__title">Socials</h2>

      <ul class="nav__ul">
        <li>
          <a href="https://www.facebook.com/bigmallem">Facebook</a>
        </li>

        <li>
          <a href="https://www.tiktok.com/@thelovelysyrian?fbclid=IwZXh0bgNhZW0CMTAAAR10eFL-T_24up86dqUN6lav5NOFK5jYPtjLAKMyXI-gVoJ8VT1s0Mbq3OM_aem_Xks1Ki_WS__L8Rgf2IywFA">Tiktok</a>
        </li>
            
        <li>
          <a href="#">Something</a>
        </li>
      </ul>
    </li>
    
    <li class="nav__item nav__item--extra">
      <h2 class="nav__title">Technology</h2>
      
      <ul class="nav__ul nav__ul--extra">
        <li>
          <a href="#">Something</a>
        </li>
        
        <li>
          <a href="#">Something</a>
        </li>
        
        <li>
          <a href="#">Something</a>
        </li>
        
        <li>
          <a href="#">Something</a>
        </li>
        
        <li>
          <a href="#">Something</a>
        </li>
        
        <li>
          <a href="#">Something</a>
        </li>
      </ul>
    </li>
    
    <li class="nav__item">
      <h2 class="nav__title">Legal</h2>
      
      <ul class="nav__ul">
        <li>
          <a href="privacy.html">Privacy Policy</a>
        </li>
        
        <li>
          <a href="terms.html">Terms of Use</a>
        </li>
        
        <li>
          <a href="#">Sitemap</a>
        </li>
      </ul>
    </li>
  </ul>
  
  <div class="legal">
    <p>&copy; 2024 The Big Mallem Shawarma. All rights reserved.</p>
    
    <div class="legal__links">
      <span>Made with <span class="heart">♥</span> Love</span>
    </div>
  </div>
</footer>

</body>

<script>
  let menu = document.querySelector('#menu-icon');
let navbar = document.querySelector(".navbar");

menu.addEventListener("click", function(){
    navbar.classList.toggle("active");
});

  document.addEventListener("DOMContentLoaded", function() {
    const elements = document.querySelectorAll('.animate');
  
    const observer = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('show');
          observer.unobserve(entry.target); // Unobserve the element after animation
        }
      });
    }, {
      threshold: 0.1
    });
  
    elements.forEach(element => {
      observer.observe(element);
    });
  });
</script>
</html>