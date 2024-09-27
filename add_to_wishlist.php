<?php
// Start session and include database connection
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

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product_id from POST request
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$user_id = $_SESSION['user_id'];

// Validate product_id
if ($product_id <= 0) {
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: index.php"); // Redirect to home page if product ID is invalid
    exit();
}

// Prepare and execute the query to check if the product is already in the wishlist
$sql = "SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Product is already in the wishlist
    $_SESSION['message'] = "Product is already in your wishlist.";
} else {
    // Product is not in the wishlist, add it
    $sql = "INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $product_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product added to wishlist.";
    } else {
        $_SESSION['message'] = "Error adding product to wishlist: " . $stmt->error;
    }
}

$stmt->close();
$conn->close();

// Redirect to home page after adding to wishlist
header("Location: index.php");
exit();
?>
