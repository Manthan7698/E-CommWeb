<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>E-Commerce</title>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>

    </style>
</head>

<body>
    <?php
    include("config.php");
    $pid = $_REQUEST['pid'];
    // Fetch product details from database
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = $pid");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    ?>
    <?php include 'header.php'; ?>
    
    <section id="prodetails" class="section-p1">
        <div class="single-pro-image">
            <img src="<?= $row['product_img'] ?>" id="MainImg" width="100%" alt="" />

            <div class="small-img-group">
                <div class="small-img-col">
                    <img src="img/products/f1.jpg" width="100%" class="small-img" alt="" />
                </div>
                <div class="small-img-col">
                    <img src="img/products/f2.jpg" width="100%" class="small-img" alt="" />
                </div>
                <div class="small-img-col">
                    <img src="img/products/f3.jpg" width="100%" class="small-img" alt="" />
                </div>
                <div class="small-img-col">
                    <img src="img/products/f4.jpg" width="100%" class="small-img" alt="" />
                </div>
            </div>
        </div>

        <div class="single-pro-details">
            <h6><?= $row['product_brand'] ?></h6>
            <h4><?= $row['product_name'] ?></h4>
            <h2><i class="fa-solid fa-dollar-sign"></i><?= number_format($row['product_price'], 2) ?></h2>
            <label for="size">Size :</label>
            <select name="sizes" id="Size" title="Select Size">
                <option>Select Size</option>
                <option>S</option>
                <option>M</option>
                <option>L</option>
                <option>XL</option>
                <option>XXL</option>
            </select>
            <div class="quantity-container">
                <label for="quantity">Quantity :</label>
                <select name="quantity" id="quantity">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="button-container">
                <button id="add-to-bag">Add To Bag</button>
                <button id="purchase-now">Purchase Now</button>
            </div>
            <h4>Product Description</h4>
            <span>The Gildan Ultra Cotton T-Shirt is made from a substantial 6.0az.per
                sq.yd. fabric constructed from 100% cotton, this classic fit preshrunk
                jersey knit provides unmatched comfort with each wear. Featuring a
                taped neck and shoulder, and a seamless double-needle collar, and
                available in a range of colors. it offers it all in the ultimate
                head-turing package.</span>
        </div>
    </section>

    <section id="product1" class="section-p1">
        <h2>Featured Products</h2>
        <p>Summer Collection New Morden Design</p>
        <div class="pro-container">
            <?php
            $stmt = $conn->prepare("SELECT * FROM products ORDER BY id DESC LIMIT 4");
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()):
            ?>
                <div class="pro">
                    <a href="sproduct.php?pid=<?= $row['id'] ?>"><img src="<?= $row['product_img'] ?>" width="100%"></a>
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

    <section id="newsletter" class="section-p1 section-m1">
        <div class="newstext">
            <h4>Sign Up For Newsletters</h4>
            <p>
                Get E-mail updates about our latest shop and
                <span>special offers.</span>
            </p>
        </div>
        <div class="form">
            <input type="email" placeholder="Enter Your Email" />
            <button class="normal">Sign Up</button>
        </div>
    </section>

    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="img/logo.png" alt="" />
            <h4>Contact</h4>
            <p>
                <strong>Address:</strong> 562 Wellington Road, Street 32, San
                Francisco
            </p>
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
                <img src="img/pay/app.jpg" alt="" />
                <img src="img/pay/play.jpg" alt="" />
            </div>
            <p>Secured Payment Gateways</p>
            <img src="img/pay/pay.png" alt="" />
        </div>
        <div class="copyright">
            <p>© 2024, E-Commerce Website</p>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/0164451027.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>

</body>

</html>