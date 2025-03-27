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

document.addEventListener("DOMContentLoaded", () => {
    const addItemBtns = document.querySelectorAll(".addItemBtn");

    addItemBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();

            const form = btn.closest(".form-submit");
            const pid = form.querySelector(".pid").value;
            const pname = form.querySelector(".pname").value;
            const pprice = form.querySelector(".pprice").value;
            const pimage = form.querySelector(".pimage").value;
            const pcode = form.querySelector(".pcode").value;
            


            fetch("add-to-cart.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `pid=${pid}&pname=${pname}&pprice=${pprice}&pimage=${pimage}&pcode=${pcode}`,
            })
                .then((response) => response.text())
                .then((message) => {
                    alert(message); // Display success or error message
                })
                .catch((error) => console.error("Error:", error));
        });
    });
});
