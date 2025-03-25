<?php
require 'config.php';

if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pcode = $_POST['pcode'];
    $pqty = $_POST['pqty'];

    // Initialize cart session if not set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if the product is already in the cart
    $item_array_id = array_column($_SESSION['cart'], 'pid');
    if (!in_array($pid, $item_array_id)) {
        $item_array = [
            'pid' => $pid,
            'pname' => $pname,
            'pprice' => $pprice,
            'pimage' => $pimage,
            'pqty' => $pqty,
        ];
        $_SESSION['cart'][] = $item_array;
        echo "Item added to the cart!";
    } else {
        echo "Item already in the cart!";
    }
}

// Bag Item Counter


