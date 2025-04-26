<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <?php include 'header.php'; ?>
    <section id="bag-container">
        <div class="bag-box-area">
            <div class="bag-item-box-area">
                <h2>Your Items</h2>
                <div class="bag-header">
                    <div class="divider"></div>
                    <button id="clearBagBtn" class="clear-bag-btn">Clear Bag</button>
                </div>
                <div class="bag-item-box">
                    <?php
                    include 'config.php';
                    $total = 0;
                    $stmt = $conn->prepare('SELECT * FROM cart');
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows === 0) {
                        echo '<div class="empty-cart-message">';
                        echo '<i class="fa-solid fa-shopping-bag" style="font-size: 50px; color: #088178; margin-bottom: 20px;"></i>';
                        echo '<h3>Your Shopping Bag is Empty!</h3>';
                        echo '<p>Looks like you haven\'t added anything to your bag yet.</p>';
                        echo '<a href="shop.php" class="shop-now-btn">Shop Now</a>';
                        echo '</div>';
                    }
                    
                    while ($row = $result->fetch_assoc()):
                        $total += $row['product_price'] * $row['qty'];
                    ?>
                        <div class="bag-item">
                            <img src="<?php echo $row['product_img'] ?>" alt="">
                            <div class="bag-item-des">
                                <h4><?= $row['product_brand']?></h4>
                                <h5><?= $row['product_name'] ?></h5>
                                <div class="price-container">
                                    <p class="item-price">Price: <i class="fa-solid fa-dollar-sign"></i><?= number_format($row['product_price'], 2) ?></p>
                                    <p class="item-subtotal">Total: <i class="fa-solid fa-dollar-sign"></i><?= number_format($row['product_price'] * $row['qty'], 2) ?></p>
                                </div>
                                <br>
                                <label for="size">Size :</label>
                                <select name="sizes" id="size" class="size-select" data-cart-id="<?= $row['id'] ?>">
                                    <option value="">Select Size</option>
                                    <option value="S" <?= $row['product_size'] === 'S' ? 'selected' : '' ?>>S</option>
                                    <option value="M" <?= $row['product_size'] === 'M' ? 'selected' : '' ?>>M</option>
                                    <option value="L" <?= $row['product_size'] === 'L' ? 'selected' : '' ?>>L</option>
                                    <option value="XL" <?= $row['product_size'] === 'XL' ? 'selected' : '' ?>>XL</option>
                                    <option value="XXL" <?= $row['product_size'] === 'XXL' ? 'selected' : '' ?>>XXL</option>
                                </select>
                                <br>
                                <div class="cart-controls">
                                    <div class="quantity-controls">
                                        <button class="quantity-btn minus-btn" data-cart-id="<?= $row['id'] ?>">
                                            <i class="fa-solid fa-minus"></i>
                                        </button>
                                        <span class="quantity-value"><?= $row['qty'] ?></span>
                                        <button class="quantity-btn plus-btn" data-cart-id="<?= $row['id'] ?>">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                    </div>
                                    <div class="divider"></div>
                                    <button class="delete-btn" data-cart-id="<?= $row['id'] ?>">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div> 
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <div class="bag-subtotal-box">
            <div class="bag-subtotal-des">
                <h3>Order Summary</h3>
                <p class="small-p-txt">Shipping and discount codes are added at checkout.</p>
                <?php
                $total = 0;
                $stmt = $conn->prepare('SELECT SUM(product_price * qty) as total FROM cart');
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $total = $row['total'] ?? 0;
                ?>
                <p class="subtotal-txt">Subtotal: <span><strong><i class="fa-solid fa-dollar-sign"></i><?= number_format($total, 2) ?></strong></span></p>
                <a href="checkout.php" class="proceed-to-buy">Proceed to Buy</a>
            </div>
        </div>
    </section>

    <section id="newsletter" class="section-p1 section-m1">
        <div class="newstext">
            <h4>Sign Up For Newsletters</h4>
            <p>Get E-mail updates about our latest shop and <span>special offers.</span></p>
        </div>
        <div class="form">
            <input type="email" placeholder="Enter Your Email">
            <button class="normal">Sign Up</button>
        </div>
    </section>

    <?php include 'footer.php' ?>

    <script src="https://kit.fontawesome.com/0164451027.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>
    <script src="bag.js"></script>

</body>

</html>