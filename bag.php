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
                                <button class="remove-item"><i class="fa-solid fa-trash"><input type="number" name="quantity" id="quantity" value="1" min="1" max="10"></i><i class="fa-solid fa-plus"></i></button>
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
                <p class="subtotal-txt">Subtotal: <span><strong>$0.00</strong></span></p>
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

</body>

</html>