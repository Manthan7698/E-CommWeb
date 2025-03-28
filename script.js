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

    if (productId && productName && productPrice && productImg && quantity && productCode) {
      fetch('add_to_cart.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `&id=${productId}&product_name=${productName}&product_price=${productPrice}&product_img=${productImg}&quantity=${quantity}&product_code=${productCode}`,
      })
        .then(response => response.text())
        .then(data => {
          alert(data); // Display success or error message
          updateCartCount(); // Update cart count after adding item
        })
        .catch(error => {
          console.error('Error:', error);
        });
    } else {
      alert('Product details are missing.');
    }
  });
});

// Update cart count when page loads
document.addEventListener('DOMContentLoaded', updateCartCount);

