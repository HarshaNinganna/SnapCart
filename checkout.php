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

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$user_query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Debug output
if (!$user) {
    die("User not found.");
}

// Fetch cart items
$cart_query = "SELECT c.id, c.product_id, c.quantity, p.name, p.price, p.image 
               FROM cart c 
               JOIN products p ON c.product_id = p.id 
               WHERE c.user_id = ?";
$stmt = $conn->prepare($cart_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_result = $stmt->get_result();

$cart_items = [];
$total_price = 0;

while ($row = $cart_result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}
$stmt->close();

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
    $payment_method = $_POST['payment_method'] ?? 'COD'; // Default to COD

    // Insert the order into the database
    $order_query = "INSERT INTO orders (user_id, total_price, order_date, payment_method) VALUES (?, ?, NOW(), ?)";
    $stmt = $conn->prepare($order_query);
    $stmt->bind_param("ids", $user_id, $total_price, $payment_method);
    
    if (!$stmt->execute()) {
        die("Error inserting order: " . $stmt->error);
    }
    
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert order items
    foreach ($cart_items as $item) {
        $order_item_query = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($order_item_query);
        $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        
        if (!$stmt->execute()) {
            die("Error inserting order item: " . $stmt->error);
        }
        $stmt->close();
    }

    // Clear the user's cart
    $clear_cart_query = "DELETE FROM cart WHERE user_id = ?";
    $stmt = $conn->prepare($clear_cart_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Set a session variable to indicate order placement
    $_SESSION['order_success'] = true;
    
    // Redirect to a confirmation page
    header("Location: order_confirmation.php?order_id=$order_id");
    exit();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - SnapCart</title>
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
                <li><a href="home.php">Home</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="user_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Checkout Content -->
    <main class="container mt-4">
        <h1>Checkout</h1>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-md-8">
                <h3>Your Cart</h3>
                <?php if (count($cart_items) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($cart_items as $item): ?>
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
                    <h4 class="mt-4">Total: ₹<?php echo number_format($total_price, 2); ?></h4>
                <?php else: ?>
                    <p>Your cart is empty.</p>
                <?php endif; ?>
            </div>

            <!-- User Info & Payment -->
            <div class="col-md-4">
                <h3>Your Information</h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['first_name']) . ' ' . htmlspecialchars($user['last_name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($user['address1']) . ', ' . htmlspecialchars($user['city']) . ', ' . htmlspecialchars($user['state']); ?></p>

                <h4 class="mt-4">Payment Method</h4>
                <form method="post" action="checkout.php">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="cod" value="COD" checked>
                        <label class="form-check-label" for="cod">Cash on Delivery</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="upi" value="UPI">
                        <label class="form-check-label" for="upi">UPI ID</label>
                        <input type="text" name="upi_id" class="form-control" placeholder="Enter UPI ID" required>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="Credit Card">
                        <label class="form-check-label" for="credit_card">Credit Card</label>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#creditCardModal">Add Credit Card</button>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="debit_card" value="Debit Card">
                        <label class="form-check-label" for="debit_card">Debit Card</label>
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#debitCardModal">Add Debit Card</button>
                    </div>

                    <!-- Credit Card Modal -->
                    <div class="modal fade" id="creditCardModal" tabindex="-1" aria-labelledby="creditCardModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="creditCardModalLabel">Enter Credit Card Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="mb-3">
                                            <label for="cc-number" class="form-label">Card Number</label>
                                            <input type="text" class="form-control" id="cc-number" placeholder="Card Number" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cc-expiry" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control" id="cc-expiry" placeholder="MM/YY" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="cc-cvc" class="form-label">CVC</label>
                                            <input type="text" class="form-control" id="cc-cvc" placeholder="CVC" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> 
                                    <button type="button" class="btn btn-primary">Save Card</button> 
                                </div> 
                            </div> 
                        </div> 
                    </div>
                                    <!-- Debit Card Modal -->
                <div class="modal fade" id="debitCardModal" tabindex="-1" aria-labelledby="debitCardModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="debitCardModalLabel">Enter Debit Card Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="mb-3">
                                        <label for="dc-number" class="form-label">Card Number</label>
                                        <input type="text" class="form-control" id="dc-number" placeholder="Card Number" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dc-expiry" class="form-label">Expiry Date</label>
                                        <input type="text" class="form-control" id="dc-expiry" placeholder="MM/YY" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="dc-cvc" class="form-label">CVC</label>
                                        <input type="text" class="form-control" id="dc-cvc" placeholder="CVC" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save Card</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" name="checkout" class="btn btn-primary mt-3">Place Order</button>
            </form>
        </div>
    </div>
</main>

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; 2024 SnapCart. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

