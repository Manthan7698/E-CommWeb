const bar = document.getElementById('bar');
const close = document.getElementById('close');
const nav = document.getElementById('navbar');

if (bar) {
  bar.addEventListener('click', () => {
    nav.classList.toggle('active');
  });
}

if (close) {
  close.addEventListener('click', () => {
    nav.classList.remove('active');
  });
}

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
            if (cartCount) {
                cartCount.textContent = data.count;
            }
        })
        .catch(error => console.error('Error:', error));
}

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
    button.addEventListener('click', (e) => {
        e.preventDefault();
        const form = button.closest('.form-submit');
        const productId = form.querySelector('.pid').value;
        const productName = form.querySelector('.pname').value;
        const productPrice = form.querySelector('.pprice').value;
        const productImg = form.querySelector('.pimage').value;
        const quantity = form.querySelector('#quantity') ? form.querySelector('#quantity').value : 1;
        const productCode = form.querySelector('.pcode').value;
        const productBrand = form.querySelector('.pbrand').value;
        const productDetails = form.querySelector('.pdetails').value;

        if (productId && productName && productPrice && productImg && quantity && productCode && productBrand && productDetails) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `&id=${productId}&product_name=${productName}&product_price=${productPrice}&product_img=${productImg}&quantity=${quantity}&product_code=${productCode}&product_brand=${productBrand}&product_details=${productDetails}`,
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

// Update cart count when page loads
document.addEventListener('DOMContentLoaded', updateCartCount);

