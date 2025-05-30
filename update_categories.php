<?php
session_start();
require_once 'config.php';

// Function to refresh categories cache
function refreshCategories() {
    global $conn;
    $categories = array();
    
    try {
        $cat_query = "SELECT * FROM categories WHERE status = 'active' ORDER BY parent_id IS NULL DESC, name ASC";
        $cat_result = mysqli_query($conn, $cat_query);
        
        if ($cat_result) {
            while($category = mysqli_fetch_assoc($cat_result)) {
                $categories[] = $category;
            }
            $_SESSION['categories'] = $categories;
            return true;
        }
    } catch (Exception $e) {
        error_log("Error refreshing categories: " . $e->getMessage());
    }
    
    return false;
}

// Refresh categories if requested
if (isset($_GET['refresh'])) {
    refreshCategories();
}

// Return current categories
$categories = isset($_SESSION['categories']) ? $_SESSION['categories'] : array();
echo json_encode($categories);
?> 