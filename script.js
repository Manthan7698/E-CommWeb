const bar = document.getElementById('bar');
const close = document.getElementById('close');
const navContainer = document.querySelector('.nav-container');

if (bar) {
  bar.addEventListener('click', () => {
    navContainer.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent scrolling when menu is open
  });
}

if (close) {
  close.addEventListener('click', () => {
    navContainer.classList.remove('active');
    document.body.style.overflow = ''; // Re-enable scrolling
  });
}

// Close menu when clicking on a nav link
const navLinks = document.querySelectorAll('.nav-link');
navLinks.forEach(link => {
  link.addEventListener('click', () => {
    navContainer.classList.remove('active');
    document.body.style.overflow = '';
  });
});

// Close menu when clicking outside
document.addEventListener('click', (event) => {
  if (navContainer.classList.contains('active') && 
      !navContainer.contains(event.target) && 
      event.target !== bar) {
    navContainer.classList.remove('active');
    document.body.style.overflow = '';
  }
});

// Handle window resize
window.addEventListener('resize', () => {
  if (window.innerWidth > 799) {
    navContainer.classList.remove('active');
    document.body.style.overflow = '';
  }
});

// Login and Register Buttons

const container = document.getElementById("container");
const registerbtn = document.getElementById("register");
const loginbtn = document.getElementById("login");

if (registerbtn) {
  registerbtn.addEventListener("click", () => {
    container.classList.add("active");
  });
}

if (loginbtn) {
  loginbtn.addEventListener("click", () => {
    container.classList.remove("active");
  });
}

// Single Product Display

const MainImg = document.getElementById("MainImg");
const smallimg = document.getElementsByClassName("small-img");

if (smallimg.length > 0) {
  for (let i = 0; i < smallimg.length; i++) {
    smallimg[i].onclick = function () {
      MainImg.src = smallimg[i].src;
    };
  }
}

// Function to update cart count
function updateCartCount() {
    fetch('get_cart_count.php')
        .then(response => response.json())
        .then(data => {
            const cartCount = document.getElementById('bag-item-count');
            const mobileCartCount = document.getElementById('mobile-bag-count');
            
            if (cartCount) {
                cartCount.textContent = data.count;
            }
            
            if (mobileCartCount) {
                mobileCartCount.textContent = data.count;
            }
        })
        .catch(error => console.error('Error:', error));
}

// Update cart count when page loads
document.addEventListener('DOMContentLoaded', updateCartCount);

// Create and show notification
function showNotification(message, isSuccess) {
    // Remove any existing notifications
    const existingNotifications = document.querySelectorAll('.notification');
    existingNotifications.forEach(notification => {
        notification.remove();
    });

    const notification = document.createElement('div');
    notification.className = `notification ${isSuccess ? 'success' : 'error'}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds with fade out animation
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => {
            notification.remove();
        }, 500); // Wait for fade out animation to complete
    }, 2500);
}

// Add to Cart Functionality
const addToCartButtons = document.querySelectorAll('.addItemBtn');

addToCartButtons.forEach(button => {
    // Check if product is out of stock
    const form = button.closest('.form-submit');
    const productId = form.querySelector('.pid').value;
    
    // Check if the product has an out-of-stock badge
    const productCard = button.closest('.pro');
    const isOutOfStock = productCard && productCard.querySelector('.out-of-stock-badge');
    
    if (isOutOfStock) {
        button.disabled = true;
        button.style.opacity = '0.5';
        button.style.cursor = 'not-allowed';
        button.title = 'Out of Stock';
    }

    button.addEventListener('click', (e) => {
        e.preventDefault();
        
        // Don't proceed if button is disabled
        if (button.disabled) {
            return;
        }

        const form = button.closest('.form-submit');
        const productName = form.querySelector('.pname').value;
        const productPrice = form.querySelector('.pprice').value;
        const productImg = form.querySelector('.pimage').value;
        const quantity = form.querySelector('#quantity') ? form.querySelector('#quantity').value : 1;
        const productCode = form.querySelector('.pcode').value;
        const productBrand = form.querySelector('.pbrand').value;
        const productDetails = form.querySelector('.pdetails').value;
        const productSize = form.querySelector('.psize') ? form.querySelector('.psize').value : '';

        // Check if we're on the product page and size is required
        const isProductPage = document.getElementById('Size') !== null;
        if (isProductPage && !productSize) {
            showNotification('Please select a size before adding to cart', false);
            return;
        }

        if (productId && productName && productPrice && productImg && quantity && productCode && productBrand && productDetails) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `&id=${productId}&product_name=${productName}&product_price=${productPrice}&product_img=${productImg}&quantity=${quantity}&product_code=${productCode}&product_brand=${productBrand}&product_details=${productDetails}&product_size=${productSize}`,
            })
            .then(response => response.json())
            .then(data => {
                showNotification(data.message, data.success);
                if (data.success) {
                    updateCartCount();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error adding product to bag', false);
            });
        } else {
            showNotification('Product details are missing', false);
        }
    });
});

