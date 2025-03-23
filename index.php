<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <section id="header">
        <a href="index.php"><img src="img/logo.png" alt="Logo"></a>
        <div class="search-box">
            <input type="text" placeholder="Search...">
            <button name="search-btn" type="submit" title="Search"><i class="fa-solid fa-search"></i></button>
        </div>
        <div>
            <ul id="navbar">
                <!-- <li id="Search"><input type="text" style="padding-left: 15px;" placeholder="Search"></li> -->
                <li><a class="active" href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li id="lg-bag">
                    <a href="bag.php" title="Shopping Bag"><i class="fa-solid fa-bag-shopping"></i><span id="bag-item-count"></span></a>
                </li>
                <li><a id="login-btn" href="login.php">Login</a></li>
                <li><a href="#" id="close" title="Close"><i class="fa-solid fa-xmark"></i></a></li>
            </ul>
        </div>
        <div id="mobile">
            <a href="bag.html" title="Shopping Bag"><i class="fa-solid fa-bag-shopping"></i></a>
            <i id="bar" class="fa-solid fa-outdent"></i>
        </div>
    </section>

    <section id="hero">
        <h4>Trade-in-offer</h4>
        <h2>Super Value Deals</h2>
        <h1>On all products</h1>
        <p>Save more with coupons and upto 70% off!</p>
        <button>Shop Now</button>
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
            $stmt = $conn->prepare("SELECT * FROM products");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
            ?>
                <div class="pro">
                    <img src="<?= $row['product_img'] ?>" alt="">
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

    <section id="product1" class="section-p1">
        <h2>New Arrivals</h2>
        <p>Summer Collection New Morden Design</p>
        <div id="message"></div>
        <div class="pro-container">
            <?php
            include 'config.php';
            $stmt = $conn->prepare("SELECT * FROM products");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
            ?>
                <div class="pro">
                    <img src="<?= $row['product_img'] ?>" alt="">
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

    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="img/logo.png" alt="">
            <h4>Contact</h4>
            <p><strong>Address:</strong> 562 Wellington Road, Street 32, San Francisco</p>
            <p><strong>Phone:</strong> (+91) 9510670132 / (+91) 8866830048</p>
            <p><strong>Hours:</strong> 10:00 - 18:00, Mon - Sat</p>
            <div class="follow">
                <h4>Follow Us</h4>
                <div class="icons">
                    <a href="#" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fa-brands fa-x-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" title="Pinterest"><i class="fa-brands fa-pinterest-p"></i></a>
                    <a href="#" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>
        </div>
        <div class="col">
            <h4>About</h4>
            <a href="#">About us</a>
            <a href="#">Delivery Information</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms & Conditions</a>
            <a href="#">Contact Us</a>
        </div>

        <div class="col">
            <h4>My Account</h4>
            <a href="#">Sing-in</a>
            <a href="#">View Cart</a>
            <a href="#">My Wishlist</a>
            <a href="#">Track My Order</a>
            <a href="#">Help</a>
        </div>

        <div class="col install">
            <h4>Install App</h4>
            <p>From App Store or Google Play Store</p>
            <div class="row">
                <img src="img/pay/app.jpg" alt="">
                <img src="img/pay/play.jpg" alt="">
            </div>
            <p>Secured Payment Gateways</p>
            <img src="img/pay/pay.png" alt="">
        </div>
        <div class="copyright">
            <p>© 2024, E-Commerce Website</p>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/0164451027.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>