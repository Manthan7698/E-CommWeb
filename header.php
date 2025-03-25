<?php
// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']); // e.g., "index.php"
?>
<section id="header">
    <a href="#"><img src="img/logo.png" alt="Logo"></a>
    <div class="search-box">
        <input type="text" placeholder="Search...">
        <button name="search-btn" type="submit" title="Search"><i class="fa-solid fa-search"></i></button>
    </div>
    <div>
        <ul id="navbar">
            <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="shop.php" class="<?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">Shop</a></li>
            <li><a href="blog.php" class="<?php echo ($current_page == 'blog.php') ? 'active' : ''; ?>">Blog</a></li>
            <li><a href="about.php" class="<?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About</a></li>
            <li><a href="contact.php" class="<?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact Us</a></li>
            <li id="lg-bag">
                <a href="bag.php" title="Shopping Bag"><i class="fa-solid fa-bag-shopping"></i></a>
                <span id="bag-item-count">
                    <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                </span>
            </li>
            <li><a id="login-btn" href="login.php" class="<?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">Login</a></li>
            <li><a href="#" id="close" title="Close"><i class="fa-solid fa-xmark"></i></a></li>
        </ul>
    </div>
    <div id="mobile">
        <a href="bag.php" title="Shopping Bag"><i class="fa-solid fa-bag-shopping"></i></a>
        <i id="bar" class="fa-solid fa-outdent"></i>
    </div>
</section>
