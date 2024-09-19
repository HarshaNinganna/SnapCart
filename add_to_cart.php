<?php
// Start session and include database connection
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

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the product_id and quantity from the POST request
if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $user_id = (int)$_SESSION['user_id'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT id FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Item already exists in the cart, update the quantity
        $stmt->close();
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param("iii", $quantity, $user_id, $product_id);
    } else {
        // Item does not exist in the cart, insert a new row
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    }

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo "Item added to cart successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
} else {
    echo "Invalid input.";
}

$conn->close();
?>
