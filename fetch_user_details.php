<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "snapcart";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Initialize user variable
$user = null;

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Prepare the SQL query
    $user_query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($user_query);

    if ($stmt) {
        // Bind parameters and execute the statement
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $user_result = $stmt->get_result();

        // Fetch user data if available
        if ($user_result->num_rows > 0) {
            $user = $user_result->fetch_assoc();
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            exit();
        }

        $stmt->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Internal server error']);
        exit();
    }
} else {
    http_response_code(403);
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

// Set the header for JSON response
header('Content-Type: application/json');
echo json_encode($user);

// Close the database connection
$conn->close();
?>
