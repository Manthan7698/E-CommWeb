<?php
session_start();
include("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - E-Commerce</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <section id="checkout" class="section-p1">
        <div class="checkout-container">
            <div class="checkout-form">
                <h2>Shipping Details</h2>
                <form action="process_order.php" method="POST">
                    <div class="form-group">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="fullname" name="fullname" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>

                    <div class="form-group">
                        <label for="address">Shipping Address</label>
                        <textarea id="address" name="address" required></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" required>
                        </div>
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="zipcode">ZIP Code</label>
                            <input type="text" id="zipcode" name="zipcode" required>
                        </div>
                        <div class="form-group">
                            <label for="country">Country</label>
                            <input type="text" id="country" name="country" required>
                        </div>
                    </div>

                    <div class="payment-methods">
                        <h3>Payment Method</h3>
                        <div class="payment-option">
                            <input type="radio" id="card" name="payment" value="card" checked>
                            <label for="card">Credit/Debit Card</label>
                        </div>
                        <div class="payment-option">
                            <input type="radio" id="paypal" name="payment" value="paypal">
                            <label for="paypal">PayPal</label>
                        </div>
                    </div>

                    <button type="submit" class="place-order-btn">Place Order</button>
                </form>
            </div>

            <div class="order-summary">
                <h2>Order Summary</h2>
                <div class="summary-items">
                    <?php
                    $total = 0;
                    $stmt = $conn->prepare('SELECT * FROM cart');
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()):
                        $subtotal = $row['product_price'] * $row['qty'];
                        $total += $subtotal;
                    ?>
                        <div class="summary-item">
                            <img src="<?= $row['product_img'] ?>" alt="<?= $row['product_name'] ?>">
                            <div class="item-details">
                                <h4><?= $row['product_name'] ?></h4>
                                <p>Size: <?= $row['product_size'] ?></p>
                                <p>Quantity: <?= $row['qty'] ?></p>
                                <p class="item-price">$<?= number_format($subtotal, 2) ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                        <div class="summary-totals">
                            <div class="subtotal">
                                <span>Subtotal</span>
                                <span>$<?= number_format($total, 2) ?></span>
                            </div>
                            <div class="shipping">
                                <span>Shipping</span>
                                <span>Free</span>
                            </div>
                            <div class="total">
                                <span>Total</span>
                                <span>$<?= number_format($total, 2) ?></span>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php' ?>

    <script src="script.js"></script>
</body>
</html> 