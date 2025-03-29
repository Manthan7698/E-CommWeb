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
    <script src="live-reload.js"></script>
    <script>

    </script>



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

            <div class="button-container">
                <form action="add_to_cart.php" class="form-submit">
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
                        <select name="quantity" id="quantity" title="Quantity">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>
                    <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                    <input type="hidden" class="pname" value="<?= $row['product_name'] ?>">
                    <input type="hidden" class="pprice" value="<?= $row['product_price'] ?>">
                    <input type="hidden" class="pimage" value="<?= $row['product_img'] ?>">
                    <input type="hidden" class="pcode" value="<?= $row['product_code'] ?>">

                    <button type="submit" class="addItemBtn" title="AddToBag"><span>Add To Bag</span><i class="fas fa-shopping-cart"></i></button>
                </form>
            </div>
            <h4>Product Description</h4>
            <span>The Gildan Ultra Cotton T-Shirt is made from a substantial 6.0az.per
                sq.yd. fabric constructed from 100% cotton, this classic fit preshrunk
                jersey knit provides unmatched comfort with each wear. Featuring a
                taped neck and shoulder, and a seamless double-needle collar, and
                available in a range of colors. it offers it all in the ultimate
                head-turing package.</span>
                <br>
                <br>
                <marquee style="background-color: #0881796f; color: #000; font-weight: bold; padding: 2px; border-radius: 5px;" behavior="scroll" direction="left" loop="infinite" scrollamount="10" >
                    <span>% Free Shipping, Free Returns, and always 2-day shipping %</span>
                </marquee>
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

    <?php include 'footer.php' ?>

    <script src="https://kit.fontawesome.com/0164451027.js" crossorigin="anonymous"></script>
    <script src="script.js"></script>



</body>

</html>