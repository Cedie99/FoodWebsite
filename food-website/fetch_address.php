<?php
include 'db.php'; // Include your database connection script

// Get user ID (e.g., from session)
$user_id = $_SESSION['user_id'];

// Fetch user addresses from the database
$addresses_query = "SELECT address FROM user_addresses WHERE user_id='$user_id'";
$addresses_result = $conn->query($addresses_query);

$addresses = [];
if ($addresses_result->num_rows > 0) {
    while ($row = $addresses_result->fetch_assoc()) {
        $addresses[] = $row['address'];
    }
}

echo json_encode($addresses);
?>
