<?php
include 'config.php';

if (isset($_POST["pid"])) {
    $pid = $_POST['pid'];
    $pname = $_POST['pname'];
    $pprice = $_POST['pprice'];
    $pimage = $_POST['pimage'];
    $pcode = $_POST['pcode'];
    $pqty = $_POST["quantity"]; // Default quantity for new items
echo "======".$pqty;
    // Check if the product already exists in the cart
    $stmt = $conn->prepare("SELECT qty FROM cart WHERE product_code = ?");
    $stmt->bind_param("s", $pcode);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // If product exists, update the quantity
        $stmt = $conn->prepare("UPDATE cart SET qty = qty + ".$pqty." WHERE product_code = ?");
        $stmt->bind_param("s", $pcode);
        $stmt->execute();
        echo "Product quantity updated in cart!";
    } else {
        // If product does not exist, insert it into the cart
        $stmt = $conn->prepare("INSERT INTO cart (id, product_name, product_price, product_img, product_code, qty) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $pid, $pname, $pprice, $pimage, $pcode, $pqty);
        if ($stmt->execute()) {
            echo "Product added to cart successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
}

$conn->close();
?>