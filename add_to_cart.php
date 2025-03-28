<?php
include 'config.php';

// Check if the request is a POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve product details from the POST request
    $pid = $_POST['id'];
    $pname = $_POST['product_name'];
    $pprice = $_POST['product_price'];
    $pimg = $_POST['product_img'];
    $qty = $_POST['quantity'];
    $pcode = $_POST['product_code'];

    // Validate input
    if (!empty($pid) && !empty($pname) && !empty($pprice) && !empty($pimg) && !empty($qty) && !empty($pcode)) {
        // Prepare SQL query to insert into the cart table
        $query = "INSERT INTO cart (id, product_name, product_price, product_img, qty, product_code) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssis", $pid, $pname, $pprice, $pimg, $qty, $pcode);

        // Execute the query and check for success
        if ($stmt->execute()) {
            echo "Product added to cart successfully.";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

// Close the database connection
$conn->close();
