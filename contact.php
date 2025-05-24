<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>E-Commerce</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <?php include 'header.php'; ?>

    <section id="s-pg-header" class="contact-header">
        <h2>#let's_talk</h2>
        <p>LEAVE A MESSAGE, We love to hear from you!</p>
    </section>

    <section id="contact-details" class="section-p1">
        <div class="details">
            <span>GET IN TOUCH</span>
            <h2>Visit one of our agency locations or contact us today</h2>
            <h3>Head office</h3>
            <ul>
                <li>
                    <i class="fa-solid fa-location-dot"></i>
                    <p>56 Glassford Street Glassgow G1 1UL New York</p>
                </li>
                <li>
                    <i class="fa-regular fa-envelope"></i>
                    <p>contact@examole.com</p>
                </li>
                <li>
                    <i class="fa-solid fa-phone"></i>
                    <p>contact@example.com</p>
                </li>
                <li>
                    <i class="fa-regular fa-clock"></i>
                    <p>Monday to Saturday: 9:00am to 16:00pm</p>
                </li>
            </ul>
        </div>
        <div class="map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d79041.58768512552!2d-1.24758785!3d51.7504163!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48713380adc41faf%3A0xc820dba8cb547402!2sOxford%2C%20UK!5e0!3m2!1sen!2sin!4v1739438890125!5m2!1sen!2sin"
                width="600" height="450" class="iframe-style" allowfullscreen="" title="Google Maps"></iframe>
        </div>
    </section>

    <section id="form-details" class="section-p1">
        <form action="">
            <span>LEAVE A MESSAGE</span>
            <h2>We love to hear from you</h2>
            <input type="text" placeholder="Your Name" />
            <input type="email" placeholder="Email" />
            <input type="text" placeholder="Subject" />
            <textarea name="" id="" cols="30" rows="10" placeholder="Your Message"></textarea>
            <button type="submit" class="normal">Submit</button>
        </form>

        <div class="people">
            <div>
                <img src="img/people/1.png" alt="" />
                <p><span>John Doe</span>Senior Marketing Manager <br>Phone: +000 123 000 77 88 <br>Email:
                    contact@gmail.com</p>
            </div>
            <div>
                <img src="img/people/2.png" alt="" />
                <p><span>William smith</span>Junior Marketing Manager <br>Phone: +000 123 000 77 88 <br>Email:
                    contact@gmail.com</p>
            </div>
            <div>
                <img src="img/people/3.png" alt="" />
                <p><span>Emma Stone</span>Managing Director<br>Phone: +000 123 000 77 88 <br>Email: contact@gmail.com
                </p>
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