<?php
include 'config.php';

// Modify users table to add new fields
$sql = "ALTER TABLE users 
        ADD COLUMN IF NOT EXISTS username VARCHAR(50) UNIQUE,
        ADD COLUMN IF NOT EXISTS phone VARCHAR(20),
        ADD COLUMN IF NOT EXISTS profile_picture VARCHAR(255) DEFAULT 'default.jpg',
        ADD COLUMN IF NOT EXISTS google_id VARCHAR(255) NULL UNIQUE,
        ADD COLUMN IF NOT EXISTS is_google_user TINYINT(1) DEFAULT 0";

if (mysqli_query($conn, $sql)) {
    echo "Users table updated successfully<br>";
} else {
    echo "Error updating users table: " . mysqli_error($conn) . "<br>";
}

// Create user_addresses table
$sql = "CREATE TABLE IF NOT EXISTS user_addresses (
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if (mysqli_query($conn, $sql)) {
    echo "User addresses table created successfully";
} else {
    echo "Error creating user_addresses table: " . mysqli_error($conn);
}

mysqli_close($conn);
?> 