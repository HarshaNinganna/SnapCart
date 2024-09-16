<?php
// Start session and include database connection
session_start();

// Database connection
$servername = "localhost"; // Assuming you're using XAMPP or similar
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password (empty by default in XAMPP)
$database = "snapcart"; // Your database name

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SnapCart</title>
    <!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap 5 JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="snap_index_style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">SnapCart</div>
        <nav>
            <ul>
                <li><a href="#">Home</a></li>
                <li><a href="#">Products</a></li>
                <li><a href="cart.php">Cart</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <h1>Products</h1>
        <div class="product-grid">
            <?php
            // Fetch products from the database
            $sql = "SELECT * FROM products"; // Query to get all products
            $result = $conn->query($sql);

            // Check if any products are returned
            if ($result->num_rows > 0) {
                // Loop through each product
                while ($product = $result->fetch_assoc()) {
                    echo '<div class="product-card">';
                    echo '<img src="'.$product['image'].'" alt="'.$product['name'].'">';
                    echo '<h2>'.$product['name'].'</h2>';
                    echo '<p class="price">₹'.$product['price'].'</p>';
                    echo '<form method="post" action="add_to_cart.php">';
                    echo '<input type="hidden" name="product_id" value="'.$product['id'].'">';
                    echo '<button type="submit" class="btn">Add to Cart</button>';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "<p>No products available.</p>";
            }

            // Free result set and close the connection
            $result->free();
            $conn->close();
            ?>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2024 SnapCart. All Rights Reserved.</p>
    </footer>
</body>
</html>
