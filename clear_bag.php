<?php
include 'config.php';

// Clear all items from the cart table
$query = "TRUNCATE TABLE cart";
$result = mysqli_query($conn, $query);

if ($result) {
    echo json_encode(['success' => true, 'message' => 'Bag cleared successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error clearing bag']);
}
?> 