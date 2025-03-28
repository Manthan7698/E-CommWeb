<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $_POST['cart_id'];
    
    if (!empty($cart_id)) {
        // Delete item from cart table
        $query = "DELETE FROM cart WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $cart_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing cart ID']);
    }
}

$conn->close();
?> 