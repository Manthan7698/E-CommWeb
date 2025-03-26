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
</head>

<body>
    <?php include 'header.php'; ?>

    <section id="s-pg-header" class="about-header">
        <h2>#KnowUs</h2>
        <p>Read all case studies about our products!</p>
    </section>

    <section id="about-head" class="section-p1">
        <img src="img/about/a6.jpg" alt="">
        <div>
            <h2>Who We Are?</h2>
            <p>An ecommerce website is your digital storefront on the internet. It facilitates the transaction between a
                buyer and seller. It is the virtual space where you showcase products, and online customers make
                selections.
                Your website acts as the product shelves, sales staff, and cash register of your online business
                channel.Ecommerce (or electronic commerce) is the buying and selling of goods or services on the
                Internet.
                It encompasses a wide variety of data, systems and tools for online buyers and sellers, including mobile
                shopping and online payment encryption.Most businesses with an online presence use an online store
                and/or
                platform to conduct ecommerce marketing and sales activities and to oversee logistics and fulfillment.
            </p>
            <abbr title="">Create stunning images with as much or as little control as you like thanks to a choice of
                basic and Creative models.</abbr>

            <br><br>

            <marquee bgcolor="#ccc" loop="-1" scrollamount="5" width="100%">Create stunning images with as much or as little control as you like thanks to a choice of
                basic and Creative models.</marquee>
        </div>
    </section>

    <section id="about-app" class="section-p1">
        <h1>Download Our <a href="#">App</a></h1>
        <div class="video">
            <video autoplay muted loop src="img/about/1.mp4"></video>
        </div>
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