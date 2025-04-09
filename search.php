<?php
session_start();
include 'config.php';

// Get the search query
$search = isset($_GET['q']) ? $_GET['q'] : '';

// Debug: Log the search query
error_log("Search page query: " . $search);

// Prepare the search query
$query = "SELECT * FROM products WHERE product_name LIKE ? OR product_brand LIKE ? OR product_details LIKE ?";
$stmt = $conn->prepare($query);
$searchTerm = "%$search%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Debug: Log the number of results found
error_log("Found " . $result->num_rows . " results for query: " . $search);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - E-Commerce</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <section id="s-pg-header">
        <h2>Search Results</h2>
        <p>Showing results for: "<?php echo htmlspecialchars($search); ?>"</p>
    </section>

    <section id="product1" class="section-p1">
        <div class="pro-container">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
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
            <?php else: ?>
                <div class="no-results">
                    <h3>No products found matching your search.</h3>
                    <p>Try different keywords or browse our <a href="shop.php">full catalog</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'footer.php' ?>
    <script src="script.js"></script>
</body>
</html> 