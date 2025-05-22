<?php
session_start();
require_once '../config.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

// Get counts for dashboard
$counts = [
    'products' => 0,
    'orders' => 0,
    'users' => 0,
    'reviews' => 0
];

// Get product count
$sql = "SELECT COUNT(*) as count FROM products";
$result = mysqli_query($conn, $sql);
if ($result) {
    $counts['products'] = mysqli_fetch_assoc($result)['count'];
}

// Get order count
$sql = "SELECT COUNT(*) as count FROM orders";
$result = mysqli_query($conn, $sql);
if ($result) {
    $counts['orders'] = mysqli_fetch_assoc($result)['count'];
}

// Get user count
$sql = "SELECT COUNT(*) as count FROM users WHERE role = 'user'";
$result = mysqli_query($conn, $sql);
if ($result) {
    $counts['users'] = mysqli_fetch_assoc($result)['count'];
}

// Get review count
$sql = "SELECT COUNT(*) as count FROM product_reviews";
$result = mysqli_query($conn, $sql);
if ($result) {
    $counts['reviews'] = mysqli_fetch_assoc($result)['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Cara</title>
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

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        } */

        /* Dashboard Cards */
        .dashboard-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            color: var(--light-text);
            font-size: 16px;
        }

        .card-icon {
            width: 40px;
            height: 40px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
        }

        .card-value {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-color);
        }

        /* Recent Activity */
        .recent-activity {
            background: var(--white);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .activity-list {
            list-style: none;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            background: var(--secondary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary-color);
        }

        .activity-details {
            flex: 1;
        }

        .activity-title {
            font-weight: 500;
            color: var(--text-color);
        }

        .activity-time {
            font-size: 12px;
            color: var(--light-text);
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
                    <a href="dashboard.php" class="nav-link active">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="nav-item">
                    <a href="products.php" class="nav-link">
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
                <h1>Dashboard</h1>
                <div class="user-info">
                    <img src="../img/user.png" alt="Admin">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?></span>
                </div>
            </div>

            <!-- Dashboard Cards -->
            <div class="dashboard-cards">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total Products</h3>
                        <div class="card-icon">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <div class="card-value"><?php echo $counts['products']; ?></div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total Orders</h3>
                        <div class="card-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="card-value"><?php echo $counts['orders']; ?></div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total Users</h3>
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="card-value"><?php echo $counts['users']; ?></div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Total Reviews</h3>
                        <div class="card-icon">
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="card-value"><?php echo $counts['reviews']; ?></div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="recent-activity">
                <div class="activity-header">
                    <h2>Recent Activity</h2>
                </div>
                <ul class="activity-list">
                    <?php
                    // Get recent orders
                    $sql = "SELECT o.*, u.name as user_name 
                           FROM orders o 
                           JOIN users u ON o.user_id = u.id 
                           ORDER BY o.created_at DESC 
                           LIMIT 5";
                    $result = mysqli_query($conn, $sql);
                    
                    while ($order = mysqli_fetch_assoc($result)) {
                        echo '<li class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="activity-details">
                                    <div class="activity-title">New order from ' . htmlspecialchars($order['user_name']) . '</div>
                                    <div class="activity-time">' . date('M d, Y H:i', strtotime($order['created_at'])) . '</div>
                                </div>
                            </li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</body>
</html> 