<?php
include 'config.php';

// Get total from cart table
$query = "SELECT SUM(product_price * qty) as total FROM cart";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$total = $row['total'] ?? 0;

// Return the total as JSON
header('Content-Type: application/json');
echo json_encode(['total' => floatval($total)]);
?> 