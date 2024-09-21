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

// Check if vendor is logged in
if (!isset($_SESSION['vendor_id'])) {
    header('Location: vendor_login.php');
    exit;
}

// Retrieve vendor information
$vendor_id = $_SESSION['vendor_id'];
$sql = "SELECT name, category FROM vendors WHERE id = '$vendor_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $vendor = $result->fetch_assoc();
    $vendor_name = $vendor['name'];
    $vendor_category = $vendor['category'];
} else {
    die("Vendor not found.");
}

// Handle adding a new product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    
    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_dir = 'uploads/';
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0777, true);
        }
        $image_path = $image_dir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image_path)) {
            // Insert product into database with the vendor's category
            $sql = "INSERT INTO products (vendor_id, name, description, price, image, category) 
                    VALUES ('$vendor_id', '$name', '$description', '$price', '$image_path', '$vendor_category')";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success' role='alert'>New product added successfully.</div>";
            } else {
                echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error uploading image.</div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>No image uploaded.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
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
                <li><a href="vendor.php">Dashboard</a></li>
                <li><a href="#myproducts">My Products</a></li>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</a></li>
                <li><a href="view_orders.php">View Orders</a></li>
                <li><a href="vendor_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container mt-4">
        <h1>Welcome, <?php echo htmlspecialchars($vendor_name); ?></h1>
        <p>Category: <strong><?php echo htmlspecialchars($vendor_category); ?></strong></p>
        
        <!-- Product List -->
        <h2>My Products</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Sl. No.</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Category</th> <!-- Add Category Column -->
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch products for the vendor
                $sql = "SELECT * FROM products WHERE vendor_id = '$vendor_id'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $count = 1;
                    while ($product = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $count++ . "</td>";
                        echo "<td>" . htmlspecialchars($product['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($product['description']) . "</td>";
                        echo "<td>₹" . htmlspecialchars($product['price']) . "</td>";
                        echo "<td><img src='" . htmlspecialchars($product['image']) . "' alt='Product Image' style='max-width: 100px;'></td>";
                        echo "<td>" . htmlspecialchars($product['category']) . "</td>"; // Display category
                        echo "<td>";
                        echo "<a href='vendor_edit_product.php?id=" . $product['id'] . "' class='btn btn-warning btn-sm'>Update</a> ";
                        echo "<a href='vendor_delete_product.php?id=" . $product['id'] . "' class='btn btn-danger btn-sm'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No products available.</td></tr>"; // Adjust colspan for added category column
                }
                ?>
            </tbody>
        </table>
    </main>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>© 2024 SnapCart. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap 5 JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
