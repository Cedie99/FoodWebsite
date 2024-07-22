<?php
session_start();

// Function to destroy session and logout
function logout() {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    // Output JavaScript to clear cart data from local storage
    echo "<script>
            localStorage.removeItem('cartItems'); // Clear cart items from localStorage
            document.getElementById('cart-count').textContent = '0'; // Reset cart count display
          </script>";

    // Redirect to index.php after logout
    header("Location: index.php");
    exit();
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $username = $_SESSION['username'];
    $userId = $_SESSION['user_id']; // Get user ID

    // Handle logout if logout parameter is passed in URL
    if (isset($_GET['logout'])) {
        logout();
    }
} else {
    $username = null;
    $userId = null;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Our Menu</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="icon" href="pictures/logo-removebg-preview.png" type="image/x-icon">
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

#cart-count {
    margin-right: 60px;
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
    #cart-count {
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

    #cart-count {
        margin: auto;
    }


}


@media (max-width: 575px) {

}

.search-container {
    text-align: right;
    position: absolute;
    top: 90px; /* Adjust as needed */
    right: 50px; /* Adjust as needed */
    width: 289px;
    
}

#search-bar {
    width: 100%;
    max-width: 600px;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 5px;
    color: black;
    
}


.menu {
    width: 100%;
    padding: 70px 0;
    margin-top: 50px;
    margin-bottom: -150px;
}

#foodweoffer{
    font-size: 55px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    

}


#maindish {
    font-size: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 30px;
}

.menu h1 span {
    color: #ffba08;
    margin-left: 15px;
    font-family: mv boli;
}

.menu h1 span::after {
    content: '';
    width: 100%;
    height: 2px;
    background: #ffba08;
    display: block;
    position: relative;
    bottom: 15px;
}

/* Default layout with 4 cards per row on laptop screens */
.menu .menu_box {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    padding: 30px;
}

.menu .menu_box .menu_card {
    width: calc(25% - 40px); /* Adjust based on your layout */
    margin: 20px;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    text-align: center;
    padding: 20px;
}

.menu .menu_box .menu_card:hover {
    transform: translateY(-5px);
}

.menu .menu_box .menu_card .menu_image {
    height: 300px; /* Set a fixed height for the image container */
    overflow: hidden;
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
}

.menu .menu_box .menu_card .menu_image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease; /* Added for image scaling */
    padding: 4px;
    cursor: pointer;
}

.menu .menu_box .menu_card .menu_image:hover img {
    transform: scale(1.1); /* Adjust scale factor as needed */
}

.menu .menu_box .menu_card .menu_info {
    padding: 5px;
    min-height: 100px; /* Set a minimum height for the menu info */
    
}

.menu .menu_box .menu_card .menu_info h2 {
    font-size: 18px;
    color: black;
    margin-bottom: 10px;
}

.menu .menu_box .menu_card .menu_info p {
    font-size: 12px; /* Adjust font size for consistency */
    line-height: 1.5;
    color: black;
    margin: 0;
}

.menu .menu_box .menu_card .menu_info h3 {
    font-size: 16px; /* Adjust font size for consistency */
    margin-top: 10px;
    color: black;
}

.menu .menu_box .menu_card button {
    width: 80%;
    margin: auto;
    margin-top: 10px;
    padding: 12px;
    background-color: #ffba08;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.menu .menu_box .menu_card button:hover {
    background-color: #c48c00;
}

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    cursor: pointer;
}

.modal img {
    max-width: 50%;
    max-height: 90%;
    border-radius: 16px;
}

/* Media query for screens smaller than laptop size */
@media (max-width: 1200px) {
    .menu .menu_box .menu_card {
        width: calc(33.33% - 40px); /* 3 cards per row */
    }
}

@media (max-width: 992px) {
    .menu .menu_box .menu_card {
        width: calc(50% - 40px); /* 2 cards per row */
    }
}

@media (max-width: 768px) {
    .menu .menu_box .menu_card {
        width: calc(100% - 40px); /* 1 card per row */
    }

    #foodweoffer {
        font-size: 40px;
    }

    .logo {
        font-size: 1.5rem;
    }

    .cart_container {
        width: 60%;
        max-width: 300px; /* Limit maximum width for larger screens */
        right: 0;
        left: 0;
        margin: 0 auto; /* Center horizontally */
        border-radius: 10px; /* Rounded corners */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Add shadow */
        height: 50vh; /* Limit height to 80% of viewport height */
        overflow-y: auto; /* Add vertical scrollbar */
    }

}
    
.add-to-cart-btn {
    background-color: #ffba08;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.add-to-cart-btn:hover {
    background-color: darkgreen;
}

.cart_container {
    position: fixed;
    width: 90%;
    max-width: 500px;
    bottom: 20px;
    right: 20px;
    opacity: 0;
    transition: opacity 0.2s, transform 0.2s;
    display: none;
    z-index: 1;
    border-radius: 10px;
    overflow: hidden;
    overflow-y: auto; /* add a vertical scrollbar */
    max-height: 500px; /* set a maximum height for the container */
    
}

.cart_container.active {
    display: block;
    opacity: 1;
    animation: popOut 0.5s ease forwards;
}

@keyframes popOut {
    0% { opacity: 0; transform: scale(0.8); }
    100% { opacity: 1; transform: scale(1); }
}

#cart {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
}

#cart h2 {
    font-size: 24px;
    margin-bottom: 15px;
    color: #333;
    text-align: center;
}

#cart p {
    font-weight: bold;
}

#cart-items {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

#cart-items li {
    padding: 10px 0;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    color: #555;
}
.cart-item-image img {
  width: 100px;
  height: 100px;
  border-radius: 5px;
  margin: 10px;
  object-fit: cover;
}



#cart-items li:last-child {
    border-bottom: none;
}

#cart-items li button {
    width: 60px;
    height: 30px;
    background-color: #ff4d4d;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.2s;
}

#cart-items li button:hover {
    background-color: #cc0000;
}

#cart p, #cart-total {
    color: #333;
    text-align: center;
    margin: 10px 0;
}

.shopCart {
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
    padding-bottom: 10px;
    border-bottom: 1px solid #eee;
    margin-bottom: 10px;
}

#close-cart-btn {
    cursor: pointer;
    position: absolute;
    top: -10px;
    right: -10px;
    color: #888;
    background-color: #fff;
    border-radius: 50%;
    border: 1px solid #ddd;
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.2s, background-color 0.2s;
}

#close-cart-btn:hover {
    color: #555;
    background-color: #f2f2f2;
}

.shopCart h2 {
    font-size: 20px;
    margin: 0;
}

.orderBtn {
    padding: 10px 15px;
    text-decoration: none;
    color: white;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    background-color: #ffba08;
    border: none;
    border-radius: 5px;
    transition: background-color 0.2s;
}

.orderBtn i {
    margin: 0 5px;
}

.orderBtn:hover {
    background-color: #c48c00;
}
.footer {
    display: flex;
    flex-flow: row wrap;
    padding: 30px 30px 20px 30px;
    color: #2f2f2f;
    background-color: black;
    border-top: 1px solid gray;
    margin-bottom: -100px;
    margin-top: 100px;
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

h3 {
    color: black;
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

</style>
<div class="background-overlay"></div>
<header>
<a href="index.php" class="logo">
        <img src="pictures/logo-removebg-preview.png" alt="The Big Mallem Logo" class="logo-img"> The Big Mallem Shawarma
    </a>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
    <li><a href="index.php" <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?>>Home</a></li>
    <li><a href="#Menu" <?php if (basename($_SERVER['PHP_SELF']) == 'menu.php') echo 'class="active"'; ?>>Menu</a></li>
    <li><a href="about.php" <?php if (basename($_SERVER['PHP_SELF']) == 'about.php') echo 'class="active"'; ?>>About</a></li> 
    <li><a href="account.php" <?php if (basename($_SERVER['PHP_SELF']) == 'order.php') echo 'class="active"'; ?>>Orders</a></li>
    <li><a href="order.php"><i class="fa-solid fa-cart-shopping"></i></a></li>
        <span id="cart-count">0</span>
        <?php if ($username): ?>
            <li class="menuWelcome"> <a href="profile.php">Welcome, <?php echo $username; ?>! </a><a href="?logout" class="logoutBtn">Logout</a></li>
        <?php else: ?>
            <li><a href="register.php"><li><a href="register.php" id="sl">Sign in  |  Register</a></li></a></li>
        <?php endif; ?>
        <!--<li><a href="#" onclick="tAndC()">Terms and Conditions</a></li>-->
    </ul>   
</header>
<div class="search-container">
    <input type="text" id="search-bar" placeholder="Search for available food...">
</div>



<div class="menu" id="Menu">
        <h1 id="foodweoffer">Food we <span>Offer</span></h1>
        <h2 id="maindish">Main Dish</h2>
        <div class="menu_box">
            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice8-removebg-preview.png" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Shawarma</h2>
                    <p>Thinly sliced cuts of marinated beef wrapped in a warm pita.
                    </p>
                    <h3>P79.00</h3>
                    
                   
                </div>
                <div><button class="add-to-cart-btn" data-name="Shawarma" data-price="79" data-image="spice8-removebg-preview.png">Add to Cart</button></div>
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice6-removebg-preview.png" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Kebab Rice</h2>
                    <p>A flavorful dish that combines tender, marinated kebab meat with fragrant rice and aromatic spices.
                    </p>
                    <h3>P119.00</h3>
                </div>
                <div><button class="add-to-cart-btn" data-name="Kebab Rice" data-price="119" data-image="spice6-removebg-preview.png">Add to Cart</button></div>
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice7-removebg-preview.png" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Shawarma Rice</h2>
                    <p>Shawarma with fragrant rice, creating a hearty and satisfying dish.
                    </p>
                    <h3>P99.00</h3>
                    
                </div>
                <div><button class="add-to-cart-btn" data-name="Shawarma Rice" data-price="99" data-image="spice7-removebg-preview.png">Add to Cart</button></div>
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice8-removebg-preview.png"" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Chicken Parmigiana</h2>
                    <p> Breaded chicken breast topped with marinara sauce and melted mozzarella cheese.
                    </p>
                    <h3>P469.00</h3>
                   
                </div>
               <div><button class="add-to-cart-btn" data-name="Chicken Parmigiana" data-price="469" data-image="foods/chic.jpg">Add to Cart</button></div> 
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice8-removebg-preview.png"" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Eggplant Parmesan</h2>
                    <p> Layers of breaded and fried eggplant slices with marinara sauce and melted cheese.
                    </p>
                    <h3>P319.00</h3>
                    
                </div>
                <div><button class="add-to-cart-btn" data-name="Eggplant Parmesan" data-price="319">Add to Cart</button></div>
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice8-removebg-preview.png"" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Tiramisu</h2>
                    <p>Layers of coffee-soaked ladyfingers, mascarpone cheese, and cocoa powder.
                    </p>
                    <h3>P199.00</h3>
                    
                </div>
                <div><button class="add-to-cart-btn" data-name="Tiramisu" data-price="199">Add to Cart</button></div>
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice8-removebg-preview.png"" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Cannoli</h2>
                    <p>Crispy pastry shells filled with sweetened ricotta cheese and chocolate chips.
                    </p>
                    <h3>P199.00</h3>
                    
                </div>
                <div><button class="add-to-cart-btn" data-name="Cannoli" data-price="199">Add to Cart</button></div>
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice8-removebg-preview.png"" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Pollo alla Cacciatora</h2>
                    <p>Chicken pieces are braised in a savory tomato sauce with onions, garlic, bell peppers, and herbs.
                    </p>
                    <h3>P199.00</h3>                   
                </div>
                <div><button class="add-to-cart-btn" data-name="Pollo alla Cacciatora" data-price="199">Add to Cart</button></div>
            </div>
        </div>  
    </div>



    <div class="menu" id="Menu">
        <h2 id="maindish">Spices</h2>
        <div class="menu_box">
            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice2-removebg-preview.png" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Chicken Masala</h2>
                    <p> Seasoning mix for chicken in yogurt sauce
                    </p>
                    <h3>P100.00</h3>
                    
                   
                </div>
                <div><button class="add-to-cart-btn"  data-name="Chicken Masala" data-price="100" data-image="spice2-removebg-preview.png">Add to Cart</button></div>
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice3-removebg-preview.png"alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Special Bombay Biryani</h2>
                    <p> Seasoning mix for meat and potato layered pilaf
                    </p>
                    <h3>P150.00</h3>
                    
                </div>
                <div><button class="add-to-cart-btn"  data-name="Special Bombay Biryani" data-price="150" data-image="spice3-removebg-preview.png">Add to Cart</button></div>
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice4-removebg-preview.png" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Malay Chicken Biryani</h2>
                    <p>Seasoning mix for chicken layered pilaf
                    </p>
                    <h3>P100.00</h3>
                   
                </div>
               <div><button class="add-to-cart-btn"  data-name="Malay Chicken Biryani" data-price="100" data-image="spice4-removebg-preview.png">Add to Cart</button></div> 
            </div>

            <div class="menu_card">

                <div class="menu_image">
                    <img src="foods/spice5-removebg-preview.png" alt="" onclick="showFullImage(this)">
                </div>

                <div class="menu_info">
                    <h2>Biryani</h2>
                    <p>Seasoning mix for tasty meat layered pilaf
                    </p>
                    <h3>P99.00</h3>
                    
                </div>
                <div><button class="add-to-cart-btn" data-name="Biryani" data-price="99">Add to Cart</button></div>
            </div>
            
            <div class="cart_container" id="cart-container">
            <div id="cart">
                <div class="shopCart" id="Cart">
                    <div class="cart-header">
                        <h2>Shopping Cart</h2>
                        <i class="fa-regular fa-circle-xmark" id="close-cart-btn"></i>
                        <i class="fas fa-cart-arrow-down"></i>
                    </div>
                </div>
                <ul id="cart-items">
                
                </ul>
                <p>Total: P<span id="cart-total">0</span></p>
                <button class="orderBtn" onclick="order()">Order Now <i class="fas fa-arrow-right"></i></button>
            </div>
        </div>  
        </div>  

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


    <script src="index.js"></script>
    
    <script>
    // Function to get user ID from PHP
    const userId = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;

    // Generate key for localStorage using user ID
    function getCartKey() {
        return `cartItems_${userId}`;
    }

    let cartItems = [];
    let cartTotal = 0;
    let cartCount = 0;

    // Get elements
    const cartCountElement = document.getElementById('cart-count');
    const cartList = document.getElementById('cart-items');
    const totalDisplay = document.getElementById('cart-total');
    const cartContainer = document.getElementById('cart-container');
    const closeCartButton = document.getElementById('close-cart-btn');
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn');

    // Function to check if user is logged in
    function isLoggedIn() {
        return userId !== null;
    }

    // Load cart from local storage
    function loadCart() {
        if (isLoggedIn()) {
            const storedCart = JSON.parse(localStorage.getItem(getCartKey()));
            if (storedCart) {
                cartItems = storedCart;
                cartTotal = cartItems.reduce((total, item) => total + item.price * item.quantity, 0);
                cartCount = cartItems.reduce((count, item) => count + item.quantity, 0);
            }
            updateCartDisplay();
        } else {
            // Clear cart data when not logged in
            cartItems = [];
            cartTotal = 0;
            cartCount = 0;
            updateCartDisplay();
        }
    }

    // Update cart display
    function updateCartDisplay() {
        cartCountElement.textContent = cartCount;
        totalDisplay.textContent = cartTotal.toFixed(2);
        displayCart();
    }

    function addToCart(event) {
        if (!isLoggedIn()) {
            window.location.href="login.php";
            return;
        }

        const button = event.target;
        const itemName = button.dataset.name;
        const itemPrice = parseFloat(button.dataset.price);
        const itemImage = button.dataset.image;

        const existingItem = cartItems.find(item => item.name === itemName);
        if (existingItem) {
            existingItem.quantity++;
        } else {
            cartItems.push({ name: itemName, price: itemPrice, quantity: 1, image: itemImage });
        }

        cartTotal += itemPrice;
        cartCount++;
        updateCartDisplay();

        localStorage.setItem(getCartKey(), JSON.stringify(cartItems));

        cartContainer.classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadCart();

        const orderLink = document.querySelector('a[href="order.php"]');
        orderLink.addEventListener('click', function(event) {
            if (cartItems.length === 0) {
                event.preventDefault();
                alert('Your cart is empty. Please add items to the cart before placing an order.');
            } else {
                order();
            }
        });

        const searchBar = document.getElementById('search-bar');
        searchBar.addEventListener('input', filterMenuItems);

        function filterMenuItems() {
            const query = searchBar.value.toLowerCase();
            const menuItems = document.querySelectorAll('.menu_card');

            menuItems.forEach(item => {
                const itemName = item.querySelector('.menu_info h2').textContent.toLowerCase();
                item.style.display = itemName.includes(query) ? 'block' : 'none';
            });
        }
    });

    // Function to display cart items
    function displayCart() {
        cartList.innerHTML = '';
        cartItems.forEach((item, index) => {
            const li = document.createElement('li');
            const imgContainer = document.createElement('div');
            imgContainer.classList.add('cart-item-image');
            const img = document.createElement('img');
            img.src = `foods/${item.image}`;
            img.alt = item.name;
            img.onclick = function() { showFullImage(this) };
            imgContainer.appendChild(img);
            li.appendChild(imgContainer);

            const itemInfo = document.createElement('div');
            itemInfo.classList.add('cart-item-info');
            itemInfo.innerHTML = `
                <h3>${item.name}</h3>
                <p>x${item.quantity} (${item.price.toFixed(2)})</p>
            `;
            li.appendChild(itemInfo);

            const deleteButton = document.createElement('button');
            deleteButton.textContent = 'Delete';
            deleteButton.classList.add('delete-btn');
            deleteButton.addEventListener('click', () => removeItem(index));
            li.appendChild(deleteButton);

            cartList.appendChild(li);
        });
    }

    // Function to remove item from cart
    function removeItem(index) {
        if (cartItems[index].quantity > 1) {
            cartItems[index].quantity--;
            cartTotal -= cartItems[index].price;
            cartCount--;
        } else {
            cartTotal -= cartItems[index].price;
            cartCount--;
            cartItems.splice(index, 1);
        }

        updateCartDisplay();
        localStorage.setItem(getCartKey(), JSON.stringify(cartItems));
    }

    // Add click event listener to each "Add to Cart" button
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            addToCart(event);
        });
    });

    // Add click event listener to the close button
    closeCartButton.addEventListener('click', () => {
        cartContainer.classList.remove('active');
    });

    // Function to handle the order process
    function order() {
        if (!isLoggedIn()) {
            alert('You need to be logged in to place an order.');
            return;
        }

        let url = "order.php?";
        cartItems.forEach((item, index) => {
            url += `item${index + 1}=${encodeURIComponent(item.name)}&quantity${index + 1}=${item.quantity}&`;
        });

        window.location.href = url.slice(0, -1);
    }

    // Full image view function
    function showFullImage(img) {
        var modal = document.createElement('div');
        modal.classList.add('modal');

        var fullImage = document.createElement('img');
        fullImage.src = img.src;
        fullImage.alt = img.alt;

        modal.appendChild(fullImage);
        document.body.appendChild(modal);

        modal.addEventListener('click', function() {
            modal.remove();
        });
    }
// Event listener for the order link
document.addEventListener('DOMContentLoaded', () => {
    loadCart();

    const orderLink = document.querySelector('a[href="order.php"]');
    orderLink.addEventListener('click', function(event) {
        if (cartItems.length === 0) {
            event.preventDefault();
            alert('Your cart is empty. Please add items to the cart before placing an order.');
        } else {
            order();
        }
    });

    const searchBar = document.getElementById('search-bar');
    searchBar.addEventListener('input', filterMenuItems);

    function filterMenuItems() {
        const query = searchBar.value.toLowerCase();
        const menuItems = document.querySelectorAll('.menu_card');

        menuItems.forEach(item => {
            const itemName = item.querySelector('.menu_info h2').textContent.toLowerCase();
            item.style.display = itemName.includes(query) ? 'block' : 'none';
        });
    }
});
    </script>
</body>
</html>
