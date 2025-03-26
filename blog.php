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
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <?php include 'header.php'; ?>

  <section id="s-pg-header" class="blog-header">
    <h2>#readmore</h2>
    <p>Read all case studies about our products!</p>
  </section>

  <section id="blog">
    <div class="blog-box">
      <div class="blog-img">
        <img src="img/blog/b1.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h3>The Cotton-Jersey Zip-Up Hoodies</h3>
        <p>Kickstarter man braid godard coloring book. Reclette waistcoat selfies yr wolf chartreuse hexagon irony, godard...</p>
        <a href="#">CONTINUE READING</a>
      </div>
      <h1>13/01</h1>
    </div>
    <div class="blog-box">
      <div class="blog-img">
        <img src="img/blog/b2.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h3>How to Style a Quiff</h3>
        <p>Kickstarter man braid godard coloring book. Reclette waistcoat selfies yr wolf chartreuse hexagon irony, godard...</p>
        <a href="#">CONTINUE READING</a>
      </div>
      <h1>13/01</h1>
    </div>
    <div class="blog-box">
      <div class="blog-img">
        <img src="img/blog/b3.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h3>Must-Have Skater Girl Items</h3>
        <p>Kickstarter man braid godard coloring book. Reclette waistcoat selfies yr wolf chartreuse hexagon irony, godard...</p>
        <a href="#">CONTINUE READING</a>
      </div>
      <h1>13/01</h1>
    </div>
    <div class="blog-box">
      <div class="blog-img">
        <img src="img/blog/b4.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h3>Runway-Inspired Trands</h3>
        <p>Kickstarter man braid godard coloring book. Reclette waistcoat selfies yr wolf chartreuse hexagon irony, godard...</p>
        <a href="#">CONTINUE READING</a>
      </div>
      <h1>13/01</h1>
    </div>
    <div class="blog-box">
      <div class="blog-img">
        <img src="img/blog/b6.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h4>AW20 Menswear Trends</h4>
        <p>Kickstarter man braid godard coloring book. Reclette waistcoat selfies yr wolf chartreuse hexagon irony, godard...</p>
        <a href="#">CONTINUE READING</a>
      </div>
      <h1>13/01</h1>
    </div>
  </section>

  <section id="pagination" class="section-p1">
    <a href="#">1</a>
    <a href="#">2</a>
    <a href="#" title="Next"><i class="fa-solid fa-arrow-right"></i></a>
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

  <script
    src="https://kit.fontawesome.com/0164451027.js"
    crossorigin="anonymous"></script>
  <script src="script.js"></script>

</body>

</html>