<?php
include 'db.php'; // Include your database connection file

$registration_message = "";
$recovery_phrase = ""; // Variable to store recovery phrase

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate if passwords match
    if ($password !== $confirm_password) {
        $registration_message = "Passwords do not match!";
    } elseif (!isPasswordStrong($password)) {
        $registration_message = "Password is too weak!";
    } else {
        // Generate a recovery phrase
        $recovery_phrase = bin2hex(random_bytes(8)); // Adjust the length as needed

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if username already exists
        $check_username = "SELECT * FROM users WHERE username='$username'";
        $result_username = $conn->query($check_username);

        if ($result_username->num_rows > 0) {
            $registration_message = "Username already exists!";
        } else {
            // Check if email already exists
            $check_email = "SELECT * FROM users WHERE email='$email'";
            $result_email = $conn->query($check_email);

            if ($result_email->num_rows > 0) {
                $registration_message = "Email already exists!";
            } else {
                // Prepare and bind SQL statement to insert data
                $stmt = $conn->prepare("INSERT INTO users (firstname, lastname, username, address, phoneNumber, email, password, recovery_phrase) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $firstname, $lastname, $username, $address, $phoneNumber, $email, $hashed_password, $recovery_phrase);

                // Execute the prepared statement
                if ($stmt->execute()) {
                    $registration_message = "Registration successful! Please take not of this recovery phrase for password reset purposes. Your recovery phrase is: " . htmlspecialchars($recovery_phrase);
                } else {
                    $registration_message = "Error: " . $stmt->error;
                }

                // Close statement
                $stmt->close();
            }
        }
    }

    // Close database connection
    $conn->close();
}

function isPasswordStrong($password) {
    $isStrong = true;
    if (strlen($password) < 8) {
        $isStrong = false;
    } elseif (!preg_match('/[A-Z]/', $password)) {
        $isStrong = false;
    } elseif (!preg_match('/[a-z]/', $password)) {
        $isStrong = false;
    } elseif (!preg_match('/[0-9]/', $password)) {
        $isStrong = false;
    } elseif (!preg_match('/[!@#\$%\^&\*]/', $password)) {
        $isStrong = false;
    }
    return $isStrong;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
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
section {
    padding: 70px 17%;
}

.home {
    width: 100%;
    min-height: 90vh;
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 1.5rem;
    align-items: center;
}

.home-img img {
    max-width: 100%;
    width: 600px;
    height: auto;
}

.home-text h1 {
    font-size: 2rem;
    color: var(--main-color);
    
}

.home-text h2{
    font-size: var(--h2-font);
    margin: 1rem 0 2rem;
    
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

.home-img img {
            animation: rotateImage 12s linear infinite;
        }

        @keyframes rotateImage {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        h1 {
            color: black;
        }
        #p {
    margin: 10px;
    color: black;
    line-height: 30px;
}

i {
    color: #ffba08;
}

input {
    color: black;
}

.container {
    display: flex;
    justify-content: center;  /* Center horizontally */
    height: 100vh;
    align-items: center;      /* Center vertically */
    padding: 20px;
    max-width: 100%;
    margin-top: 150px;
    margin-bottom: 150px;
}

.form {
    display: block;
    text-align: center;
    background-color: white;
    padding: 20px;
    margin: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 500px;
    border-radius: 12px;
    backdrop-filter: blur(10px);
    transition: transform 0.3s, box-shadow 0.3s;
}

.form:hover {
    
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

input {
    display: flex;
    width: calc(100% - 28px);
    padding: 14px;
    margin: 10px auto;
    font-size: 16px;
    border-radius: 8px;
    border: 1px solid #ddd;
    transition: border-color 0.3s, box-shadow 0.3s;
}

input:focus {
    border-color: #ffba08;
    box-shadow: 0 0 8px rgba(255, 186, 8, 0.3);
    outline: none;
}

button {
    background-color: #ffba08;
    padding: 12px 20px;
    margin: 10px;
    cursor: pointer;
    width: 50%;
    border: none;
    border-radius: 8px;
    color: white;
    font-size: 16px;
    transition: background-color 0.3s, transform 0.3s;
}

button:hover {
    background-color: #e3a813;
    transform: translateY(-2px);
}

button:active {
    transform: translateY(0);
}


.notification {
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    text-align: center;
    color: blue;
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

    section {
    padding: 70px 17%;
    margin-top: 100px;
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

  small {
    color: black;
  }

  #loginbtn {
    color: white;
  }

  #registerbtn{
    color: white;
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
    <li><a href="menu.php" <?php if (basename($_SERVER['PHP_SELF']) == 'menu.php') echo 'class="active"'; ?>>Menu</a></li>
    <li><a href="#About" <?php if (basename($_SERVER['PHP_SELF']) == 'about.php') echo 'class="active"'; ?>>About</a></li>
        
            <li><a href="register.php"><li><a href="register.php" id="sl">Sign in  |  Register</a></li></a></li>

        <!--<li><a href="#" onclick="tAndC()">Terms and Conditions</a></li>-->
    </ul>
</header>
<div class="container">
    <div class="form">
        <h1>Register</h1>
        <p id="p">Create an account to continue to order<br><i>The Big Mallem Shawarma</i></p>

        <?php if (!empty($registration_message)): ?>
            <div class="notification"><?php echo $registration_message; ?></div>
        <?php endif; ?>

        <form id="registerForm" method="POST" action="">
            <input type="text" name="firstname" id="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" id="lastname" placeholder="Last Name" required>
            <input type="text" name="username" id="username" placeholder="Username" required>
            <input type="text" name="address" id="address" placeholder="Address" required>
            <input type="tel" name="phoneNumber" id="phoneNumber" placeholder="Phone Number (09xxxxxxxxx)" pattern="(09)[0-9]{9}" title="Please enter a valid 11-digit phone number starting with 09" required>
            <input type="email" name="email" id="email" placeholder="Email" required>
            <input type="password" name="password" id="password" placeholder="Password" required>
            <div id="password-strength"></div>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
            <button id="registerbtn" type="submit">Register</button>
        </form>
        <p id="p">Or</p>
        <button id="loginbtn" onclick="location.href='login.php';">Login</button>
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
    document.getElementById("registerForm").onsubmit = function() {
        var phoneNumber = document.getElementById("phoneNumber").value.trim();
        
        // Check if the phone number starts with "09" and has 11 digits
        if (!phoneNumber.match(/^(09)[0-9]{9}$/)) {
            alert("Please enter a valid 11-digit phone number starting with 09.");
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    };


    
    document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('password');
    const confirm_password = document.getElementById('confirm_password');
    const password_strength = document.getElementById('password-strength');
    const registerForm = document.getElementById('registerForm');
    const registerBtn = document.getElementById('registerbtn');

    password.addEventListener('input', function() {
        const strength = checkPasswordStrength(password.value);
        password_strength.textContent = strength.message;
        password_strength.style.color = strength.color;
        registerBtn.disabled = strength.isWeak;
    });

    confirm_password.addEventListener('input', function() {
        if (password.value !== confirm_password.value) {
            confirm_password.setCustomValidity("Passwords do not match!");
        } else {
            confirm_password.setCustomValidity("");
        }
    });

    function checkPasswordStrength(password) {
        let strength = {message: '', color: 'red', isWeak: true};
        if (password.length < 8) {
            strength.message = 'Password is too short!';
        } else if (!/[A-Z]/.test(password)) {
            strength.message = 'Password must contain at least one uppercase letter!';
        } else if (!/[a-z]/.test(password)) {
            strength.message = 'Password must contain at least one lowercase letter!';
        } else if (!/[0-9]/.test(password)) {
            strength.message = 'Password must contain at least one number!';
        } else if (!/[!@#\$%\^&\*]/.test(password)) {
            strength.message = 'Password must contain at least one special character!';
        } else {
            strength.message = 'Password is strong!';
            strength.color = 'green';
            strength.isWeak = false;
        }
        return strength;
    }
});
    

    
</script>

</script>

</script>
</body>
</html>
