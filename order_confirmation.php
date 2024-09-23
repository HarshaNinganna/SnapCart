<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "snapcart";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if order_id is provided
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id === 0) {
    die("No order ID provided.");
}

// Fetch order details
// Fetch order details
$order_query = "SELECT * FROM orders WHERE order_id = ?";

$stmt = $conn->prepare($order_query);

if (!$stmt) {
    die("Preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    die("Order not found.");
}

$order = $order_result->fetch_assoc();
$stmt->close();

// Fetch order items
$order_items_query = "SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
$stmt = $conn->prepare($order_items_query);

if (!$stmt) {
    die("Preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items_result = $stmt->get_result();

$order_items = [];
while ($row = $order_items_result->fetch_assoc()) {
    $order_items[] = $row;
}
$stmt->close();

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - SnapCart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="snap_index_style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="assets/logo1.png" alt="SnapCart Logo">
        </div>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="user_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <main class="container mt-4">
        <h1>Order Confirmation</h1>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($order['order_status']); ?></p>
        <p><strong>Total Price:</strong> ₹<?php echo number_format($order['total_price'], 2); ?></p>
        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['placed_at']); ?></p>

        <h3>Ordered Items</h3>
        <?php if (count($order_items) > 0): ?>
            <ul class="list-group">
                <?php foreach ($order_items as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" style="max-width: 50px;">
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                            <span>(x<?php echo htmlspecialchars($item['quantity']); ?>)</span>
                        </div>
                        <span>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No items found for this order.</p>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 SnapCart. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
