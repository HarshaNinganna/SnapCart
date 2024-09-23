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
$error_message = '';
$success_message = '';
$product_details = [];

// Handle fetching product details by order ID
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_order'])) {
    $order_id = intval($_POST['order_id']);
    if ($order_id > 0) {
        // Fetch order details
        $order_query = "SELECT oi.product_id, p.name, oi.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
        $stmt = $conn->prepare($order_query);
        
        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }
        
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $order_items_result = $stmt->get_result();

        if ($order_items_result->num_rows > 0) {
            while ($row = $order_items_result->fetch_assoc()) {
                $product_details[] = $row;
            }
        } else {
            $error_message = "No products found for this order ID.";
        }
        $stmt->close();
    } else {
        $error_message = "Invalid order ID.";
    }
}

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['process_payment'])) {
    $payment_method = $_POST['payment_method'];
    $amount = floatval($_POST['amount']);
    $upi_id = isset($_POST['upi_id']) ? $_POST['upi_id'] : '';

    // Validate input
    if ($payment_method && $amount > 0) {
        // Update order status and payment details
        $update_query = "UPDATE orders SET order_status = 'completed', payment_method = ?, paid_amount = ? WHERE order_id = ?";
        $stmt = $conn->prepare($update_query);

        if (!$stmt) {
            die("Preparation failed: " . $conn->error);
        }

        // Replace with appropriate order_id after fetching
        // You can pass the order_id if needed or store it in a session variable
        $order_id = intval($_POST['order_id']);
        $stmt->bind_param("sdi", $payment_method, $amount, $order_id);
        if ($stmt->execute()) {
            $success_message = "Payment successful. Your order has been completed.";
        } else {
            $error_message = "Payment failed: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error_message = "Please fill in all fields correctly.";
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
    <title>Payment - SnapCart</title>
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
        <h1>Payment</h1>

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php elseif ($success_message): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>

        <form method="POST" id="order-form">
            <div class="mb-3">
                <label for="order_id" class="form-label">Order ID</label>
                <input type="number" name="order_id" class="form-control" required>
            </div>
            <button type="submit" name="fetch_order" class="btn btn-primary">Fetch Order Details</button>
        </form>

        <?php if (!empty($product_details)): ?>
            <h3>Product Details</h3>
            <ul class="list-group">
                <?php foreach ($product_details as $product): ?>
                    <li class="list-group-item">
                        <strong><?php echo htmlspecialchars($product['name']); ?></strong> - ₹<?php echo number_format($product['price'], 2); ?>
                        <input type="hidden" name="amount" value="<?php echo number_format($product['price'], 2); ?>">
                    </li>
                <?php endforeach; ?>
            </ul>
            <form method="POST" id="payment-form">
                <input type="hidden" name="order_id" value="<?php echo intval($_POST['order_id']); ?>">
                <input type="hidden" name="amount" value="<?php echo number_format($product['price'], 2); ?>">
                
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="">Select Payment Method</option>
                        <option value="UPI">UPI</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                    </select>
                </div>

                <div id="upiDetails" class="mb-3 d-none">
                    <label for="upi_id" class="form-label">Enter UPI ID</label>
                    <input type="text" id="upi_id" name="upi_id" class="form-control" placeholder="example@upi" required>
                </div>

                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">Proceed to Payment</button>

                <!-- Payment Modal -->
                <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">Enter Payment Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="cardDetails" class="d-none">
                                    <div class="mb-3">
                                        <label for="card_number" class="form-label">Card Number</label>
                                        <input type="text" id="card_number" class="form-control" placeholder="1234 5678 9012 3456">
                                    </div>
                                    <div class="mb-3">
                                        <label for="card_expiry" class="form-label">Expiry Date</label>
                                        <input type="text" id="card_expiry" class="form-control" placeholder="MM/YY">
                                    </div>
                                    <div class="mb-3">
                                        <label for="card_cvc" class="form-label">CVC</label>
                                        <input type="text" id="card_cvc" class="form-control" placeholder="123">
                                    </div>
                                </div>
                                <div id="debitDetails" class="d-none">
                                    <div class="mb-3">
                                        <label for="debit_card_number" class="form-label">Card Number</label>
                                        <input type="text" id="debit_card_number" class="form-control" placeholder="1234 5678 9012 3456">
                                    </div>
                                    <div class="mb-3">
                                        <label for="debit_card_expiry" class="form-label">Expiry Date</label>
                                        <input type="text" id="debit_card_expiry" class="form-control" placeholder="MM/YY">
                                    </div>
                                    <div class="mb-3">
                                        <label for="debit_card_cvc" class="form-label">CVC</label>
                                        <input type="text" id="debit_card_cvc" class="form-control" placeholder="123">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="process_payment" class="btn btn-primary">Submit Payment</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 SnapCart. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('payment_method').addEventListener('change', function() {
            const upiDetails = document.getElementById('upiDetails');
            const cardDetails = document.getElementById('cardDetails');
            const debitDetails = document.getElementById('debitDetails');
            upiDetails.classList.add('d-none');
            cardDetails.classList.add('d-none');
            debitDetails.classList.add('d-none');
            if (this.value === 'UPI') {
                upiDetails.classList.remove('d-none');
            } else if (this.value === 'credit_card') {
                cardDetails.classList.remove('d-none');
            } else if (this.value === 'debit_card') {
                debitDetails.classList.remove('d-none');
            }
        });
    </script>
</body>
</html>
