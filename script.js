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

// Database (Add to Cart)

function load_cart_item_number() {
  $.ajax({
    url: "action.php",
    method: "get",
    data: { cartItem: "cart_item" },
    success: function(response) {
      // Update all cart counters
      $("#bag-item-count").html(response);
      $("#shop-bag-item-count").html(response);
    }
  });
}

document.addEventListener("DOMContentLoaded", () => {
    const addItemBtns = document.querySelectorAll(".addItemBtn");
    const bagItemCount = document.getElementById("bag-item-count");

    addItemBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();

            const form = btn.closest(".form-submit");
            const pid = form.querySelector(".pid").value;
            const pname = form.querySelector(".pname").value;
            const pprice = form.querySelector(".pprice").value;
            const pimage = form.querySelector(".pimage").value;
            const pcode = form.querySelector(".pcode").value;

            // Send data to the server using fetch
            fetch("add_to_cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    pid,
                    pname,
                    pprice,
                    pimage,
                    pcode,
                }),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.status === "success") {
                        bagItemCount.textContent = data.cart_count; // Update cart count
                        alert("Item added to cart!");
                    } else {
                        alert("Failed to add item to cart.");
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });
    });
});