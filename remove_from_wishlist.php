<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "snapcart";
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID and product ID
$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'];

// Remove the product from wishlist
$sql = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $user_id, $product_id);
$stmt->execute();

// Redirect back to wishlist
header("Location: wishlist.php");
exit();

?>
