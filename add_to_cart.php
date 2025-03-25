<?php
session_start();
include 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['pid'])) {
    $item = [
        'id' => $data['pid'],
        'name' => $data['pname'],
        'price' => $data['pprice'],
        'image' => $data['pimage'],
        'code' => $data['pcode'],
        'quantity' => 1,
    ];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Check if item already exists in the cart
    $itemExists = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
        if ($cartItem['id'] === $item['id']) {
            $cartItem['quantity'] += 1; // Increment quantity
            $itemExists = true;
            break;
        }
    }

    if (!$itemExists) {
        $_SESSION['cart'][] = $item; // Add new item to cart
    }

    echo json_encode([
        "status" => "success",
        "cart_count" => count($_SESSION['cart']),
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request",
    ]);
}
?>