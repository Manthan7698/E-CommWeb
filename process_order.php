<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    $country = $_POST['country'];
    $pmode = $_POST['payment'];
    
    // Get user_id from session (assuming you store it in session when user logs in)
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
    
    // Get cart items
    $cart_items = [];
    $total_amount = 0;
    
    $stmt = $conn->prepare('SELECT * FROM cart');
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $total_amount += ($row['product_price'] * $row['qty']);
    }
    
    // Convert cart items to string for products field
    $products_json = json_encode($cart_items);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Insert into orders table
        $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, phone, address, pmode, products, amount_paid, order_status, payment_status) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending')");
        
        $stmt->bind_param("isssssss", 
            $user_id,
            $fullname,
            $email,
            $phone,
            $address,
            $pmode,
            $products_json,
            $total_amount
        );
        
        $stmt->execute();
        
        // Get the inserted order ID
        $order_id = $conn->insert_id;
        
        // Clear the cart
        $conn->query("TRUNCATE TABLE cart");
        
        // Commit transaction
        $conn->commit();
        
        // Redirect to success page
        header("Location: payment_status.php?order_id=" . $order_id);
        exit();
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error processing order: " . $e->getMessage();
    }
} else {
    // If not POST request, redirect to checkout
    header("Location: checkout.php");
    exit();
}
?> 