<?php
include 'config.php';

// Get total count of items in cart
$query = "SELECT COUNT(*) as total FROM cart";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total_items = $row['total'];

// Return the count as JSON
header('Content-Type: application/json');
echo json_encode(['count' => $total_items]);
?> 