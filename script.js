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

// Add To Bag Buttons

// const addToBag = document.getElementById("add-to-bag");
// const spAddToBag = document.getElementById("shop-add-to-bag");

// if (addToBag) {
//   addToBag.onclick = function () {
//     var bagCount = document.getElementById("bag-item-count");
//     bagCount.textContent = parseInt(bagCount.textContent) + 1;
//   };
// }

// if (spAddToBag) {
//   spAddToBag.onclick = function () {
//     var spBagCount = document.getElementById("shop-bag-item-count");
//     spBagCount.textContent = parseInt(spBagCount.textContent) + 1;
//   };
// }

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

// Make sure to call this when page loads
$(document).ready(function() {
  // Load cart count immediately when page loads
  load_cart_item_number();

  // Update cart when adding items
  $(".addItemBtn").click(function(e) {
    e.preventDefault();
    var $form = $(this).closest(".form-submit");
    var pid = $form.find(".pid").val();
    var pname = $form.find(".pname").val();
    var pprice = $form.find(".pprice").val();
    var pimage = $form.find(".pimage").val();
    var pcode = $form.find(".pcode").val();

    $.ajax({
      url: "action.php",
      method: "post",
      data: {
        pid: pid,
        pname: pname,
        pprice: pprice,
        pimage: pimage,
        pcode: pcode
      },
      success: function(response) {
        $("#message").html(response);
        window.scrollTo(0,1050);
        load_cart_item_number(); // Reload cart count after adding item
      }
    });
  });
});