<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in.");
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

// Retrieve the product_id and quantity from the POST request
if (isset($_POST['product_id'], $_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = (int)$_SESSION['user_id'];

    // Validate quantity (ensure it's a positive integer)
    if ($quantity <= 0) {
        die("Invalid quantity. Must be a positive number.");
    }

    // Check if the product exists
    $stmt = $conn->prepare("SELECT id FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        die("Product does not exist.");
    }
    $stmt->close();

    // Check if item already exists in the cart
    $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Item already exists in the cart, update the quantity
        $stmt->bind_result($cart_id, $existing_quantity);
        $stmt->fetch();
        $new_quantity = $existing_quantity + $quantity;

        $stmt->close();
        $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $stmt->bind_param("ii", $new_quantity, $cart_id);
    } else {
        // Item does not exist in the cart, insert a new row
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    }

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "The item has been successfully added to your cart.";
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
} else {
    die("Invalid input. Product ID and quantity are required.");
}

$conn->close();
?>
