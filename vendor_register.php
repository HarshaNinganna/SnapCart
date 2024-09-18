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

// Initialize error and success messages
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $vendor_name = mysqli_real_escape_string($conn, $_POST['vendor_name']);
    $license_no = mysqli_real_escape_string($conn, $_POST['license_no']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile_no = mysqli_real_escape_string($conn, $_POST['mobile_no']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password

    // Check if email already exists
    $query = "SELECT * FROM vendors WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $error = "Email is already registered.";
    } else {
        // Insert the vendor data into the vendors table
        $query = "INSERT INTO vendors (name, license_no, email, mobile_no, password, category) 
                  VALUES ('$vendor_name', '$license_no', '$email', '$mobile_no', '$hashed_password', '$category')";
        
        if (mysqli_query($conn, $query)) {
            $success = "Registration successful. You can now login.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Registration - SnapCart</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="snap_index_style.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Vendor Registration</h2>

        <!-- Display error or success messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form method="POST" action="vendor_register.php">
            <!-- Category Selection -->
            <div class="mb-3">
                <label for="category" class="form-label">Select Category</label>
                <select name="category" id="category" class="form-control" required>
                    <option value="">Select Category</option>
                    <option value="Electronics">Electronics</option>
                    <option value="Clothing">Clothing</option>
                    <option value="Home Appliances">Home Appliances</option>
                    <option value="Books">Books</option>
                    <option value="Sports">Sports</option>
                    <option value="Toys">Toys</option>
                </select>
            </div>

            <!-- Vendor Name -->
            <div class="mb-3">
                <label for="vendor_name" class="form-label">Vendor Name</label>
                <input type="text" name="vendor_name" id="vendor_name" class="form-control" required>
            </div>

            <!-- License Number -->
            <div class="mb-3">
                <label for="license_no" class="form-label">License No.</label>
                <input type="text" name="license_no" id="license_no" class="form-control" required>
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <!-- Mobile Number -->
            <div class="mb-3">
                <label for="mobile_no" class="form-label">Mobile No.</label>
                <input type="tel" name="mobile_no" id="mobile_no" class="form-control" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <p class="mt-3">Already have an account? <a href="vendor_login.php">Login here</a>.</p>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
