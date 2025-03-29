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
                <div class="bag-item-box">
                    <?php
                    include 'config.php';
                    $stmt = $conn->prepare('SELECT * FROM cart');
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()):
                    ?>
                        <div class="bag-item">
                            <img src="<?php echo $row['product_img'] ?>" alt="">
                            <div class="bag-item-des">
                                <h4><?= $row['product_brand']?></h4>
                                <h5><?= $row['product_name'] ?></h5>
                                <span><?= $row['product_details'] ?>
                                </span>
                                <br>
                                <br>
                                <p class="item-price">Price: <i class="fa-solid fa-dollar-sign"></i><?= number_format($row['product_price'], 2) ?></p>
                                <p class="item-subtotal">Subtotal: <i class="fa-solid fa-dollar-sign"></i><?= number_format($row['product_price'] * $row['qty'], 2) ?></p>
                                <br>
                                <label for="size">Size :</label>
                                <select name="sizes" id="size">
                                    <option>Select Size</option>
                                    <option>S</option>
                                    <option>M</option>
                                    <option>L</option>
                                    <option>XL</option>
                                    <option>XXL</option>
                                </select>
                                <br>
                                <br>
                                <div class="quantity-controls">
                                    <button class="quantity-btn minus" data-cart-id="<?= $row['id'] ?>">-</button>
                                    <input type="number" class="quantity-input" value="<?= $row['qty'] ?>" min="1" max="10" data-cart-id="<?= $row['id'] ?>">
                                    <button class="quantity-btn plus" data-cart-id="<?= $row['id'] ?>">+</button>
                                    <button class="remove-item" data-cart-id="<?= $row['id'] ?>"><i class="fa-solid fa-trash"></i></button>
                                </div>
                            </div> 
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <div class="bag-box-area blank-area"></div>
        <div class="bag-subtotal-box">
            <div class="bag-subtotal-des">
                <h3>Subtotal</h3>
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
                <br>
                <button class="proceed-to-buy">Proceed to Buy</button>
            </div>
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
    <script>
        // Quantity update functionality
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInputs = document.querySelectorAll('.quantity-input');
            const minusButtons = document.querySelectorAll('.quantity-btn.minus');
            const plusButtons = document.querySelectorAll('.quantity-btn.plus');
            const removeButtons = document.querySelectorAll('.remove-item');

            function updateQuantity(cartId, newQuantity) {
                fetch('update_quantity.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `cart_id=${cartId}&quantity=${newQuantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartCount();
                        updatePrices();
                    } else {
                        alert('Error updating quantity');
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function updatePrices() {
                fetch('get_cart_total.php')
                    .then(response => response.json())
                    .then(data => {
                        // Update the main subtotal
                        const subtotalElement = document.querySelector('.subtotal-txt strong');
                        if (subtotalElement) {
                            subtotalElement.innerHTML = `<i class="fa-solid fa-dollar-sign"></i>${data.total.toFixed(2)}`;
                        }

                        // Update individual item subtotals
                        document.querySelectorAll('.bag-item').forEach(item => {
                            const quantityInput = item.querySelector('.quantity-input');
                            const priceElement = item.querySelector('.item-price');
                            const subtotalElement = item.querySelector('.item-subtotal');
                            
                            if (quantityInput && priceElement && subtotalElement) {
                                const price = parseFloat(priceElement.textContent.replace(/[^0-9.-]+/g, ''));
                                const quantity = parseInt(quantityInput.value);
                                const subtotal = price * quantity;
                                subtotalElement.innerHTML = `Subtotal: <i class="fa-solid fa-dollar-sign"></i>${subtotal.toFixed(2)}`;
                            }
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }

            quantityInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const cartId = this.dataset.cartId;
                    const newQuantity = parseInt(this.value);
                    if (newQuantity >= 1 && newQuantity <= 10) {
                        updateQuantity(cartId, newQuantity);
                    } else {
                        this.value = 1;
                        updateQuantity(cartId, 1);
                    }
                });
            });

            minusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cartId = this.dataset.cartId;
                    const input = this.nextElementSibling;
                    const currentValue = parseInt(input.value);
                    if (currentValue > 1) {
                        input.value = currentValue - 1;
                        updateQuantity(cartId, currentValue - 1);
                    }
                });
            });

            plusButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cartId = this.dataset.cartId;
                    const input = this.previousElementSibling;
                    const currentValue = parseInt(input.value);
                    if (currentValue < 10) {
                        input.value = currentValue + 1;
                        updateQuantity(cartId, currentValue + 1);
                    }
                });
            });

            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cartId = this.dataset.cartId;
                    if (confirm('Are you sure you want to remove this item?')) {
                        fetch('remove_from_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `cart_id=${cartId}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.closest('.bag-item').remove();
                                updateCartCount();
                                updatePrices();
                            } else {
                                alert('Error removing item');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            });
        });
    </script>

</body>

</html>