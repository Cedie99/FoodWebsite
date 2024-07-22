<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include the database connection
include 'db.php';

// Initialize variables
$notification = '';

// Retrieve user information from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Retrieve other user details from the database if needed
$sql = "SELECT firstname, lastname, email, address, phoneNumber FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_details = $result->fetch_assoc();

// Retrieve address history from the database
$sql = "SELECT DISTINCT address FROM orders WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$addresses = [];
while ($row = $result->fetch_assoc()) {
    $addresses[] = $row['address'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cartItems = json_decode($_POST['cartItems'], true);
    $totalPrice = $_POST['totalPrice'];
    $paymentMethod = $_POST['paymentMethod'];
    $comments = $_POST['comments'];
    $address = $_POST['address']; // Retrieve address from POST

    // Ensure address is not empty
    if (empty($address)) {
        $notification = "Please provide a delivery address.";
    } else {
        // Insert the order into the orders table
        $sql = "INSERT INTO orders (user_id, total_price, firstname, lastname, email, address, phoneNumber, payment_method, comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idsssssss", $user_id, $totalPrice, $user_details['firstname'], $user_details['lastname'], $user_details['email'], $address, $user_details['phoneNumber'], $paymentMethod, $comments);
        $stmt->execute();
        $orderId = $stmt->insert_id;

        // Insert the order items into the order_items table
        $sql = "INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        foreach ($cartItems as $item) {
            $stmt->bind_param("isid", $orderId, $item['name'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        $notification = "Order placed successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Form</title>
    <link rel="icon" href="pictures/logo-removebg-preview.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <style>
          body {
            background-color: black;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .title {
            text-align: center;
            padding: 20px;
        }

         h1 {
            font-size: 28px;
            color: #ffba08;
            margin: 0;
            text-align: center;
        }

        #user-info {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }

        #user-info h3 {
            margin-top: 0;
            font-size: 20px;
            color: #333;
        }

        #user-info p {
            margin: 8px 0;
            font-size: 16px;
        }

        #cart-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }

        #cart-container h3 {
            margin-top: 0;
            font-size: 20px;
            color: #ffba08;
        }

        #cart-items {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #cart-items li {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
            font-size: 16px;
            color: #ffba08;
            font-weight: bold;
        }

        #cart-total {
            font-weight: bold;
            font-size: 18px;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }

        .form-group select,
        .form-group input[type="text"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
        }

        button[type="submit"],
        button[type="button"] {
            background-color: #ffba08;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #c48c00;
        }

        .notification {
            background-color: #fff;
            color: #333;
            text-align: center;
            padding: 10px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 90%;
            width: 100%;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999;
            display: none; /* Initially hidden */
        }

        /* Media query for smaller screens */
        @media (max-width: 768px) {
            #user-info,
            #cart-container {
                margin: 10px;
                max-width: 100%;
            }
        }
        .delete-btn{
            float: right;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 5px;
            margin-left: 10px; /* Add some space between the text and button */
        }
        .add-btn {
            float: right;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            padding: 5px;
            margin-left: 10px; /* Add some space between the text and button */
            
        }

        .delete-btn:hover, .add-btn:hover{
            background-color: lightgray;
        }
    </style>

   
    
    <div id="user-info">
    <h1>PLACE ORDER</h1>
        <h3>Order for: </h3>
        <p>First Name: <?php echo htmlspecialchars($user_details['firstname']); ?></p>
        <p>Last Name: <?php echo htmlspecialchars($user_details['lastname']); ?></p>
        <p>Email: <?php echo htmlspecialchars($user_details['email']); ?></p>
        <p>Address: <?php echo htmlspecialchars($user_details['address']); ?></p>
        <p>Phone Number: <?php echo htmlspecialchars($user_details['phoneNumber']); ?></p>
    </div>

    <form id="orderForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" id="cartItemsInput" name="cartItems">
        <input type="hidden" id="totalPriceInput" name="totalPrice">
        <input type="hidden" id="address" name="address"> 
        <div id="cart-container">
            <h3>Cart Items:</h3>
            <ul id="cart-items">
                <!-- Cart items will be dynamically added here -->
            </ul>
            <p>Total: P<span id="cart-total">0.00</span></p>
    <div class="form-group">
        <label for="address-select">Delivery Address:</label>
        <select id="address-select" name="address-select">
            <option value="" disabled selected>Select address</option>
            <?php foreach ($addresses as $address): ?>
                <option value="<?php echo htmlspecialchars($address); ?>" <?php if ($address === $user_details['address']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($address); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <div style="margin-top: 10px;">
            <label for="address-manual">Or enter a new address:</label>
            <input type="text" id="address-manual" name="address-manual" placeholder="Enter your address">
        </div>
    </div>

        <div class="form-group">
            <label for="paymentMethod">Payment Method:</label>
            <select id="paymentMethod" name="paymentMethod" required>
                <option value="Cash on Delivery">Cash On Delivery</option>
                <option value="GCash">GCash</option>
            </select>
        </div>

        <div class="form-group">
            <label for="comments">Comments/Instructions:</label>
            <textarea id="comments" name="comments" rows="4" cols="50"></textarea>
        </div>

            <button type="button" onclick="placeOrder()">Place Order</button>
            <button type="button" onclick="backButton()">Back</button>
        </div>
    </form>

     <!-- Notification Message -->
     <?php if (!empty($notification)) : ?>
        <div id="notification" class="notification">
            <?php echo $notification; ?>
        </div>
    <?php endif; ?>
</body>
</html>

    

    <script>
        setTimeout(() => {
    document.getElementById('notification').style.display = 'none';
}, 5000); // Hide notification after 5 seconds

const userId = <?php echo isset($_SESSION['user_id']) ? json_encode($_SESSION['user_id']) : 'null'; ?>;

document.addEventListener('DOMContentLoaded', () => {
    displayCartItemsAndTotal(userId);
});

function displayCartItemsAndTotal(userId) {
    const cartItems = JSON.parse(localStorage.getItem(`cartItems_${userId}`)) || [];
    const cartList = document.getElementById('cart-items');
    const totalDisplay = document.getElementById('cart-total');

    cartList.innerHTML = '';

    cartItems.forEach((item, index) => {
        const li = document.createElement('li');
        li.innerHTML = `${item.name} x${item.quantity} 
            <button onclick="deleteCartItem(${index})" class="delete-btn"><i class="fas fa-minus"></i></button>
            <button onclick="addCartItem(${index})" class="add-btn"><i class="fas fa-plus"></i></button>`;
        cartList.appendChild(li);
    });

    let cartTotal = cartItems.reduce((accumulator, item) => accumulator + (item.price * item.quantity), 0);
    totalDisplay.textContent = cartTotal.toFixed(2);

    document.getElementById('cartItemsInput').value = JSON.stringify(cartItems);
    document.getElementById('totalPriceInput').value = cartTotal.toFixed(2);
}

function deleteCartItem(index) {
    const userId = <?php echo json_encode($user_id); ?>;
    let cartItems = JSON.parse(localStorage.getItem(`cartItems_${userId}`)) || [];
    
    if (cartItems[index].quantity > 1) {
        cartItems[index].quantity -= 1;
    } else {
        cartItems.splice(index, 1);
    }

    localStorage.setItem(`cartItems_${userId}`, JSON.stringify(cartItems));
    displayCartItemsAndTotal(userId);
}

function addCartItem(index) {
    const userId = <?php echo json_encode($user_id); ?>;
    let cartItems = JSON.parse(localStorage.getItem(`cartItems_${userId}`)) || [];
    
    if (cartItems[index]) {
        cartItems[index].quantity += 1;
    }

    localStorage.setItem(`cartItems_${userId}`, JSON.stringify(cartItems));
    displayCartItemsAndTotal(userId);
}

function placeOrder() {
    const userId = <?php echo json_encode($user_id); ?>;
    const cartItems = JSON.parse(localStorage.getItem(`cartItems_${userId}`)) || [];
    const predefinedAddress = document.getElementById('address-select') ? document.getElementById('address-select').value.trim() : '';
    const manualAddress = document.getElementById('address-manual') ? document.getElementById('address-manual').value.trim() : '';

    let address = manualAddress || predefinedAddress;

    if (cartItems.length === 0) {
        alert('Your cart is empty. Please add items to the cart before placing an order.');
        return;
    }

    if (address === '') {
        alert('Please select or enter your delivery address.');
        return;
    }

    if (confirm('Do you want to confirm the payment and place the order?')) {
        document.getElementById('address').value = address;

        const orderForm = document.getElementById('orderForm');
        if (orderForm) {
            orderForm.submit();
            localStorage.removeItem(`cartItems_${userId}`);
        } else {
            console.error('Order form not found.');
        }
    } else {
        alert('Order placement canceled.');
    }
}

function backButton() {
    window.location.href = "menu.php";
}
    </script>
</body>
</html>
