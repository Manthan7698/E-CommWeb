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

    <section id="header">
        <a href="#"><img src="img/logo.png" alt="Logo"></a>
        <div class="search-box">
            <input type="text" placeholder="Search...">
            <button name="search-btn" type="submit" title="Search"><i class="fa-solid fa-search"></i></button> 
        </div>
        <div>
            <ul id="navbar">         
                <!-- <li id="Search"><input type="text" style="padding-left: 15px;" placeholder="Search"></li> -->
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="blog.php">Blog</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact Us</a></li>
                <li id="lg-bag">
                    <a class="active" href="bag.php" title="Shopping Bag"><i class="fa-solid fa-bag-shopping"></i></a>
                    <span id="bag-item-count">0</span>
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

    <section id="bag-container">
        <div class="bag-box-area">
            <div class="bag-item-box-area">
                <h2>Your Items</h2>
                <div class="bag-item-box">
                    <div class="bag-item">
                        <img src="img/products/f1.jpg" alt="">
                        <div class="bag-item-des">
                            <h5>Cartoon Astronaut T-Shirt</h5>
                            <span>The Gildan Ultra Cotton T-Shirt is made from a substantial 6.0az.per
                                sq.yd. fabric constructed from 100% cotton...
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
                        </div>
                    </div>
                </div>
                <div bag-item-box>
                    <div class="bag-item">
                        <img src="img/products/f2.jpg" alt="">
                        <div class="bag-item-des">
                            <h5>Cuban Collar Shirt With Short Sleeves</h5>
                            <span>The Gildan Ultra Cotton T-Shirt is made from a substantial 6.0az.per
                                sq.yd. fabric constructed from 100% cotton...
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
                        </div>
                    </div>
                </div>
                <div bag-item-box>
                    <div class="bag-item">
                        <img src="img/products/f3.jpg" alt="">
                        <div class="bag-item-des">
                            <h5>Cuban Collar Shirt With Short Sleeves</h5>
                            <span>The Gildan Ultra Cotton T-Shirt is made from a substantial 6.0az.per
                                sq.yd. fabric constructed from 100% cotton...
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
                        </div>
                    </div>
                </div>
                <div bag-item-box>
                    <div class="bag-item">
                        <img src="img/products/f4.jpg" alt="">
                        <div class="bag-item-des">
                            <h5>Cuban Collar Shirt With Short Sleeves</h5>
                            <span>The Gildan Ultra Cotton T-Shirt is made from a substantial 6.0az.per
                                sq.yd. fabric constructed from 100% cotton...
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
                        </div>
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
    <script src="script.js"></script>
</body>
</html>