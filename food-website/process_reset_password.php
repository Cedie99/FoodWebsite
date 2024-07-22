<?php
include 'db.php'; 

// Check if form submitted
if (isset($_POST['submit'])) {
    $recovery_phrase = $_POST['recovery_phrase']; // Add recovery phrase field
    $new_password = $_POST['new_password'];

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the user's password using the recovery phrase
    $query_update = "UPDATE users SET password = ? WHERE recovery_phrase = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("ss", $hashed_password, $recovery_phrase);
    $stmt_update->execute();

    // Check if update was successful
    if ($stmt_update->affected_rows > 0) {
        echo "Password reset successfully. You can now <a href='login.php'>login</a> with your new password.";
    } else {
        echo "Invalid recovery phrase. Please try again.";
    }

    // Close statement and database connection
    $stmt_update->close();
    $conn->close();
}
?>

<html>
    <button onclick="back()">Back</button>

    <script>
        function back(){
            window.location.href="login.php";
        }
    </script>
</html>




