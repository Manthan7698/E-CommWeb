<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cart_id = $_POST['cart_id'];
    $new_quantity = $_POST['quantity'];
    
    if (!empty($cart_id) && !empty($new_quantity)) {
        // Update quantity in cart table
        $query = "UPDATE cart SET qty = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $new_quantity, $cart_id);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => $stmt->error]);
        }
        
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Missing parameters']);
    }
}

$conn->close();
?> 