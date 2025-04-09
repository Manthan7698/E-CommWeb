<?php
include 'config.php';

// Get the search query
$search = isset($_GET['q']) ? $_GET['q'] : '';

// Debug: Log the search query
error_log("Search query: " . $search);

// Prepare the search query
$query = "SELECT id, product_name, product_brand, product_img FROM products 
          WHERE product_name LIKE ? OR product_brand LIKE ? OR product_details LIKE ?
          LIMIT 5";
$stmt = $conn->prepare($query);
$searchTerm = "%$search%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Return JSON response
header('Content-Type: application/json');
$suggestions = [];

while ($row = $result->fetch_assoc()) {
    $suggestions[] = [
        'id' => $row['id'],
        'name' => $row['product_name'],
        'brand' => $row['product_brand'],
        'image' => $row['product_img']
    ];
}

// Debug: Log the number of suggestions found
error_log("Found " . count($suggestions) . " suggestions for query: " . $search);

echo json_encode($suggestions); 