<?php
require 'config.php';

// Handle adding items to cart
if (isset($_POST['pid'])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pcode = $_POST['pcode'];
    $pqty = 1;

    $stmt = $conn->prepare("SELECT product_code FROM cart WHERE product_code=?");
    $stmt->bind_param("s", $pcode);
    $stmt->execute();
    $res = $stmt->get_result();
    $r = $res->fetch_assoc();
    $code = isset($r['product_code']) ? $r['product_code'] : null;

    if (!$code) {
        $query = $conn->prepare("INSERT INTO cart (product_name, product_price, product_img, qty, total_price, product_code) VALUES(?,?,?,?,?,?)");
        $query->bind_param("sssiss", $pname, $pprice, $pimage, $pqty, $pprice, $pcode);
        $query->execute();

        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
  <strong>The Item Added To The Cart</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
    } else {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>The Item Is Already In Cart</strong>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
    }
}

// Handle cart item count request - Move this outside the POST condition
if (isset($_GET['cartItem']) && $_GET['cartItem'] == 'cart_item') {
    $stmt = $conn->prepare("SELECT * FROM cart");
    $stmt->execute();
    $stmt->store_result();
    $rows = $stmt->num_rows;
    
    echo $rows;
}
?>