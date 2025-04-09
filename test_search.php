<?php
include 'config.php';

// Test the database connection
echo "<h2>Database Connection Test</h2>";
if ($conn) {
    echo "<p style='color:green;'>Database connection successful!</p>";
} else {
    echo "<p style='color:red;'>Database connection failed!</p>";
}

// Test the products table
echo "<h2>Products Table Test</h2>";
$query = "SELECT COUNT(*) as count FROM products";
$result = $conn->query($query);
if ($result) {
    $row = $result->fetch_assoc();
    echo "<p>Total products in database: " . $row['count'] . "</p>";
    
    // Show a sample of products
    echo "<h3>Sample Products:</h3>";
    $query = "SELECT id, product_name, product_brand FROM products LIMIT 5";
    $result = $conn->query($query);
    
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>ID: " . $row['id'] . " - Name: " . $row['product_name'] . " - Brand: " . $row['product_brand'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color:red;'>Error querying products table: " . $conn->error . "</p>";
}

// Test the search functionality
echo "<h2>Search Functionality Test</h2>";
$searchTerm = "cuban";
$query = "SELECT * FROM products WHERE product_name LIKE ? OR product_brand LIKE ? OR product_details LIKE ?";
$stmt = $conn->prepare($query);
$searchTermWithWildcards = "%$searchTerm%";
$stmt->bind_param("sss", $searchTermWithWildcards, $searchTermWithWildcards, $searchTermWithWildcards);
$stmt->execute();
$result = $stmt->get_result();

echo "<p>Search results for '{$searchTerm}':</p>";
if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>ID: " . $row['id'] . " - Name: " . $row['product_name'] . " - Brand: " . $row['product_brand'] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found for '{$searchTerm}'.</p>";
}

// Test the search_suggestions.php functionality
echo "<h2>Search Suggestions Test</h2>";
echo "<p>Testing search_suggestions.php with query '{$searchTerm}':</p>";
$suggestions = file_get_contents("search_suggestions.php?q=" . urlencode($searchTerm));
echo "<pre>";
echo htmlspecialchars($suggestions);
echo "</pre>";
?> 