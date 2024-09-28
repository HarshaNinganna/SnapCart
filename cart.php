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

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit();
}

// Fetch cart items for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle update and remove actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $update_sql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $update_stmt->execute();
    } elseif (isset($_POST['remove'])) {
        $product_id = $_POST['product_id'];
        $remove_sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $remove_stmt = $conn->prepare($remove_sql);
        $remove_stmt->bind_param("ii", $user_id, $product_id);
        $remove_stmt->execute();
    }

    // Redirect to avoid resubmission on refresh
    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
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

<main class="container mt-4">
    <h1>Your Cart</h1>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_amount = 0;
                while ($row = $result->fetch_assoc()):
                    $product_id = $row['product_id'];
                    $quantity = $row['quantity'];

                    // Fetch product details
                    $product_sql = "SELECT * FROM products WHERE id = ?";
                    $product_stmt = $conn->prepare($product_sql);
                    $product_stmt->bind_param("i", $product_id);
                    $product_stmt->execute();
                    $product_result = $product_stmt->get_result();
                    $product = $product_result->fetch_assoc();
                    
                    $price = $product['price'];
                    $total = $price * $quantity;
                    $total_amount += $total;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>
                        <form method="post" action="cart.php" class="d-inline">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                            <input type="number" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" min="1" class="form-control" style="width: 80px;">
                            <button type="submit" name="update" class="btn btn-warning btn-sm mt-2">Update</button>
                        </form>
                    </td>
                    <td>₹<?php echo htmlspecialchars(number_format($price, 2)); ?></td>
                    <td>₹<?php echo htmlspecialchars(number_format($total, 2)); ?></td>
                    <td>
                        <form method="post" action="cart.php" class="d-inline">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product_id); ?>">
                            <button type="submit" name="remove" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total Amount</strong></td>
                    <td>₹<?php echo htmlspecialchars(number_format($total_amount, 2)); ?></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <form method="post" action="checkout.php">
            <input type="hidden" name="order_id" value="<?php echo uniqid('ORD_'); ?>">
            <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>">
            <button type="submit" class="btn btn-primary">Proceed to Checkout</button>
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</main>

<footer class="mt-4 text-center">
    <p>&copy; <?php echo date("Y"); ?> SnapCart. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
