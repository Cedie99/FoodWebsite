<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Include the database connection
include 'db.php';

// Fetch all orders with order items excluding deleted ones
$sql = "SELECT o.id, o.total_price, o.firstname, o.lastname, o.email, o.address, o.phoneNumber, o.status, u.username, o.payment_method, o.comments,
        GROUP_CONCAT(oi.product_name, ' ', oi.quantity, 'x' SEPARATOR '<br>') AS order_items
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        WHERE o.deleted = 0
        GROUP BY o.id 
        ORDER BY o.id DESC";
$result = $conn->query($sql);

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['order_id']) && isset($_POST['status'])) {
        // Update order status
        $order_id = $_POST['order_id'];
        $status = $_POST['status'];

        $update_sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $status, $order_id);
        if ($stmt->execute()) {
            $_SESSION['notification'] = "Order status updated successfully!";
            header("Location: admin_panel.php");
            exit();
        } else {
            $_SESSION['notification'] = "Failed to update order status.";
        }
    } elseif (isset($_POST['delete_order'])) {
        // Delete order
        $order_id = $_POST['delete_order'];

        // Set deleted flag for order items
        $delete_items_sql = "UPDATE order_items SET deleted = 1 WHERE order_id = ?";
        $stmt_items = $conn->prepare($delete_items_sql);
        $stmt_items->bind_param("i", $order_id);
        $stmt_items->execute();

        // Set deleted flag for the order itself
        $delete_order_sql = "UPDATE orders SET deleted = 1 WHERE id = ?";
        $stmt_order = $conn->prepare($delete_order_sql);
        $stmt_order->bind_param("i", $order_id);
        if ($stmt_order->execute()) {
            $_SESSION['notification'] = "Order marked as deleted.";
            header("Location: admin_panel.php");
            exit();
        } else {
            $_SESSION['notification'] = "Error deleting order.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="icon" href="pictures/logo-removebg-preview.png" type="image/x-icon">
</head>
<body>
    <style>
         body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 1400px;
            width: 100%;
            
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
            font-size: 20px;
        }

        p {
            color: green;
            text-align: center;
            margin-bottom: 20px;
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            z-index: 999;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            overflow-x: auto;
            font-size: 14px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
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

        select, button[type="submit"] {
            padding: 5px 10px;
            margin-top: 5px;
            margin-bottom: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .deleteBtn {
            background-color: #e74c3c;
            color: white;
        }

        .deleteBtn:hover{
            background-color: #c0392b;
        }

        .updateBtn {
            background-color: #ffba08;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        

        .updateBtn:hover {
            background-color: #c48c00;
        }

        form {
            display: inline-block;
            margin: 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
                max-width: 100%;
            }

            
            th, td {
                display: block;
                width: 40%;
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
    </style>
    <div class="container">
        <h1>Hello, Admin! - Manage Your Orders</h1>
        <?php
        if (isset($_SESSION['notification'])) {
            echo "<p>{$_SESSION['notification']}</p>";
            unset($_SESSION['notification']); // Clear the notification after displaying
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Phone Number</th>
                    <th>Payment Method</th>
                    <th>Comments</th>
                    <th>Status</th>
                    <th>Order Items</th>
                    <th>Total Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="orderTableBody">
            <?php while ($order = $result->fetch_assoc()) { ?>
                <tr data-order-id="<?php echo $order['id']; ?>">
                <td data-label="Order ID"><?php echo $order['id']; ?></td>
            <td data-label="User"><?php echo $order['username']; ?></td>
            <td data-label="First Name"><?php echo $order['firstname']; ?></td>
            <td data-label="Last Name"><?php echo $order['lastname']; ?></td>
            <td data-label="Email"><?php echo $order['email']; ?></td>
            <td data-label="Address"><?php echo $order['address']; ?></td>
            <td data-label="Phone Number"><?php echo $order['phoneNumber']; ?></td>
            <td data-label="Payment Method"><?php echo $order['payment_method']; ?></td>
            <td data-label="Comments"><?php echo $order['comments']; ?></td>
            <td data-label="Status" class="order-status"><?php echo $order['status']; ?></td>
            <td data-label="Order Items"><?php echo $order['order_items']; ?></td>
            <td data-label="Total Price"><?php echo $order['total_price']; ?></td>
            <td data-label="Action">
                        <form class="update-form" action="admin_panel.php" method="post">
                            <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                            <select name="status">
                                <option value="Pending" <?php if ($order['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                <option value="On the way" <?php if ($order['status'] == 'On the way') echo 'selected'; ?>>On the way</option>
                                <option value="Completed" <?php if ($order['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                            </select>
                            <button type="submit" class="updateBtn">Update</button>
                        </form>

                        <form action="admin_panel.php" method="post" onsubmit="return confirm('Are you sure you want to delete this order?');">
                            <input type="hidden" name="delete_order" value="<?php echo $order['id']; ?>">
                            <button type="submit" class="deleteBtn">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
<script>
     document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.update-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('admin_panel.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.text())
                  .then(data => {
                      if (data.includes('Success')) {
                          const orderId = this.querySelector('input[name="order_id"]').value;
                          const newStatus = this.querySelector('select[name="status"]').value;
                          document.querySelector(`tr[data-order-id="${orderId}"] .order-status`).textContent = newStatus;
                          // Display notification
                          const notification = document.createElement('p');
                          notification.textContent = 'Order status updated successfully!';
                          notification.style.color = 'green';
                          notification.style.textAlign = 'center';
                          document.body.appendChild(notification);
                          setTimeout(() => {
                              notification.remove();
                          }, 7000); // Remove notification after 3 seconds
                      } else {
                          alert('Failed to update status.');
                      }
                  }).catch(error => {
                      console.error('Error:', error);
                  });
            });
        });
    });
</script>
</html>
