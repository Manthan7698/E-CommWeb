<?php
session_start();
require_once 'db.php';

// Get database instance
$db = Database::getInstance();
$conn = $db->getConnection();

// Get selected category from URL
$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : 0;

// Get category name if a category is selected
$category_name = '';
if ($selected_category > 0) {
    $cat_stmt = $conn->prepare("SELECT name FROM categories WHERE id = ?");
    $cat_stmt->bind_param("i", $selected_category);
    $cat_stmt->execute();
    $cat_result = $cat_stmt->get_result();
    if ($cat_row = $cat_result->fetch_assoc()) {
        $category_name = $cat_row['name'];
    }
}
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
    .out-of-stock-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: #ff4444;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 12px;
      font-weight: bold;
      z-index: 1;
    }
    .pro {
      position: relative;
    }
    .selected-category {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      border-left: 4px solid #088178;
    }
    .selected-category h2 {
      color: #088178;
      margin: 0;
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
    <?php if($selected_category > 0 && $category_name): ?>
      <div class="selected-category">
        <h2>Showing products in: <?php echo htmlspecialchars($category_name); ?></h2>
      </div>
    <?php endif; ?>

    <div class="pro-container">
      <?php
      // Modify the product query based on selected category
      if ($selected_category > 0) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? AND product_status IN ('active', 'out_of_stock')");
        $stmt->bind_param("i", $selected_category);
      } else {
        $stmt = $conn->prepare("SELECT * FROM products WHERE product_status IN ('active', 'out_of_stock')");
      }
      $stmt->execute();
      $result = $stmt->get_result();
      
      if ($result->num_rows > 0):
        while ($row = $result->fetch_assoc()):
      ?>
        <div class="pro">
          <?php if ($row['stock'] == 0): ?>
            <div class="out-of-stock-badge">Out of Stock</div>
          <?php endif; ?>
          <a href="sproduct.php?pid=<?php echo $row['id']?>">
            <img src="<?php echo $row['product_img'] ?>" width="100%" alt="">
          </a>
          <div class="des">
            <span><?php echo $row['product_brand'] ?></span>
            <h5><?php echo $row['product_name'] ?></h5>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h4><i class="fa-solid fa-dollar-sign"></i> <?php echo number_format($row['product_price'], 2); ?></h4>
          </div>
          <div class="card-footer-btn">
            <form action="" class="form-submit">
              <input type="hidden" class="pid" value="<?php echo $row['id'] ?>">
              <input type="hidden" class="pname" value="<?php echo $row['product_name'] ?>">
              <input type="hidden" class="pprice" value="<?php echo $row['product_price'] ?>">
              <input type="hidden" class="pimage" value="<?php echo $row['product_img'] ?>">
              <input type="hidden" class="pcode" value="<?php echo $row['product_code'] ?>">
              <input type="hidden" class="pbrand" value="<?php echo $row['product_brand'] ?>">
              <input type="hidden" class="pdetails" value="<?php echo $row['product_details'] ?>">
              <button class="addItemBtn"><i class="fas fa-shopping-cart"></i></button>
            </form>
          </div>
        </div>
      <?php 
        endwhile;
      else:
      ?>
        <div class="no-products">
          <h3>No products found in this category</h3>
        </div>
      <?php endif; ?>
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