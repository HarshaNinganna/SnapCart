<?php
// Start session and include database connection
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "snapcart";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Check if the product_id is set in the POST request
if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $user_id = (int)$_SESSION['user_id'];

    // Prepare the SQL query to remove the item from the cart
    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $user_id, $product_id);
        
        if ($stmt->execute()) {
            // Successfully removed the item
            $_SESSION['message'] = "Item removed from cart successfully.";
        } else {
            // Error occurred while removing the item
            $_SESSION['error'] = "Error removing item from cart: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Prepare failed
        $_SESSION['error'] = "Error preparing statement: " . $conn->error;
    }
} else {
    $_SESSION['error'] = "Invalid request. Product ID is required.";
}

// Redirect back to the cart or wherever appropriate
header("Location: cart.php");
exit();

// Close the database connection
$conn->close();
?>
