<?php
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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $mobile_number = mysqli_real_escape_string($conn, $_POST['mobile_number']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $dob_year = date('Y', strtotime($dob));  // Extract year from DOB
    $address1 = mysqli_real_escape_string($conn, $_POST['address1']);
    $address2 = mysqli_real_escape_string($conn, $_POST['address2']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $area_code = mysqli_real_escape_string($conn, $_POST['area_code']);
    $role = 'client'; // Default role is 'client'

    // Generate username using firstname and DOB year
    $username = strtolower($first_name . '_' . $dob_year);

    // Handle file upload for profile image
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $image_name = $_FILES['profile_image']['name'];
        $image_tmp_name = $_FILES['profile_image']['tmp_name'];
        $image_folder = 'uploads/' . $image_name;
        move_uploaded_file($image_tmp_name, $image_folder);
    } else {
        $image_folder = null;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match. Please try again.";
    } else {
        // Hash password before storing it
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if username already exists
        $username_check_query = "SELECT * FROM users WHERE username = '$username'";
        $username_check_result = mysqli_query($conn, $username_check_query);

        if (mysqli_num_rows($username_check_result) > 0) {
            $error = "Username already taken. Please use a different first name or DOB.";
        } else {
            // Insert user into the database
            $insert_query = "INSERT INTO users 
            (first_name, last_name, username, email, password, mobile_number, gender, dob, address1, address2, city, state, country, area_code, profile_image, role) 
            VALUES ('$first_name', '$last_name', '$username', '$email', '$hashed_password', '$mobile_number', '$gender', '$dob', '$address1', '$address2', '$city', '$state', '$country', '$area_code', '$image_folder', '$role')";

            if (mysqli_query($conn, $insert_query)) {
                $success = "Registration successful! Your username is: " . $username . ". You can now log in.";
                // Optionally redirect to login page after successful registration
                // header("Location: user_login.php");
                // exit();
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SnapCart</title>
    <link rel="stylesheet" href="register_style.css">
</head>
<body>

    <div class="register-container">
        <h2>Register to SnapCart</h2>

        <!-- Display success message -->
        <?php if ($success): ?>
            <div class="success-message">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <!-- Display error message -->
        <?php if ($error): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Registration Form -->
        <form method="POST" action="register.php" enctype="multipart/form-data">
            <label for="profile_image">Profile Image:</label>
            <input type="file" name="profile_image" accept="image/*">

            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" required>

            <label for="mobile_number">Mobile Number:</label>
            <input type="text" name="mobile_number" required>

            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" required>

            <label for="address1">Address Line 1:</label>
            <input type="text" name="address1" required>

            <label for="address2">Address Line 2:</label>
            <input type="text" name="address2">

            <label for="city">City:</label>
            <input type="text" name="city" required>

            <label for="state">State:</label>
            <input type="text" name="state" required>

            <label for="country">Country:</label>
            <input type="text" name="country" required>

            <label for="area_code">Area Code:</label>
            <input type="text" name="area_code" required>

            <button type="submit">Register</button>
        </form>

        <p>Already have an account? <a href="user_login.php">Login here</a>.</p>
    </div>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
