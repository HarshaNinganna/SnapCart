<?php
// Start session and include database connection
session_start();

// Database connection parameters
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

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p>You need to log in to view your cart.</p>";
    exit();
}

// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);

// Check if prepare failed
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any cart items
if ($result->num_rows > 0) {
    echo "<table class='table table-striped'>";
    echo "<thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Action</th></tr></thead>";
    echo "<tbody>";
    while ($row = $result->fetch_assoc()) {
        // Fetch product details
        $product_id = $row['product_id'];
        $product_sql = "SELECT name, price, image FROM products WHERE id = ?";
        $product_stmt = $conn->prepare($product_sql);

        // Check if prepare failed
        if ($product_stmt === false) {
            die("Prepare failed: " . $conn->error);
        }

        $product_stmt->bind_param("i", $product_id);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        $product = $product_result->fetch_assoc();

        $name = htmlspecialchars($product['name']);
        $price = (float)$product['price'];
        $image = htmlspecialchars($product['image']);
        $quantity = $row['quantity'];

        echo "<tr>";
        echo "<td><img src='" . htmlspecialchars($image) . "' alt='" . htmlspecialchars($name) . "' style='max-width:100px;'> " . htmlspecialchars($name) . "</td>";
        echo "<td>₹" . number_format($price, 2) . "</td>";
        echo "<td>" . htmlspecialchars($quantity) . "</td>";
        echo "<td>";
        echo "<form method='post' action='remove_from_cart.php' style='display:inline-block;'>";
        echo "<input type='hidden' name='product_id' value='" . htmlspecialchars($product_id) . "'>";
        echo "<button type='submit' class='btn btn-danger'>Remove</button>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</tbody>";
    echo "</table>";
} else {
    echo "<p>Your cart is empty.</p>";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
