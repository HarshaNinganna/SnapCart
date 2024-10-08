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
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL query to fetch wishlist items
    $sql = "SELECT w.id AS wishlist_id, p.id AS product_id, p.name, p.image, p.price 
            FROM wishlist w 
            JOIN products p ON w.product_id = p.id 
            WHERE w.user_id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="wishlist-item">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="' . htmlspecialchars($row['name']) . '" style="max-width:100px;">';
                echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                echo '<p class="price">₹' . htmlspecialchars($row['price']) . '</p>';

                // Form to add to cart
                echo '<form method="post" action="add_to_cart.php" class="d-inline">';
                echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($row['product_id']) . '">';
                echo '<input type="hidden" name="quantity" value="1">';
                echo '<button type="submit" class="btn btn-primary">Add to Cart</button>';
                echo '</form>';

                // Form to delete from wishlist
                echo '<form method="post" action="delete_from_wishlist.php" class="d-inline">';
                echo '<input type="hidden" name="wishlist_id" value="' . htmlspecialchars($row['wishlist_id']) . '">';
                echo '<button type="submit" class="btn btn-danger">Delete</button>';
                echo '</form>';

                echo '</div>'; // Closing wishlist-item div
                echo '<hr>'; // Optional, for visual separation
            }
        } else {
            echo '<p>Your wishlist is empty.</p>';
        }

        $stmt->close();
    } else {
        // Prepare failed
        echo 'Error preparing statement: ' . $conn->error;
    }
} else {
    echo '<p>User not logged in.</p>';
}

$conn->close();
?>
