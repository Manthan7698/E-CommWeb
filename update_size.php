<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart_id = $_POST['cart_id'];
    $size = $_POST['size'];

    $stmt = $conn->prepare("UPDATE cart SET product_size = ? WHERE id = ?");
    $stmt->bind_param("si", $size, $cart_id);
    
    $response = array();
    
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
        $response['error'] = $conn->error;
    }
    
    echo json_encode($response);
    $stmt->close();
    $conn->close();
}
?> 