<?php
require_once 'config.php';

// Function to execute SQL queries safely
function executeQuery($conn, $sql) {
    if (!mysqli_query($conn, $sql)) {
        die("Error executing query: " . mysqli_error($conn));
    }
}

// Start transaction
mysqli_begin_transaction($conn);

try {
    // Create new tables
    $tables = [
        // Categories table
        "CREATE TABLE IF NOT EXISTS `categories` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(100) NOT NULL,
            `slug` VARCHAR(100) NOT NULL,
            `description` TEXT,
            `parent_id` INT(11) DEFAULT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`),
            FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

        // Product images table
        "CREATE TABLE IF NOT EXISTS `product_images` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `product_id` INT(11) NOT NULL,
            `image_path` VARCHAR(255) NOT NULL,
            `is_primary` BOOLEAN DEFAULT FALSE,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

        // Product attributes table
        "CREATE TABLE IF NOT EXISTS `product_attributes` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `product_id` INT(11) NOT NULL,
            `attribute_name` VARCHAR(50) NOT NULL,
            `attribute_value` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

        // Order status history table
        "CREATE TABLE IF NOT EXISTS `order_status_history` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `order_id` INT(11) NOT NULL,
            `status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL,
            `comment` TEXT,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci",

        // Product reviews table
        "CREATE TABLE IF NOT EXISTS `product_reviews` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `product_id` INT(11) NOT NULL,
            `user_id` INT(11) NOT NULL,
            `rating` INT(1) NOT NULL CHECK (rating >= 1 AND rating <= 5),
            `review_text` TEXT,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
            FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci"
    ];

    // Execute create table queries
    foreach ($tables as $sql) {
        executeQuery($conn, $sql);
    }

    // Modify existing tables
    $alterQueries = [
        // Add new columns to products table
        "ALTER TABLE `products` 
         ADD COLUMN IF NOT EXISTS `category_id` INT(11) AFTER `id`,
         ADD COLUMN IF NOT EXISTS `discount_price` DECIMAL(10,2) DEFAULT NULL AFTER `product_price`,
         ADD COLUMN IF NOT EXISTS `stock` INT(11) DEFAULT 0 AFTER `product_details`,
         ADD COLUMN IF NOT EXISTS `status` ENUM('active', 'inactive', 'out_of_stock') DEFAULT 'active' AFTER `stock`,
         ADD COLUMN IF NOT EXISTS `is_featured` BOOLEAN DEFAULT FALSE AFTER `status`,
         ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `is_featured`,
         ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`,
         ADD FOREIGN KEY IF NOT EXISTS (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL",

        // Add new columns to users table
        "ALTER TABLE `users` 
         ADD COLUMN IF NOT EXISTS `username` VARCHAR(50) DEFAULT NULL AFTER `password`,
         ADD COLUMN IF NOT EXISTS `phone` VARCHAR(20) DEFAULT NULL AFTER `username`,
         ADD COLUMN IF NOT EXISTS `profile_picture` VARCHAR(255) DEFAULT 'default.jpg' AFTER `phone`,
         ADD COLUMN IF NOT EXISTS `google_id` VARCHAR(255) DEFAULT NULL AFTER `profile_picture`,
         ADD COLUMN IF NOT EXISTS `is_google_user` TINYINT(1) DEFAULT 0 AFTER `google_id`,
         ADD COLUMN IF NOT EXISTS `role` ENUM('user', 'admin', 'staff') DEFAULT 'user' AFTER `is_google_user`,
         ADD COLUMN IF NOT EXISTS `status` ENUM('active', 'inactive', 'banned') DEFAULT 'active' AFTER `role`,
         ADD COLUMN IF NOT EXISTS `last_login` TIMESTAMP NULL AFTER `status`,
         ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `last_login`,
         ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`,
         ADD UNIQUE KEY IF NOT EXISTS `username` (`username`),
         ADD UNIQUE KEY IF NOT EXISTS `google_id` (`google_id`)",

        // Add new columns to orders table
        "ALTER TABLE `orders` 
         ADD COLUMN IF NOT EXISTS `shipping_address_id` INT(11) AFTER `phone`,
         ADD COLUMN IF NOT EXISTS `billing_address_id` INT(11) AFTER `shipping_address_id`,
         ADD COLUMN IF NOT EXISTS `order_status` ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending' AFTER `amount_paid`,
         ADD COLUMN IF NOT EXISTS `payment_status` ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending' AFTER `order_status`,
         ADD COLUMN IF NOT EXISTS `tracking_number` VARCHAR(100) AFTER `payment_status`,
         ADD COLUMN IF NOT EXISTS `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `tracking_number`,
         ADD COLUMN IF NOT EXISTS `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`,
         ADD FOREIGN KEY IF NOT EXISTS (`shipping_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL,
         ADD FOREIGN KEY IF NOT EXISTS (`billing_address_id`) REFERENCES `user_addresses` (`id`) ON DELETE SET NULL"
    ];

    // Execute alter table queries
    foreach ($alterQueries as $sql) {
        executeQuery($conn, $sql);
    }

    // Commit transaction
    mysqli_commit($conn);
    echo "Database schema updated successfully!";

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    die("Error updating schema: " . $e->getMessage());
}

// Close connection
mysqli_close($conn);
?> 