<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "snapcart";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the wishlist_id from the POST request
if (isset($_POST['wishlist_id'])) {
    $wishlist_id = (int)$_POST['wishlist_id'];

    // Delete the product from the wishlist
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $wishlist_id, $user_id);

    if ($stmt->execute()) {
        echo "Item deleted from wishlist successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid input. Wishlist ID is required.";
}

$conn->close();
?>
