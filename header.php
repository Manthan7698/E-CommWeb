<?php
// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']); // e.g., "index.php"

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<section id="header">
    <a href="index.php" class="logo" id="logo"><img src="img/logo.png" alt="Logo"></a>
    
    <form method="get" action="search.php" id="search-form">
    <div class="search-box">
        <input type="text" name="q" id="search-input" placeholder="Search..." autocomplete="off">
        <button name="search-btn" type="submit" title="Search"><i class="fa-solid fa-search"></i></button>
        <div id="search-suggestions" class="search-suggestions"></div>
    </div>
    </form>
    
    <div class="nav-container">
        <ul id="navbar">
            <li><a href="index.php" class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Home</a></li>
            <li><a href="shop.php" class="nav-link <?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>">Shop</a></li>
            <li><a href="blog.php" class="nav-link <?php echo ($current_page == 'blog.php') ? 'active' : ''; ?>">Blog</a></li>
            <li><a href="about.php" class="nav-link <?php echo ($current_page == 'about.php') ? 'active' : ''; ?>">About</a></li>
            <li><a href="contact.php" class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>">Contact Us</a></li>
            <li id="lg-bag">
                <a href="bag.php" class="nav-link bag-icon <?php echo ($current_page == 'bag.php') ? 'active' : ''; ?>" title="Shopping Bag"><i class="fa-solid fa-bag-shopping"></i></a>
                <span id="bag-item-count">
                    <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                </span>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="user-menu">
                    <a href="#" class="nav-link user-link">
                        <i class="fa-solid fa-user"></i> <?php echo $_SESSION['name']; ?>
                    </a>
                    <div class="dropdown-menu">
                        <a href="profile.php">My Profile</a>
                        <a href="orders.php">My Orders</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </li>
            <?php else: ?>
                <li><a id="login-btn" href="login.php" class="login-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>">Login</a></li>
            <?php endif; ?>
            <li><a href="#" id="close" class="close-btn" title="Close"><i class="fa-solid fa-xmark"></i></a></li>
        </ul>
    </div>
    
    <div id="mobile">
        <a href="bag.php" class="mobile-bag" title="Shopping Bag">
            <i class="fa-solid fa-bag-shopping"></i>
            <span id="mobile-bag-count">
                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
            </span>
        </a>
        <i id="bar" class="fa-solid fa-bars"></i>
    </div>
</section>

<style>
    .user-menu {
        position: relative;
    }
    
    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background-color: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 5px;
        min-width: 150px;
        z-index: 1000;
    }
    
    .user-menu:hover .dropdown-menu {
        display: block;
    }
    
    .dropdown-menu a {
        display: block;
        padding: 10px 15px;
        color: #333;
        text-decoration: none;
        transition: background-color 0.3s;
    }
    
    .dropdown-menu a:hover {
        background-color: #f5f5f5;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');
    let searchTimeout;

    // Function to fetch search suggestions
    function fetchSuggestions(query) {
        if (query.length < 2) {
            searchSuggestions.innerHTML = '';
            searchSuggestions.classList.remove('active');
            return;
        }

        fetch(`search_suggestions.php?q=${encodeURIComponent(query)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                searchSuggestions.innerHTML = '';
                
                if (data.length > 0) {
                    const suggestionsList = document.createElement('div');
                    suggestionsList.className = 'suggestions-list';
                    
                    data.forEach(item => {
                        const suggestionItem = document.createElement('a');
                        suggestionItem.href = `sproduct.php?pid=${item.id}`;
                        suggestionItem.className = 'suggestion-item';
                        
                        suggestionItem.innerHTML = `
                            <div class="suggestion-content">
                                <img src="${item.image}" alt="${item.name}" class="suggestion-img">
                                <div class="suggestion-details">
                                    <div class="suggestion-name">${item.name}</div>
                                    <div class="suggestion-brand">${item.brand}</div>
                                </div>
                            </div>
                        `;
                        
                        suggestionsList.appendChild(suggestionItem);
                    });
                    
                    searchSuggestions.appendChild(suggestionsList);
                    searchSuggestions.classList.add('active');
                } else {
                    searchSuggestions.classList.remove('active');
                }
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
                searchSuggestions.classList.remove('active');
            });
    }

    // Add event listener for input changes
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        
        // Add a small delay to prevent too many requests
        searchTimeout = setTimeout(() => {
            fetchSuggestions(query);
        }, 300);
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(event) {
        if (!searchInput.contains(event.target) && !searchSuggestions.contains(event.target)) {
            searchSuggestions.innerHTML = '';
            searchSuggestions.classList.remove('active');
        }
    });

    // Show suggestions when focusing on the search input
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length >= 2) {
            fetchSuggestions(this.value.trim());
        }
    });
    
    // Debug: Log when the script is loaded
    console.log('Search functionality initialized');
});
</script>
