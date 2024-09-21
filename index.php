<?php
// Start session and include database connection
session_start();

// Database connection parameters
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

// Initialize user variable
$user = null;

// Fetch logged-in user details if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_query);
    
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_result = $stmt->get_result();
        
        // Fetch user data if available
        if ($user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
        } else {
            // Handle case where user ID doesn't exist
            $user = null;
        }
        
        $stmt->close();
    } else {
        // Log statement error if preparation fails
        error_log("Prepare statement failed: " . $conn->error);
    }
}

// Fetch all products categorized by their category
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error in query: " . $conn->error);
}

// Example user registration logic (ensure this part is in the right place)
if (isset($_POST['register'])) { // Assuming a form submission for registration
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Make sure to hash this before storing
    
    // Insert user into database
    $registration_query = "INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($registration_query);
    
    if ($stmt) {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);
        $stmt->execute();
        
        // Get the newly created user ID
        $user_id = $stmt->insert_id;
        
        // Store user details in session
        $_SESSION['user_id'] = $user_id; // Set this to the new user's ID
        $_SESSION['first_name'] = $first_name; // Store the user's first name
        
        // Redirect to home page or wherever appropriate
        header("Location: home.php");
        exit();
    } else {
        // Log registration error if preparation fails
        error_log("Prepare statement for registration failed: " . $conn->error);
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SnapCart - Home</title>

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
                <li><a href="#">Home</a></li>
                <li><a href="#electronics">Products</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#wishlistModal">Wishlist</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#cartModal">Cart</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">Profile</a>
                        <ul class="dropdown-menu" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">View Profile</a></li>
                            <li><a class="dropdown-item" href="track_order.php">Track My Orders</a></li>
                            <li><a class="dropdown-item" href="manage_account.php">Manage Accounts</a></li>
                            <li><a class="dropdown-item" href="offer.php">Gift Cards and offers</a></li>
                            <li><a class="dropdown-item" href="payment.php">Payments</a></li>
                            <li><a class="dropdown-item" href="user_logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li><a href="user_login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <!-- Cart Modal -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Your Cart</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cartItems">
                        <p>Loading cart items...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="cart.php" class="btn btn-primary">Checkout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Wishlist Modal -->
    <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="wishlistModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="wishlistModalLabel">Your Wishlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="wishlistItems">
                        <p>Loading wishlist items...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="cart.php" class="btn btn-primary">Add to Cart</a>
                </div>
            </div>
        </div>
    </div>

   <!-- Profile Modal -->
<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php if ($user): ?>
                    <form method="post" action="update_profile.php" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="mobile_number" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($user['mobile_number']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="Male" <?php echo ($user['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($user['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                                <option value="Other" <?php echo ($user['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address1" class="form-label">Address Line 1</label>
                            <input type="text" class="form-control" id="address1" name="address1" value="<?php echo htmlspecialchars($user['address1']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="address2" class="form-label">Address Line 2</label>
                            <input type="text" class="form-control" id="address2" name="address2" value="<?php echo htmlspecialchars($user['address2']); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" value="<?php echo htmlspecialchars($user['city']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="area_code" class="form-label">Area code</label>
                            <input type="text" class="form-control" id="area_code" name="area_code" value="<?php echo htmlspecialchars($user['area_code']); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                <?php else: ?>
                    <p>Please <a href="user_login.php">login</a> to view your profile.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<main class="container mt-4">
    <h1>Welcome to SnapCart<?php if (isset($user) && !empty($user)): ?>, <?php echo htmlspecialchars($user['first_name']); ?>!<?php endif; ?></h1>
</main>




        <!-- Categories Filter -->
        <div class="categories">
            <h3>Category</h3>
            <ul>
                <li><a href="#electronics">Electronics</a></li>
                <li><a href="#clothing">Clothing</a></li>
                <li><a href="#home_appliances">Home Appliances</a></li>
                <li><a href="#books">Books</a></li>
                <li><a href="#sports">Sports</a></li>
                <li><a href="#toys">Toys</a></li>
            </ul>
        </div>

        <!-- Product Sections by Category -->
        <div class="product-grid">
            <?php
            // Initialize categories array
            $categories = [
                'Electronics' => [],
                'Clothing' => [],
                'Home Appliances' => [],
                'Books' => [],
                'Sports' => [],
                'Toys' => []
            ];

            // Fetch and categorize products
            while ($product = $result->fetch_assoc()) {
                $category = isset($product['category']) ? $product['category'] : 'Uncategorized';
                if (!array_key_exists($category, $categories)) {
                    $categories[$category] = [];
                }
                $categories[$category][] = $product;
            }

            // Display products by category
            foreach ($categories as $category => $products) {
                $category_id = strtolower(str_replace(' ', '_', $category));
                echo "<section id='$category_id'>";
                echo "<h2>" . htmlspecialchars($category) . "</h2>";
                if (count($products) > 0) {
                    foreach ($products as $product) {
                        echo '<div class="product-card">';
                        echo '<img src="' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" style="max-width:100px;">';
                        echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
                        echo '<p class="price">₹' . htmlspecialchars($product['price']) . '</p>';
                        echo '<form method="post" action="add_to_cart.php" class="d-inline">';
                        echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($product['id']) . '">';
                        echo '<input type="hidden" name="quantity" value="1">';
                        echo '<button type="submit" class="btn btn-primary">Add to Cart</button>';
                        echo '</form>';
                        echo '<form method="post" action="add_to_wishlist.php" class="d-inline">';
                        echo '<input type="hidden" name="product_id" value="' . htmlspecialchars($product['id']) . '">';
                        echo '<button type="submit" class="btn btn-secondary">Add to Wishlist</button>';
                        echo '</form>';
                        echo '<button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#productDescriptionModal" data-product-id="' . htmlspecialchars($product['id']) . '">Description</button>';
                        echo '</div>'; // Closing product-card div
                    }
                } else {
                    echo "<p>No products available in this category.</p>";
                }
                echo "</section>"; // Closing category section
            }
            ?>
        </div>
    </main>

    <!-- Product Description Modal -->
    <div class="modal fade" id="productDescriptionModal" tabindex="-1" aria-labelledby="productDescriptionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDescriptionModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="productDetails">
                        <!-- Product details will be dynamically loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 SnapCart. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to fetch product details
        function fetchProductDetails(productId) {
            fetch(`fetch_product_details.php?id=${productId}`)
                .then(response => response.json())
                .then(data => {
                    const productDetailsDiv = document.getElementById('productDetails');
                    productDetailsDiv.innerHTML = `
                        <h3>${data.name}</h3>
                        <img src="${data.image}" alt="${data.name}" style="max-width: 200px;">
                        <p class="price">₹${data.price}</p>
                        <p>${data.description}</p>
                    `;
                })
                .catch(error => {
                    console.error('Error fetching product details:', error);
                    document.getElementById('productDetails').innerHTML = '<p>Error loading product details.</p>';
                });
        }

        document.querySelectorAll('.btn-info').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                fetchProductDetails(productId);
            });
        });

        // Load cart items
        fetch('fetch_cart_items.php')
            .then(response => response.text())
            .then(data => document.getElementById('cartItems').innerHTML = data);

        // Load wishlist items
        fetch('fetch_wishlist_items.php')
            .then(response => response.text())
            .then(data => document.getElementById('wishlistItems').innerHTML = data);
    </script>
</body>
</html>

