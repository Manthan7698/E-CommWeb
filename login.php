<?php
session_start();

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/style.css" />
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
  <style>
    .error-message {
      color: #ff3333;
      background-color: #ffe6e6;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
      text-align: center;
    }
    .success-message {
      color: #009933;
      background-color: #e6ffe6;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
      text-align: center;
    }
  </style>
</head>

<body>
    <?php include 'header.php'; ?>

  <div class="container" id="container">
    <div class="sign-up">
      <form action="register.php" method="POST">
        <h3>Create Account</h3>
        <div class="icons">
          <a href="#" class="icon" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" class="icon" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="icon" title="Google"><i class="fa-brands fa-google"></i></a>
          <a href="#" class="icon" title="GitHub"><i class="fa-brands fa-github"></i></a>
        </div>
        <span>or use email for registration</span>
        <input type="text" name="name" placeholder="Name" required />
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <button type="submit">Sign Up</button>
      </form>
    </div>
    <div class="sign-in">
      <form action="auth.php" method="POST">
        <h3>Sign In</h3>
        <div class="icons">
          <a href="#" class="icon" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" class="icon" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" class="icon" title="Google"><i class="fa-brands fa-google"></i></a>
          <a href="#" class="icon" title="GitHub"><i class="fa-brands fa-github"></i></a>
        </div>
        <span>or use email password</span>
        
        <?php
        // Display error messages
        if (isset($_SESSION['errors'])) {
            foreach ($_SESSION['errors'] as $error) {
                echo '<div class="error-message">' . $error . '</div>';
            }
            unset($_SESSION['errors']);
        }
        
        // Display success message
        if (isset($_SESSION['success'])) {
            echo '<div class="success-message">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        
        <input type="email" name="email" placeholder="Email" required />
        <input type="password" name="password" placeholder="Password" required />
        <a href="#">Forgot password</a>
        <button type="submit">Sign In</button>
      </form>
    </div>
    <div class="toogle-container">
      <div class="toogle">
        <div class="toogle-panel toogle-left">
          <h3>Welcome User!</h3>
          <p>If you already have an account</p>
          <button id="login">Sign In</button>
        </div>
        <div class="toogle-panel toogle-right">
          <h3>Hello, User!</h3>
          <p>If you don't have an account</p>
          <button id="register">Sign Up</button>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php' ?>

  <script src="script.js"></script>
</body>

</html>