<?php
session_start();
header('Content-Type: application/json');

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "snapcart";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT name, image, description, price FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $stmt->close();
    $conn->close();

    echo json_encode($product);
} else {
    echo json_encode(['error' => 'Product ID not provided']);
}
?>
