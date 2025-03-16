const bar = document.getElementById('bar');
const close = document.getElementById('close');
const nav = document.getElementById('navbar');

const container = document.getElementById("container");
const registerbtn = document.getElementById("register");
const loginbtn = document.getElementById("login");

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

if (registerbtn) { registerbtn.addEventListener("click", () => { 
  container.classList.add("active"); }); }

if (loginbtn){
  loginbtn.addEventListener("click", () => {
    container.classList.remove("active");
  });
}
