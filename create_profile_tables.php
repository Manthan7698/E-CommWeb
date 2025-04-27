<?php
include 'config.php';

// Create user_addresses table
$create_addresses_table = "CREATE TABLE IF NOT EXISTS user_addresses (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    address_type ENUM('shipping', 'billing') NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    country VARCHAR(100) NOT NULL,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create wishlist table
$create_wishlist_table = "CREATE TABLE IF NOT EXISTS wishlist (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create saved_carts table
$create_saved_carts_table = "CREATE TABLE IF NOT EXISTS saved_carts (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    cart_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create saved_cart_items table
$create_saved_cart_items_table = "CREATE TABLE IF NOT EXISTS saved_cart_items (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    saved_cart_id INT(11) NOT NULL,
    product_id INT(11) NOT NULL,
    quantity INT(11) NOT NULL,
    FOREIGN KEY (saved_cart_id) REFERENCES saved_carts(id)
)";

// Create payment_methods table
$create_payment_methods_table = "CREATE TABLE IF NOT EXISTS payment_methods (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    payment_type ENUM('credit_card', 'debit_card', 'paypal', 'upi') NOT NULL,
    card_number VARCHAR(20),
    card_holder_name VARCHAR(100),
    expiry_date DATE,
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create subscriptions table
$create_subscriptions_table = "CREATE TABLE IF NOT EXISTS subscriptions (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    plan_name VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE,
    status ENUM('active', 'cancelled', 'expired') NOT NULL,
    auto_renew BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create reward_points table
$create_reward_points_table = "CREATE TABLE IF NOT EXISTS reward_points (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    points INT(11) NOT NULL DEFAULT 0,
    earned_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expiry_date DATE,
    status ENUM('active', 'expired', 'redeemed') NOT NULL DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create coupons table
$create_coupons_table = "CREATE TABLE IF NOT EXISTS coupons (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    code VARCHAR(50) NOT NULL,
    discount_type ENUM('percentage', 'fixed') NOT NULL,
    discount_value DECIMAL(10,2) NOT NULL,
    min_purchase DECIMAL(10,2),
    expiry_date DATE,
    status ENUM('active', 'used', 'expired') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create notification_preferences table
$create_notification_preferences_table = "CREATE TABLE IF NOT EXISTS notification_preferences (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    email_notifications BOOLEAN DEFAULT TRUE,
    sms_notifications BOOLEAN DEFAULT FALSE,
    order_updates BOOLEAN DEFAULT TRUE,
    promotional_offers BOOLEAN DEFAULT TRUE,
    newsletter BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create login_history table
$create_login_history_table = "CREATE TABLE IF NOT EXISTS login_history (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    device_info TEXT,
    login_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('success', 'failed') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create support_tickets table
$create_support_tickets_table = "CREATE TABLE IF NOT EXISTS support_tickets (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'resolved', 'closed') NOT NULL DEFAULT 'open',
    priority ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Create ticket_replies table
$create_ticket_replies_table = "CREATE TABLE IF NOT EXISTS ticket_replies (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    ticket_id INT(11) NOT NULL,
    user_id INT(11) NOT NULL,
    message TEXT NOT NULL,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES support_tickets(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

// Execute all table creation queries
$tables = [
    $create_addresses_table,
    $create_wishlist_table,
    $create_saved_carts_table,
    $create_saved_cart_items_table,
    $create_payment_methods_table,
    $create_subscriptions_table,
    $create_reward_points_table,
    $create_coupons_table,
    $create_notification_preferences_table,
    $create_login_history_table,
    $create_support_tickets_table,
    $create_ticket_replies_table
];

foreach ($tables as $query) {
    if (mysqli_query($conn, $query)) {
        echo "Table created successfully<br>";
    } else {
        echo "Error creating table: " . mysqli_error($conn) . "<br>";
    }
}

mysqli_close($conn);
?> 