<?php
// payment_success.php

session_start();

// Check if order_id is provided
if (!isset($_GET['order_id'])) {
    die("Order ID not provided.");
}

$order_id = intval($_GET['order_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success - SnapCart</title>
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
                <li><a href="index.php">Home</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="user_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Success Message -->
    <main class="container mt-5">
        <div class="alert alert-success">
            <h1>Payment Successful!</h1>
            <p>Your payment was successful and your order ID is: <strong><?php echo htmlspecialchars($order_id); ?></strong>.</p>
            <a href="order_details.php?order_id=<?php echo htmlspecialchars($order_id); ?>" class="btn btn-primary">View Order Details</a>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 SnapCart. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
