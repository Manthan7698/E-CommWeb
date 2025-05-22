<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if orders table exists, if not create it
$check_table = "SHOW TABLES LIKE 'orders'";
$table_exists = mysqli_query($conn, $check_table);

if (mysqli_num_rows($table_exists) == 0) {
    // Create orders table
    $create_orders_table = "CREATE TABLE orders (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) NOT NULL,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        total_amount DECIMAL(10,2) NOT NULL,
        status VARCHAR(50) NOT NULL DEFAULT 'Pending',
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
    
    mysqli_query($conn, $create_orders_table);
    
    // Create order_items table
    $create_order_items_table = "CREATE TABLE order_items (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        order_id INT(11) NOT NULL,
        product_id INT(11) NOT NULL,
        product_name VARCHAR(255) NOT NULL,
        product_price DECIMAL(10,2) NOT NULL,
        quantity INT(11) NOT NULL,
        size VARCHAR(50),
        FOREIGN KEY (order_id) REFERENCES orders(id)
    )";
    
    mysqli_query($conn, $create_order_items_table);
}

// Get user's orders
// $orders_query = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY order_date DESC";
$orders_query = "SELECT * FROM orders WHERE user_id = $user_id ";
$orders_result = mysqli_query($conn, $orders_query);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <title>My Orders</title>
  <style>
    .orders-container {
      max-width: 1000px;
      margin: 50px auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    
    .orders-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .orders-header h1 {
      color: #333;
      margin-bottom: 10px;
    }
    
    .no-orders {
      text-align: center;
      padding: 50px 0;
      color: #666;
    }
    
    .order-card {
      border: 1px solid #eee;
      border-radius: 8px;
      margin-bottom: 20px;
      overflow: hidden;
    }
    
    .order-header {
      background-color: #f9f9f9;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #eee;
    }
    
    .order-id {
      font-weight: 600;
      color: #333;
    }
    
    .order-date {
      color: #666;
    }
    
    .order-status {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 500;
    }
    
    .status-pending {
      background-color: #fff8e1;
      color: #ffa000;
    }
    
    .status-processing {
      background-color: #e3f2fd;
      color: #1976d2;
    }
    
    .status-completed {
      background-color: #e8f5e9;
      color: #388e3c;
    }
    
    .status-cancelled {
      background-color: #ffebee;
      color: #d32f2f;
    }
    
    .order-items {
      padding: 20px;
    }
    
    .order-item {
      display: flex;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid #f5f5f5;
    }
    
    .order-item:last-child {
      border-bottom: none;
    }
    
    .item-details {
      flex-grow: 1;
    }
    
    .item-name {
      font-weight: 500;
      color: #333;
      margin-bottom: 5px;
    }
    
    .item-meta {
      font-size: 14px;
      color: #666;
    }
    
    .item-price {
      font-weight: 500;
      color: #333;
    }
    
    .order-footer {
      background-color: #f9f9f9;
      padding: 15px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .order-total {
      font-weight: 600;
      color: #333;
    }
    
    .view-details-btn {
      background-color: #088178;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      transition: background-color 0.3s;
    }
    
    .view-details-btn:hover {
      background-color: #066963;
    }
  </style>
</head>

<body>
  <?php include 'header.php'; ?>

  <div class="orders-container">
    <div class="orders-header">
      <h1>My Orders</h1>
      <p>View your order history</p>
    </div>
    
    <?php if (mysqli_num_rows($orders_result) == 0): ?>
      <div class="no-orders">
        <i class="fa-solid fa-box-open" style="font-size: 50px; margin-bottom: 20px;"></i>
        <h2>No Orders Yet</h2>
        <p>You haven't placed any orders yet. Start shopping to see your orders here.</p>
        <a href="shop.php" class="btn-update" style="display: inline-block; margin-top: 20px;">Start Shopping</a>
      </div>
    <?php else: ?>
      <?php while ($order = mysqli_fetch_assoc($orders_result)): ?>
        <div class="order-card">
          <div class="order-header">
            <div class="order-id">Order #<?php echo $order['id']; ?></div>
            <div class="order-date"><?php echo date('F j, Y', strtotime($order['order_date'])); ?></div>
            <div class="order-status status-<?php echo strtolower($order['status']); ?>"><?php echo $order['status']; ?></div>
          </div>
          
          <div class="order-items">
            <?php
            // Get order items
            $order_id = $order['id'];
            $items_query = "SELECT * FROM order_items WHERE order_id = $order_id";
            $items_result = mysqli_query($conn, $items_query);
            
            while ($item = mysqli_fetch_assoc($items_result)):
            ?>
              <div class="order-item">
                <div class="item-details">
                  <div class="item-name"><?php echo $item['product_name']; ?></div>
                  <div class="item-meta">
                    Quantity: <?php echo $item['quantity']; ?>
                    <?php if (!empty($item['size'])): ?>
                      | Size: <?php echo $item['size']; ?>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="item-price">$<?php echo number_format($item['product_price'], 2); ?></div>
              </div>
            <?php endwhile; ?>
          </div>
          
          <div class="order-footer">
            <div class="order-total">Total: $<?php echo number_format($order['total_amount'], 2); ?></div>
            <button class="view-details-btn">View Details</button>
          </div>
        </div>
      <?php endwhile; ?>
    <?php endif; ?>
  </div>

  <?php include 'footer.php'; ?>
</body>

</html> 