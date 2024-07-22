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

// Initialize the update message
$update_message = "";

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch user details from the database
    $query = "SELECT * FROM users WHERE id='$user_id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $update_message = "User not found!";
        $user = []; // Initialize user as empty array
    }
} else {
    $update_message = "No user is logged in!";
    $user = []; // Initialize user as empty array
}

// Update user details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email']; // Add email to the form data

    // Check if username already exists (excluding current user)
    $check_username = "SELECT * FROM users WHERE username='$username' AND id != '$user_id'";
    $result_username = $conn->query($check_username);

    if ($result_username->num_rows > 0) {
        $update_message = "Username already exists!";
    } else {
        // Prepare and bind SQL statement to update data
        $stmt = $conn->prepare("UPDATE users SET firstname=?, lastname=?, username=?, address=?, phoneNumber=?, email=? WHERE id=?");
        $stmt->bind_param("ssssssi", $firstname, $lastname, $username, $address, $phoneNumber, $email, $user_id);

        // Execute the prepared statement
        if ($stmt->execute()) {
            // Update the orders table if necessary
            $update_orders = "UPDATE orders SET firstname=?, lastname=?, email=?, address=?, phoneNumber=? WHERE user_id=?";
            $stmt_orders = $conn->prepare($update_orders);
            $stmt_orders->bind_param("sssssi", $firstname, $lastname, $email, $address, $phoneNumber, $user_id);
            $stmt_orders->execute();

            $update_message = "Profile updated successfully!";
            // Fetch updated user details
            $query = "SELECT * FROM users WHERE id='$user_id'";
            $result = $conn->query($query);
            $user = $result->fetch_assoc();

            // Refresh session data
            $_SESSION['user_firstname'] = $firstname;
            $_SESSION['user_lastname'] = $lastname;
            $_SESSION['user_username'] = $username;
            $_SESSION['user_address'] = $address;
            $_SESSION['user_phoneNumber'] = $phoneNumber;
            $_SESSION['user_email'] = $email; // Add email to session
        } else {
            $update_message = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
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



        h1 {
            color: black;
        }
        #p {
            margin: 10px;
            color: black;
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

  .container h2 {
    color: black;
  }


  input[disabled] {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }

        .edit-btn {
            background-color: #ffba08;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin: 10px 0;
            transition: background-color 0.3s;
        }

        .edit-btn:hover {
            background-color: #e3a813;
        }



    </style>
<header>
<a href="index.php" class="logo">
        <img src="pictures/logo-removebg-preview.png" alt="The Big Mallem Logo" class="logo-img"> The Big Mallem Shawarma
    </a>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
    <li class="navLinks"><a href="index.php" <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?>>Home</a></li>
    <li class="navLinks"><a href="menu.php" <?php if (basename($_SERVER['PHP_SELF']) == 'menu.php') echo 'class="active"'; ?>>Menu</a></li>
    <li class="navLinks"><a href="about.php" <?php if (basename($_SERVER['PHP_SELF']) == 'about.php') echo 'class="active"'; ?>>About</a></li>
        <?php if ($username): ?>
            <li class="welcome"><a href="?logout" class="logoutBtn">Logout</a></li>
        <?php else: ?>
            <li><a href="register.php">Sign in | Register</a></li>
        <?php endif; ?>
        <!--<li><a href="#" onclick="tAndC()">Terms and Conditions</a></li>-->
    </ul>
</header>

<div class="container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form" id="form">
        <h2>Profile</h2>
        <?php if ($update_message != "") { ?>
            <div class="notification"><?php echo $update_message; ?></div>
        <?php } ?>
        <input type="text" name="firstname" placeholder="First Name" value="<?php echo $user['firstname']; ?>" required disabled>
        <input type="text" name="lastname" placeholder="Last Name" value="<?php echo $user['lastname']; ?>" required disabled>
        <input type="text" name="username" placeholder="Username" value="<?php echo $user['username']; ?>" required disabled>
        <input type="text" name="address" placeholder="Address" value="<?php echo $user['address']; ?>" required disabled>
        <input type="email" name="email" placeholder="Email" value="<?php echo $user['email']; ?>" required disabled>
        <input type="text" name="phoneNumber" placeholder="Contact Number" value="<?php echo $user['phoneNumber']; ?>" required disabled>
        <button type="submit" style="display: none;" id="submit-btn">Save</button> <!-- Hide the submit button initially -->
        <button type="button" class="edit-btn" id="edit-button">Edit Profile</button>
        <button type="button" onclick="history.back()">Back</button>
    </form>
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

</body>
<script src="index.js"></script>
<script>
    function back(){
        window.location.href = "index.php";
    }


    document.getElementById('edit-button').addEventListener('click', function() {
        // Enable all input fields
        var inputs = document.querySelectorAll('input');
        inputs.forEach(function(input) {
            input.removeAttribute('disabled');

        });

        // Show the submit button
        document.getElementById('submit-btn').style.display = 'inline-block';

        // Hide the edit button
        this.style.display = 'none';
        
    });
</script>
</html>