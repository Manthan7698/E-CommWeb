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
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <style>
    .pro a img {
      border-radius: 20px;
    }
  </style>
</head>

<body>
    <?php include 'header.php'; ?>      

  <section id="s-pg-header">
    <h2>#stayhome</h2>
    <p>Save more with coupons and upto 70% off!</p>
  </section>

  <section id="product1" class="section-p1">
    <div class="pro-container">
      <?php
      include 'config.php';
      $stmt = $conn->prepare("SELECT * FROM products");
      $stmt->execute();
      $result = $stmt->get_result();
      while ($row = $result->fetch_assoc()):
      ?>
        <div class="pro">
          <a href="sproduct.php?pid=<?php echo $row['id'] ?>"><img src="<?= $row['product_img'] ?>" width="100%"></a>
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

  <section id="pagination" class="section-p1">
    <a href="#">1</a>
    <a href="#">2</a>
    <a href="#" title="Next Page"><i class="fa-solid fa-arrow-right"></i></a>
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