<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

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


// Retrieve user information from session
$user_id = $_SESSION['user_id'];

// Fetch user orders with order items
$sql = "SELECT o.id, o.total_price, o.firstname, o.lastname, o.email, o.address, o.phoneNumber, o.status, u.username, o.payment_method, o.comments,
        GROUP_CONCAT(oi.product_name, ' ', oi.quantity, 'x' SEPARATOR '<br>') AS order_items
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        WHERE o.user_id = ? 
        GROUP BY o.id 
        ORDER BY o.id DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();


// Update order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id']) && isset($_POST['status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $order_id);
    if ($stmt->execute()) {
        $_SESSION['notification'] = "Order status updated successfully!";
        header("Location: account.php"); // Redirect to clear POST data
        exit();
    } else {
        $notification = "Failed to update order status.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="icon" href="pictures/logo-removebg-preview.png" type="image/x-icon">
</head>
<body>
    <style>
  * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: sans-serif;
            color: white;
        }

        :root {
            --main-color: #ffba08;
            --text-color: #fff;
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
        }

        header img {
            background-color: white;
            width: 30px;
            height: 30px;
            border-radius: 50px;
            font-size: 2rem;
            margin-left: 10px;
        }

        header img:hover {
            background-color: #ffba08;
        }

        #menu-icon {
            font-size: 2rem;
            cursor: pointer;
            display: none;
        }

        .logo {
            color: var(--main-color);
            font-weight: 600;
            font-size: 2rem;
            text-decoration: none;
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

        .container {
            background-color: #fff;
            padding: 40px 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1200px;
            width: 100%;
            margin: 100px auto;
            margin-top: 200px;
            margin-bottom: 200px;
        }

        #myorderstxt {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            overflow-x: auto;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            color: black;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        button {
            background-color: #ffba08;
            border: none;
            padding: 10px;
            border-radius: 10px;
            color: white;
            width: 100px;
            cursor: pointer;
        }

        button:hover {
            background-color: #c48c00;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }

            th, td {
                display: block;
                width: 100%;
            }

            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            td {
                position: relative;
                padding-left: 50%;
                text-align: right;
            }

            td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: calc(50% - 20px);
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
            }
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

        @media (max-width: 720px) {
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
    </style>
    <header>
    <a href="#Home" class="logo">The Big Mallem</a>
    <div class="bx bx-menu" id="menu-icon"></div>
    <ul class="navbar">
    <li class="navLinks"><a href="#Home" <?php if (basename($_SERVER['PHP_SELF']) == 'index.php') echo 'class="active"'; ?>>Home</a></li>
    <li class="navLinks"><a href="menu.php" <?php if (basename($_SERVER['PHP_SELF']) == 'menu.php') echo 'class="active"'; ?>>Menu</a></li>
    <li class="navLinks"><a href="about.php" <?php if (basename($_SERVER['PHP_SELF']) == 'about.php') echo 'class="active"'; ?>>About</a></li>
        <?php if ($username): ?>
            <li class="welcome"> <a href="profile.php">Welcome, <?php echo $username; ?>!</a> <a href="?logout" class="logoutBtn">Logout</a></li>
        <?php else: ?>
            <li><a href="register.php"><img src="foods/icons8-male-user-48.png" alt=""></a></li>
        <?php endif; ?>
        <!--<li><a href="#" onclick="tAndC()">Terms and Conditions</a></li>-->
    </ul>
</header>


    <div class="container">
        <h1 id="myorderstxt">My Orders</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Payment Method</th>
                    <th>Comments</th>
                    <th>Order Items</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Action</th>
                    
                    
                </tr>
            </thead>
            <tbody>
            <?php while ($order = $result->fetch_assoc()) { ?>
                <tr>
                    <td data-label="Order ID"><?php echo $order['id']; ?></td>
                    <td data-label="Payment Method"><?php echo $order['payment_method']; ?></td>
                    <td data-label="Comments"><?php echo $order['comments']; ?></td>
                    <td data-label="Order Items"><?php echo $order['order_items']; ?></td>
                    <td data-label="Total Price"><?php echo $order['total_price']; ?></td>
                    <td data-label="Status"><?php echo $order['status']; ?></td>
                    <td data-label="Action">
                        <?php if ($order['status'] != 'Completed') { ?>
                        <form action="account.php" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <input type="hidden" name="status" value="Completed">
                            <button type="submit" class="updateBtn">Mark as Completed</button>
                        </form>
                        <?php } else { ?>
                            Completed
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>            
        </table>
        <button type="button" onclick="backButton()">Back</button>
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

<script>
    function backButton() 
    {
        window.location.href = "menu.php";
    }
</script>
</html>
