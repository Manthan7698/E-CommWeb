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
                                    <p class="item-subtotal">Subtotal: <i class="fa-solid fa-dollar-sign"></i><?= number_format($row['product_price'] * $row['qty'], 2) ?></p>
                                </div>
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
                <p class="subtotal-txt">Total: <span><strong><i class="fa-solid fa-dollar-sign"></i><?= number_format($total, 2) ?></strong></span></p>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const quantityControls = document.querySelectorAll('.quantity-controls');
            const deleteButtons = document.querySelectorAll('.delete-btn');

            function updateQuantity(cartId, newQuantity, quantityControl) {
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
                        const quantityValue = quantityControl.querySelector('.quantity-value');
                        quantityValue.textContent = newQuantity;
                        updateCartCount();
                        updatePrices();
                    } else {
                        alert('Error updating quantity');
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            function removeItem(cartId, itemElement) {
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
                            itemElement.closest('.bag-item').remove();
                            updateCartCount();
                            updatePrices();
                        } else {
                            alert('Error removing item');
                        }
                    })
                    .catch(error => console.error('Error:', error));
                }
            }

            quantityControls.forEach(control => {
                const minusBtn = control.querySelector('.minus-btn');
                const plusBtn = control.querySelector('.plus-btn');
                const quantityValue = control.querySelector('.quantity-value');
                const cartId = minusBtn.dataset.cartId;

                minusBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityValue.textContent);
                    if (currentValue > 1) {
                        updateQuantity(cartId, currentValue - 1, control);
                    }
                });

                plusBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityValue.textContent);
                    if (currentValue < 10) {
                        updateQuantity(cartId, currentValue + 1, control);
                    }
                });
            });

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cartId = this.dataset.cartId;
                    removeItem(cartId, this);
                });
            });

            function updatePrices() {
                fetch('get_cart_total.php')
                    .then(response => response.json())
                    .then(data => {
                        const subtotalElement = document.querySelector('.subtotal-txt strong');
                        if (subtotalElement) {
                            subtotalElement.innerHTML = `<i class="fa-solid fa-dollar-sign"></i>${data.total.toFixed(2)}`;
                        }

                        document.querySelectorAll('.bag-item').forEach(item => {
                            const quantityValue = item.querySelector('.quantity-value');
                            const priceElement = item.querySelector('.item-price');
                            const subtotalElement = item.querySelector('.item-subtotal');
                            
                            if (quantityValue && priceElement && subtotalElement) {
                                const price = parseFloat(priceElement.textContent.replace(/[^0-9.-]+/g, ''));
                                const quantity = parseInt(quantityValue.textContent);
                                const subtotal = price * quantity;
                                subtotalElement.innerHTML = `Subtotal: <i class="fa-solid fa-dollar-sign"></i>${subtotal.toFixed(2)}`;
                            }
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>

</body>

</html>