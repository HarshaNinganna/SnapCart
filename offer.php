<?php
// Start the session
session_start();

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

// Fetch offers from the database
$offers_query = "SELECT * FROM offers WHERE is_active = 1";
$offers_result = $conn->query($offers_query);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Offers - SnapCart</title>
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
        <h1>Current Offers</h1>

        <?php if ($offers_result->num_rows > 0): ?>
            <div class="list-group">
                <?php while ($offer = $offers_result->fetch_assoc()): ?>
                    <div class="list-group-item">
                        <h5><?php echo htmlspecialchars($offer['title']); ?></h5>
                        <p><?php echo htmlspecialchars($offer['description']); ?></p>
                        <p><strong>Discount:</strong> <?php echo htmlspecialchars($offer['discount']); ?>%</p>
                        <p><strong>Valid Until:</strong> <?php echo htmlspecialchars($offer['valid_until']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No current offers available.</p>
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
