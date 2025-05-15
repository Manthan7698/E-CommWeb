<?php
session_start();
include 'config.php';

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Initialize variables
$productName = $productPrice = $productImage = $productDescription = "";
$errors = [];

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $productName = mysqli_real_escape_string($conn, $_POST['product_name']);
    $productPrice = mysqli_real_escape_string($conn, $_POST['product_price']);
    $productImage = mysqli_real_escape_string($conn, $_POST['product_image']);
    $productDescription = mysqli_real_escape_string($conn, $_POST['product_description']);

    // Validate input
    if (empty($productName)) {
        $errors[] = "Product name is required";
    }
    if (empty($productPrice)) {
        $errors[] = "Product price is required";
    }
    if (empty($productImage)) {
        $errors[] = "Product image URL is required";
    }

    // If no errors, insert product into database
    if (empty($errors)) {
        $sql = "INSERT INTO products (name, price, image, description) VALUES ('$productName', '$productPrice', '$productImage', '$productDescription')";
        if (mysqli_query($conn, $sql)) {
            echo "Product added successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel - Add Product</h1>
    <?php
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }
    ?>
    <form action="admin_panel.php" method="post">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($productName); ?>"><br>

        <label for="product_price">Product Price:</label>
        <input type="text" id="product_price" name="product_price" value="<?php echo htmlspecialchars($productPrice); ?>"><br>

        <label for="product_image">Product Image URL:</label>
        <input type="text" id="product_image" name="product_image" value="<?php echo htmlspecialchars($productImage); ?>"><br>

        <label for="product_description">Product Description:</label>
        <textarea id="product_description" name="product_description"><?php echo htmlspecialchars($productDescription); ?></textarea><br>

        <input type="submit" value="Add Product">
    </form>
</body>
</html>