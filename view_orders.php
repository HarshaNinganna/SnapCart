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

// Check if vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
    header('Location: vendor_login.php');
    exit;
}

// Retrieve vendor information
$vendor_id = $_SESSION['vendor_id'];

// Handle fetching orders for the vendor
$sql = "SELECT o.id, p.name AS product_name, o.quantity, o.total_price, o.order_date
        FROM orders o
        JOIN products p ON o.product_id = p.id
        WHERE p.vendor_id = '$vendor_id'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="snap_index_style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo">
            <img src="assets/logo1.png" alt="SnapCart Logo">
        </div>
        <nav>
            <ul>
                <li><a href="vendor.php">Dashboard</a></li>
                <li><a href="#myproducts">My Products</a></li>
                <li><a href="vendor_view_orders.php">View Orders</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</a></li>
                <li><a href="vendor_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mt-4">
        <h1>View Orders</h1>
        
        <!-- Order List -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($order = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($order['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['product_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['quantity']) . "</td>";
                        echo "<td>₹" . htmlspecialchars($order['total_price']) . "</td>";
                        echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <!-- Footer -->
    <footer>
        <p>© 2024 SnapCart. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap 5 JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
