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
        
        // Update product stock
        foreach ($cart_items as $item) {
            // Get current stock
            $stock_query = $conn->prepare("SELECT stock FROM products WHERE id = ?");
            $stock_query->bind_param("i", $item['id']);
            $stock_query->execute();
            $stock_result = $stock_query->get_result();
            $stock_data = $stock_result->fetch_assoc();
            
            if (!$stock_data) {
                throw new Exception("Product with ID {$item['id']} not found");
            }
            
            $current_stock = $stock_data['stock'];
            
            // Calculate new stock
            $new_stock = $current_stock - $item['qty'];
            
            if ($new_stock < 0) {
                throw new Exception("Insufficient stock for product ID {$item['id']}");
            }
            
            // Update stock
            $update_stock = $conn->prepare("UPDATE products SET stock = ?, product_status = CASE WHEN ? <= 0 THEN 'out_of_stock' ELSE product_status END WHERE id = ?");
            $update_stock->bind_param("iii", $new_stock, $new_stock, $item['id']);
            $update_stock->execute();
        }
        
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