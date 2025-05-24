<?php
session_start();
include 'add_to_cart.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        .pro a img{
            border-radius: 20px;
        }
    </style>
</head>

<body>
   <?php include 'header.php'; ?>

    <section id="hero">
        <h4>Trade-in-offer</h4>
        <h2>Super Value Deals</h2>
        <h1>On all products</h1>
        <p>Save more with coupons and upto 70% off!</p>
        <button onclick="window.location.href='shop.php'">Shop Now</button>
    </section>

    <section id="feature" class="section-p1">
        <div class="fe-box">
            <img src="img/features/f1.png" alt="Feature_1">
            <h6>Free Trails</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f2.png" alt="Feature_2">
            <h6>Online Order</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f3.png" alt="Feature_3">
            <h6>Save Money</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f4.png" alt="Feature_4">
            <h6>Promotion</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f5.png" alt="Feature_5">
            <h6>Happy Sells</h6>
        </div>
        <div class="fe-box">
            <img src="img/features/f6.png" alt="Feature_6">
            <h6>24/7 Support</h6>
        </div>
    </section>

    <section id="product1" class="section-p1">
        <h2>Featured Product</h2>
        <p>Summer Collection New Morden Design</p>
        <div id="message"></div>
        <div class="pro-container">
            <?php
            include 'config.php';
            $stmt = $conn->prepare("SELECT * FROM products LIMIT 8");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
            ?>
                <div class="pro">
                   <a href="sproduct.php?pid=<?php echo $row['id']?>"><img src="<?= $row['product_img'] ?>" width="100%"></a> 
                    <div class="des">
                        <span><?= $row['product_brand'] ?></span>
                        <h5><?= $row['product_name'] ?></h5>
                        <div class="star">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h4><i class="fa-solid fa-dollar-sign"></i> <?php echo number_format($row['product_price'], 2); ?> </h4>
                    </div>
                    <div class="card-footer-btn">
                        <form action="" class="form-submit">
                            <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                            <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
                            <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                            <input type="hidden" class="pimage" value="<?= $row['product_img'] ?>">
                            <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
                            <input type="hidden" class="pbrand" value="<?= $row['product_brand'] ?>">
                            <input type="hidden" class="pdetails" value="<?= $row['product_details'] ?>">
                            <button class="addItemBtn"><i class="fas fa-shopping-cart"></i></button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section id="banner" class="section-m1">
        <h4>Repair Services</h4>
        <h2>Up to <span>70% Off</span> - All t-Shirts & Accessories</h2>
        <button class="normal">Explore More</button>
    </section>

    <section id="product2" class="section-p1">
        <h2>New Arrivals</h2>
        <p>Summer Collection New Morden Design</p>
        <div id="message"></div>
        <div class="pro-container">
            <?php
            include 'config.php';
            $stmt = $conn->prepare("SELECT * FROM products LIMIT 8 OFFSET 8");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
            ?>
                <div class="pro">
                    <a href="sproduct.php?pid=<?= $row['id']?>"><img src="<?= $row['product_img'] ?>" width="100%"></a>
                    <div class="des">
                        <span><?= $row['product_brand'] ?></span>
                        <h5><?= $row['product_name'] ?></h5>
                        <div class="star">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <h4><i class="fa-solid fa-dollar-sign"></i> <?= number_format($row['product_price'], 2) ?> </h4>
                    </div>
                    <div class="card-footer-btn">
                        <form action="" class="form-submit">
                            <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                            <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
                            <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                            <input type="hidden" class="pimage" value="<?= $row['product_img'] ?>">
                            <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">
                            <input type="hidden" class="pbrand" value="<?= $row['product_brand'] ?>">
                            <input type="hidden" class="pdetails" value="<?= $row['product_details'] ?>">
                            <button class="addItemBtn"><i class="fas fa-shopping-cart"></i></button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section id="sm-banner" class="section-p1">
        <div class="banner-box">
            <h4>Crazy deals</h4>
            <h2>buy 1 get 1 free</h2>
            <span>The best classic dress is one sale at cara</span>
            <button class="white">learn More</button>
        </div>
        <div class="banner-box banner-box2">
            <h4>Spring/Summer</h4>
            <h2>Upcomming Season</h2>
            <span>The best classic dress is one sale at cara</span>
            <button class="white">Collection</button>
        </div>
    </section>

    <section id="banner3" class="section-p1">
        <div class="banner-box">
            <h2>SEASONAL SALE</h2>
            <h3>Winter Collection -50% OFF</h3>
        </div>
        <div class="banner-box banner-box2">
            <h2>NEW FOOTWEAR COLLECTION</h2>
            <h3>Spring / Summer2024</h3>
        </div>
        <div class="banner-box banner-box3">
            <h2>T-SHIRT</h2>
            <h3>New Trendy Prints</h3>
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

    <?php include 'footer.php'; ?>

    <script src="https://kit.fontawesome.com/0164451027.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>