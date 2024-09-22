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

// Retrieve vendor ID from session
$vendor_id = $_SESSION['vendor_id'];

// Fetch orders related to products of this vendor
$order_query = "
    SELECT o.id AS order_id, o.total_price, o.order_date, 
           u.first_name, u.last_name, u.email, 
           GROUP_CONCAT(p.name SEPARATOR ', ') AS product_names
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    JOIN products p ON oi.product_id = p.id
    JOIN users u ON o.user_id = u.id
    WHERE p.vendor_id = ?
    GROUP BY o.id
    ORDER BY o.order_date DESC
";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $vendor_id);
$stmt->execute();
$order_result = $stmt->get_result();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders - Vendor Dashboard</title>
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
                <li><a href="vendor_add_product.php">Add Product</a></li>
                <li><a href="view_orders.php">View Orders</a></li>
                <li><a href="vendor_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mt-4">
        <h1>Your Orders</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Email</th>
                    <th>Product Names</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($order_result->num_rows > 0): ?>
                    <?php while ($order = $order_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['first_name'] ); ?></td>
                            <td><?php echo htmlspecialchars($order['email']); ?></td>
                            <td><?php echo htmlspecialchars($order['product_names']); ?></td>
                            <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['order_date']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No orders available.</td>
                    </tr>
                <?php endif; ?>
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
