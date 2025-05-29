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
    $pbrand = $_POST['product_brand'];
    $pdetails = $_POST['product_details'];
    $psize = $_POST['product_size'];

    // Validate input
    if (!empty($pid) && !empty($pname) && !empty($pprice) && !empty($pimg) && !empty($qty) && !empty($pcode) && !empty($pbrand) && !empty($pdetails)) {
        // Check if product is in stock
        $stock_query = "SELECT stock FROM products WHERE id = ?";
        $stock_stmt = $conn->prepare($stock_query);
        $stock_stmt->bind_param("i", $pid);
        $stock_stmt->execute();
        $stock_result = $stock_stmt->get_result();
        $stock_row = $stock_result->fetch_assoc();
        
        if ($stock_row['stock'] <= 0) {
            echo json_encode(['success' => false, 'message' => 'Sorry, this product is out of stock']);
            exit;
        }

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
        $query = "INSERT INTO cart (id, product_name, product_price, product_img, qty, product_code, product_brand, product_details, product_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("isssissss", $pid, $pname, $pprice, $pimg, $qty, $pcode, $pbrand, $pdetails, $psize);

        // Execute the query and check for success
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Product added to your bag']);
        } else {
            error_log("Error adding product to cart: " . $stmt->error);
            echo json_encode(['success' => false, 'message' => 'Error adding product to bag: ' . $stmt->error]);
        }

        // Close the statements
        $stmt->close();
        $check_stmt->close();
    } else {
        error_log("Missing required fields in add_to_cart.php");
        echo json_encode(['success' => false, 'message' => 'All fields are required']);
    }
}

// Close the database connection
$conn->close();
