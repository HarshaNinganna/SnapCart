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
$order = null;
$order_items = [];
$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

// Fetch order details if order_id is provided
if ($order_id > 0) {
    $order_query = "SELECT * FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($order_query);

    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order_result = $stmt->get_result();

    if ($order_result->num_rows === 0) {
        $error_message = "Order not found.";
    } else {
        $order = $order_result->fetch_assoc();
    }
    $stmt->close();

    // Fetch order items
    if ($order) {
        $order_items_query = "SELECT oi.*, p.name, p.image FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
        $stmt = $conn->prepare($order_items_query);

        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $order_items_result = $stmt->get_result();

        while ($row = $order_items_result->fetch_assoc()) {
            $order_items[] = $row;
        }
        $stmt->close();
    }

    // Handle cancel order request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
        if ($order['order_status'] === 'pending') {
            $cancel_query = "UPDATE orders SET order_status = 'canceled' WHERE order_id = ?";
            $stmt = $conn->prepare($cancel_query);

            if (!$stmt) {
                die("Preparation failed: " . $conn->error);
            }

            $stmt->bind_param("i", $order_id);
            if ($stmt->execute()) {
                $success_message = "Order canceled successfully.";
                $order['order_status'] = 'canceled'; // Update local order status
            } else {
                $error_message = "Failed to cancel the order.";
            }
            $stmt->close();
        } else {
            $error_message = "Only pending orders can be canceled.";
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - SnapCart</title>
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
        <h1>Track Your Order</h1>
        
        <!-- Order ID form -->
        <form method="POST" class="mb-4">
            <div class="input-group">
                <input type="number" name="order_id" class="form-control" placeholder="Enter Order ID" required>
                <button class="btn btn-primary" type="submit">Track Order</button>
            </div>
        </form>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php elseif (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php elseif ($order): ?>
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

            <!-- Cancel Order Button -->
            <?php if ($order['order_status'] === 'pending'): ?>
                <form method="POST" class="mt-3">
                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order['order_id']); ?>">
                    <button name="cancel_order" class="btn btn-danger">Cancel Order</button>
                </form>
            <?php endif; ?>
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
