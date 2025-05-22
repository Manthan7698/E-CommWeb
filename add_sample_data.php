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
    // 1. First, create categories (no dependencies)
    $categories = [
        ['name' => 'Men\'s Clothing', 'slug' => 'mens-clothing', 'description' => 'Men\'s fashion and apparel'],
        ['name' => 'Women\'s Clothing', 'slug' => 'womens-clothing', 'description' => 'Women\'s fashion and apparel'],
        ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Fashion accessories for all'],
        ['name' => 'Footwear', 'slug' => 'footwear', 'description' => 'Shoes and boots for all occasions']
    ];

    foreach ($categories as $category) {
        $sql = "INSERT INTO categories (name, slug, description) VALUES (
            '" . mysqli_real_escape_string($conn, $category['name']) . "',
            '" . mysqli_real_escape_string($conn, $category['slug']) . "',
            '" . mysqli_real_escape_string($conn, $category['description']) . "'
        )";
        executeQuery($conn, $sql);
    }

    // 2. Create users (no dependencies)
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (name, email, password, username, role, status) VALUES (
        'Admin User',
        'admin@example.com',
        '$adminPassword',
        'admin',
        'admin',
        'active'
    )";
    executeQuery($conn, $sql);

    $userPassword = password_hash('user123', PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (name, email, password, username, role, status) VALUES (
        'Regular User',
        'user@example.com',
        '$userPassword',
        'user',
        'user',
        'active'
    )";
    executeQuery($conn, $sql);

    // 3. Create products (depends on categories)
    $products = [
        [
            'category_id' => 1,
            'product_brand' => 'Nike',
            'product_name' => 'Men\'s Running Shoes',
            'product_price' => 89.99,
            'discount_price' => 79.99,
            'product_code' => 'NIK001',
            'product_details' => 'Lightweight running shoes with superior comfort',
            'stock' => 50,
            'status' => 'active',
            'is_featured' => true
        ],
        [
            'category_id' => 2,
            'product_brand' => 'Adidas',
            'product_name' => 'Women\'s Yoga Pants',
            'product_price' => 49.99,
            'discount_price' => null,
            'product_code' => 'ADI001',
            'product_details' => 'Comfortable yoga pants for maximum flexibility',
            'stock' => 30,
            'status' => 'active',
            'is_featured' => false
        ]
    ];

    foreach ($products as $product) {
        $sql = "INSERT INTO products (
            category_id, product_brand, product_name, product_price, discount_price,
            product_code, product_details, stock, status, is_featured
        ) VALUES (
            " . (int)$product['category_id'] . ",
            '" . mysqli_real_escape_string($conn, $product['product_brand']) . "',
            '" . mysqli_real_escape_string($conn, $product['product_name']) . "',
            " . (float)$product['product_price'] . ",
            " . ($product['discount_price'] ? (float)$product['discount_price'] : "NULL") . ",
            '" . mysqli_real_escape_string($conn, $product['product_code']) . "',
            '" . mysqli_real_escape_string($conn, $product['product_details']) . "',
            " . (int)$product['stock'] . ",
            '" . mysqli_real_escape_string($conn, $product['status']) . "',
            " . ($product['is_featured'] ? 1 : 0) . "
        )";
        executeQuery($conn, $sql);
    }

    // 4. Create product images (depends on products)
    $productImages = [
        ['product_id' => 1, 'image_path' => 'img/products/nike-running-1.jpg', 'is_primary' => true],
        ['product_id' => 1, 'image_path' => 'img/products/nike-running-2.jpg', 'is_primary' => false],
        ['product_id' => 2, 'image_path' => 'img/products/adidas-yoga-1.jpg', 'is_primary' => true],
        ['product_id' => 2, 'image_path' => 'img/products/adidas-yoga-2.jpg', 'is_primary' => false]
    ];

    foreach ($productImages as $image) {
        $sql = "INSERT INTO product_images (product_id, image_path, is_primary) VALUES (
            " . (int)$image['product_id'] . ",
            '" . mysqli_real_escape_string($conn, $image['image_path']) . "',
            " . ($image['is_primary'] ? 1 : 0) . "
        )";
        executeQuery($conn, $sql);
    }

    // 5. Create product attributes (depends on products)
    $productAttributes = [
        ['product_id' => 1, 'attribute_name' => 'Size', 'attribute_value' => '42'],
        ['product_id' => 1, 'attribute_name' => 'Color', 'attribute_value' => 'Black'],
        ['product_id' => 2, 'attribute_name' => 'Size', 'attribute_value' => 'M'],
        ['product_id' => 2, 'attribute_name' => 'Color', 'attribute_value' => 'Blue']
    ];

    foreach ($productAttributes as $attr) {
        $sql = "INSERT INTO product_attributes (product_id, attribute_name, attribute_value) VALUES (
            " . (int)$attr['product_id'] . ",
            '" . mysqli_real_escape_string($conn, $attr['attribute_name']) . "',
            '" . mysqli_real_escape_string($conn, $attr['attribute_value']) . "'
        )";
        executeQuery($conn, $sql);
    }

    // 6. Create user addresses (depends on users)
    $addresses = [
        [
            'user_id' => 2,
            'address_type' => 'shipping',
            'full_name' => 'Regular User',
            'phone' => '1234567890',
            'address_line1' => '123 Main St',
            'address_line2' => 'Apt 4B',
            'city' => 'New York',
            'state' => 'NY',
            'zip_code' => '10001',
            'country' => 'USA',
            'is_default' => true
        ]
    ];

    foreach ($addresses as $address) {
        $sql = "INSERT INTO user_addresses (
            user_id, address_type, full_name, phone, address_line1, address_line2,
            city, state, zip_code, country, is_default
        ) VALUES (
            " . (int)$address['user_id'] . ",
            '" . mysqli_real_escape_string($conn, $address['address_type']) . "',
            '" . mysqli_real_escape_string($conn, $address['full_name']) . "',
            '" . mysqli_real_escape_string($conn, $address['phone']) . "',
            '" . mysqli_real_escape_string($conn, $address['address_line1']) . "',
            '" . mysqli_real_escape_string($conn, $address['address_line2']) . "',
            '" . mysqli_real_escape_string($conn, $address['city']) . "',
            '" . mysqli_real_escape_string($conn, $address['state']) . "',
            '" . mysqli_real_escape_string($conn, $address['zip_code']) . "',
            '" . mysqli_real_escape_string($conn, $address['country']) . "',
            " . ($address['is_default'] ? 1 : 0) . "
        )";
        executeQuery($conn, $sql);
    }

    // 7. Create orders (depends on users and addresses)
    $sql = "INSERT INTO orders (
        user_id, name, email, phone, shipping_address_id, billing_address_id,
        pmode, amount_paid, order_status, payment_status
    ) VALUES (
        2,
        'Regular User',
        'user@example.com',
        '1234567890',
        1,
        1,
        'credit_card',
        89.99,
        'pending',
        'pending'
    )";
    executeQuery($conn, $sql);

    // 8. Create order items (depends on orders and products)
    $sql = "INSERT INTO order_items (
        order_id, product_id, product_name, product_price, quantity, size
    ) VALUES (
        1,
        1,
        'Men\'s Running Shoes',
        89.99,
        1,
        '42'
    )";
    executeQuery($conn, $sql);

    // 9. Create order status history (depends on orders)
    $sql = "INSERT INTO order_status_history (order_id, status, comment) VALUES (
        1,
        'pending',
        'Order placed successfully'
    )";
    executeQuery($conn, $sql);

    // 10. Create product reviews (depends on products and users)
    $reviews = [
        [
            'product_id' => 1,
            'user_id' => 2,
            'rating' => 5,
            'review_text' => 'Great shoes! Very comfortable for long runs.'
        ],
        [
            'product_id' => 2,
            'user_id' => 2,
            'rating' => 4,
            'review_text' => 'Good quality yoga pants, but a bit pricey.'
        ]
    ];

    foreach ($reviews as $review) {
        $sql = "INSERT INTO product_reviews (product_id, user_id, rating, review_text) VALUES (
            " . (int)$review['product_id'] . ",
            " . (int)$review['user_id'] . ",
            " . (int)$review['rating'] . ",
            '" . mysqli_real_escape_string($conn, $review['review_text']) . "'
        )";
        executeQuery($conn, $sql);
    }

    // Commit transaction
    mysqli_commit($conn);
    echo "Sample data added successfully!";

} catch (Exception $e) {
    // Rollback transaction on error
    mysqli_rollback($conn);
    die("Error adding sample data: " . $e->getMessage());
}

// Close connection
mysqli_close($conn);
?> 