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

// Initialize variables
$user_id = $_SESSION['user_id'];
$orders = [];
$message = '';

// Fetch orders for the user
$order_query = "SELECT * FROM orders WHERE user_id = ? ORDER BY placed_at DESC";
$stmt = $conn->prepare($order_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_result = $stmt->get_result();

while ($row = $order_result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();

// Handle cancel order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $order_id = intval($_POST['order_id']);
    
    // Check if the order is in 'pending' status
    $check_query = "SELECT order_status FROM orders WHERE order_id = ? AND user_id = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $order_id, $user_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        $order = $check_result->fetch_assoc();
        if ($order['order_status'] === 'pending') {
            // Cancel the order
            $cancel_query = "UPDATE orders SET order_status = 'canceled' WHERE order_id = ?";
            $stmt = $conn->prepare($cancel_query);
            $stmt->bind_param("i", $order_id);
            if ($stmt->execute()) {
                $message = "Order ID $order_id has been canceled.";
            } else {
                $message = "Failed to cancel order: " . $stmt->error;
            }
        } else {
            $message = "Only pending orders can be canceled.";
        }
    } else {
        $message = "Order not found or you do not have permission to cancel this order.";
    }
    $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - SnapCart</title>
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
                <li><a href="offers.php">Offers</a></li>
            </ul>
        </nav>
    </header>

    <main class="container mt-4">
        <h1>Manage Your Orders</h1>

        <?php if ($message): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <h3>Order History</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($orders) > 0): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($order['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                            <td>₹<?php echo number_format($order['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($order['placed_at']); ?></td>
                            <td>
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                                    <button type="submit" name="cancel_order" class="btn btn-danger btn-sm" 
                                        <?php echo ($order['order_status'] !== 'pending') ? 'disabled' : ''; ?>>Cancel</button>
                                </form>
                                <a href="track_order.php?order_id=<?php echo htmlspecialchars($order['order_id']); ?>" class="btn btn-info btn-sm">Track</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No orders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 SnapCart. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
