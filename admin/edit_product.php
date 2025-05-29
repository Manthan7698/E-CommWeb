<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get product ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: products.php');
    exit();
}

$product_id = (int)$_GET['id'];

// Get product data
$sql = "SELECT p.* FROM products p WHERE p.id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $product_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

// If product not found, redirect
if (!$product) {
    header('Location: products.php');
    exit();
}

// Get all categories
$sql = "SELECT * FROM categories ORDER BY name";
$categories = mysqli_query($conn, $sql);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Enable error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    // Create debug log file
    $debug_log = fopen("debug.log", "a");
    fwrite($debug_log, "\n\n=== Product Update Debug Log ===\n");
    fwrite($debug_log, "Time: " . date('Y-m-d H:i:s') . "\n");
    
    // Log POST data
    fwrite($debug_log, "POST Data:\n");
    fwrite($debug_log, print_r($_POST, true));
    
    $category_id = (int)$_POST['category_id'];
    $product_brand = mysqli_real_escape_string($conn, $_POST['product_brand']);
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $product_price = (float)$_POST['product_price'];
    $discount_price = !empty($_POST['discount_price']) ? (float)$_POST['discount_price'] : null;
    $product_code = mysqli_real_escape_string($conn, $_POST['product_code']);
    $product_details = mysqli_real_escape_string($conn, $_POST['product_details']);
    $stock = (int)$_POST['stock'];
    $selected_status = mysqli_real_escape_string($conn, $_POST['product_status']);

    if ($stock === 0) {
        $product_status = 'out_of_stock';
    } else {
        $product_status = ($selected_status === 'inactive') ? 'inactive' : 'active';
    }
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    // Log processed data
    fwrite($debug_log, "\nProcessed Data:\n");
    fwrite($debug_log, "Product Status: " . $product_status . "\n");
    
    // Handle image upload
    $product_image = $product['product_img']; // Keep existing image by default
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $filename = $_FILES['product_image']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            $upload_path = '../img/products/' . $new_filename;

            if (move_uploaded_file($_FILES['product_image']['tmp_name'], $upload_path)) {
                // Delete old image if exists
                if (!empty($product['product_img']) && file_exists('../' . $product['product_img'])) {
                    unlink('../' . $product['product_img']);
                }
                $product_image = 'img/products/' . $new_filename;
            }
        }
    }

    // Update product
    $sql = "UPDATE products SET 
            category_id = ?,
            product_brand = ?,
            product_name = ?,
            product_price = ?,
            discount_price = ?,
            product_code = ?,
            product_details = ?,
            product_img = ?,
            stock = ?,
            product_status = ?,
            is_featured = ?
            WHERE id = ?";

    // Log the SQL query
    fwrite($debug_log, "\nSQL Query:\n" . $sql . "\n");
    fwrite($debug_log, "Parameters:\n");
    fwrite($debug_log, "category_id: $category_id\n");
    fwrite($debug_log, "product_brand: $product_brand\n");
    fwrite($debug_log, "product_name: $product_name\n");
    fwrite($debug_log, "product_price: $product_price\n");
    fwrite($debug_log, "discount_price: $discount_price\n");
    fwrite($debug_log, "product_code: $product_code\n");
    fwrite($debug_log, "product_details: $product_details\n");
    fwrite($debug_log, "product_img: $product_image\n");
    fwrite($debug_log, "stock: $stock\n");
    fwrite($debug_log, "product_status: $product_status\n");
    fwrite($debug_log, "is_featured: $is_featured\n");
    fwrite($debug_log, "product_id: $product_id\n");

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        fwrite($debug_log, "\nPrepare Error: " . mysqli_error($conn) . "\n");
        $error = "Error preparing statement: " . mysqli_error($conn);
    } else {
        mysqli_stmt_bind_param($stmt, "issdssssssii", 
            $category_id, $product_brand, $product_name, $product_price, $discount_price,
            $product_code, $product_details, $product_image, $stock, $product_status, $is_featured,
            $product_id
        );

        if (!mysqli_stmt_execute($stmt)) {
            fwrite($debug_log, "\nExecute Error: " . mysqli_stmt_error($stmt) . "\n");
            $error = "Error updating product: " . mysqli_stmt_error($stmt);
        } else {
            fwrite($debug_log, "\nUpdate successful!\n");
            
            // Verify the update
            $verify_sql = "SELECT * FROM products WHERE id = ?";
            $verify_stmt = mysqli_prepare($conn, $verify_sql);
            mysqli_stmt_bind_param($verify_stmt, "i", $product_id);
            mysqli_stmt_execute($verify_stmt);
            $result = mysqli_stmt_get_result($verify_stmt);
            $updated_product = mysqli_fetch_assoc($result);
            
            fwrite($debug_log, "\nVerification after update:\n");
            fwrite($debug_log, print_r($updated_product, true));
            
            header('Location: products.php');
            exit();
        }
    }
    
    fclose($debug_log);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - Cara Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        :root {
            --primary-color: #088178;
            --secondary-color: #e3e6f3;
            --text-color: #1a1a1a;
            --light-text: #465b52;
            --white: #ffffff;
            --danger: #dc3545;
            --success: #28a745;
        }

        body {
            background: #f5f5f5;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background: var(--white);
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 20px 0;
            text-align: center;
            border-bottom: 1px solid #eee;
        }

        .sidebar-header h2 {
            color: var(--primary-color);
            font-size: 24px;
        }

        .nav-menu {
            margin-top: 30px;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-color);
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            background: var(--primary-color);
            color: var(--white);
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: var(--text-color);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .btn-danger {
            background: var(--danger);
            color: var(--white);
        }

        .btn-success {
            background: var(--success);
            color: var(--white);
        }

        /* Form Styles */
        .form-container {
            background: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 auto;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
        }

        .form-control {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 5px rgba(8, 129, 120, 0.3);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .checkbox-group input {
            margin-right: 5px;
        }

        .form-actions {
            margin-top: 30px;
            text-align: right;
        }

    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>Cara Admin</h2>
            </div>
            <nav class="nav-menu">
                <div class="nav-item">
                    <a href="dashboard.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="products.php" class="nav-link active">
                        <i class="fas fa-box"></i>
                        Products
                    </a>
                </div>
                <div class="nav-item">
                    <a href="categories.php" class="nav-link">
                        <i class="fas fa-tags"></i>
                        Categories
                    </a>
                </div>
                <div class="nav-item">
                    <a href="orders.php" class="nav-link">
                        <i class="fas fa-shopping-cart"></i>
                        Orders
                    </a>
                </div>
                <div class="nav-item">
                    <a href="users.php" class="nav-link">
                        <i class="fas fa-users"></i>
                        Users
                    </a>
                </div>
                <div class="nav-item">
                    <a href="reviews.php" class="nav-link">
                        <i class="fas fa-star"></i>
                        Reviews
                    </a>
                </div>
                <div class="nav-item">
                    <a href="../logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Edit Product</h1>
                <a href="products.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Products
                </a>
            </div>

            <div class="form-container">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                <?php while ($category = mysqli_fetch_assoc($categories)) { ?>
                                    <option value="<?php echo $category['id']; ?>" <?php echo $product['category_id'] == $category['id'] ? 'selected' : ''; ?> >
                                        <?php echo htmlspecialchars($category['name']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="product_brand">Brand</label>
                            <input type="text" name="product_brand" id="product_brand" class="form-control" value="<?php echo htmlspecialchars($product['product_brand']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="product_price">Price</label>
                            <input type="number" name="product_price" id="product_price" class="form-control" step="0.01" value="<?php echo htmlspecialchars($product['product_price']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="discount_price">Discount Price (Optional)</label>
                            <input type="number" name="discount_price" id="discount_price" class="form-control" step="0.01" value="<?php echo htmlspecialchars($product['discount_price']); ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="product_code">Product Code</label>
                            <input type="text" name="product_code" id="product_code" class="form-control" value="<?php echo htmlspecialchars($product['product_code']); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="stock">Stock</label>
                            <input type="number" name="stock" id="stock" class="form-control" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="product_details">Product Details</label>
                        <textarea name="product_details" id="product_details" class="form-control" required><?php echo htmlspecialchars($product['product_details']); ?></textarea>
                    </div>

                     <div class="form-group">
                        <label for="product_image">Product Image</label>
                        <?php if (!empty($product['product_image'])) { ?>
                            <p>Current Image: <img src="../<?php echo htmlspecialchars($product['product_image']); ?>" alt="Current Product Image" style="width: 50px; height: 50px; object-fit: cover;"></p>
                        <?php } else { ?>
                            <p>No current image.</p>
                        <?php } ?>
                        <input type="file" name="product_image" id="product_image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-row">
                         <div class="form-group">
                            <label for="product_status">Status</label>
                            <select name="product_status" id="product_status" class="form-control" required>
                                <option value="active" <?php echo $product['product_status'] == 'active' ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo $product['product_status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                                <option value="out_of_stock" <?php echo $product['product_status'] == 'out_of_stock' ? 'selected' : ''; ?>>Out of Stock</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="is_featured">Featured</label>
                            <div class="checkbox-group">
                                <input type="checkbox" name="is_featured" id="is_featured" value="1" <?php echo $product['is_featured'] ? 'checked' : ''; ?>> 
                                <label for="is_featured">Mark as Featured</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 