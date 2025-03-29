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
        // Check if product already exists in cart
        $check_query = "SELECT * FROM cart WHERE id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("i", $pid);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'Product is already in your bag']);
            exit;
        }

        // Prepare SQL query to insert into the cart table
        $query = "INSERT INTO cart (id, product_name, product_price, product_img, qty, product_code) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssis", $pid, $pname, $pprice, $pimg, $qty, $pcode);

        // Execute the query and check for success
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product added to your bag']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error adding product to bag']);
        }

        // Close the statements
        $stmt->close();
        $check_stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
    }
}

// Close the database connection
$conn->close();
