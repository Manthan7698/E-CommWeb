<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
    crossorigin="anonymous"
    referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet" />
  <title>Login Page</title>
</head>

<body>
    <?php include 'header.php'; ?>

  <div class="container" id="container">
    <div class="sign-up">
      <form>
        <h3>Create Account</h3>
        <div class="icons">
          <a href="#" class="icon" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" class="icon" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="icon" title="Google"><i class="fa-brands fa-google"></i></a>
          <a href="#" class="icon" title="GitHub"><i class="fa-brands fa-github"></i></a>
        </div>
        <span>or use email for registeration</span>
        <input type="text" placeholder="Name" />
        <input type="text" placeholder="Email" />
        <input type="password" placeholder="Password" />
        <button type="submit">Sign Up</button>
      </form>
    </div>
    <div class="sign-in">
      <form>
        <h3>Sign In</h3>
        <div class="icons">
          <a href="#" class="icon" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" class="icon" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="icon" title="Google"><i class="fa-brands fa-google"></i></a>
          <a href="#" class="icon" title="GitHub"><i class="fa-brands fa-github"></i></a>
        </div>
        <span>or use email password</span>
        <input type="text" placeholder="Email" />
        <input type="password" placeholder="Password" />
        <a href="#">Forgot password</a>
        <button type="submit">Sign In</button>
      </form>
    </div>
    <div class="toogle-container">
      <div class="toogle">
        <div class="toogle-panel toogle-left">
          <h3>Welcome User!</h3>
          <p>If you already have an account</p>
          <button class="hidden" id="login">Sign In</button>
        </div>
        <div class="toogle-panel toogle-right">
          <h3>Hello, User!</h3>
          <p>If you don't have an account</p>
          <button class="hidden" id="register">Sign Up</button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php' ?>

  <script src="script.js"></script>
</body>

</html>