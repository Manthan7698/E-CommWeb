<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $address_type = mysqli_real_escape_string($conn, $_POST['address_type']);
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $address_line1 = mysqli_real_escape_string($conn, $_POST['address_line1']);
    $address_line2 = mysqli_real_escape_string($conn, $_POST['address_line2']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $state = mysqli_real_escape_string($conn, $_POST['state']);
    $zip_code = mysqli_real_escape_string($conn, $_POST['zip_code']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $is_default = isset($_POST['is_default']) ? 1 : 0;
    
    // Validate required fields
    $errors = [];
    
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($address_line1)) {
        $errors[] = "Address line 1 is required";
    }
    
    if (empty($city)) {
        $errors[] = "City is required";
    }
    
    if (empty($state)) {
        $errors[] = "State is required";
    }
    
    if (empty($zip_code)) {
        $errors[] = "ZIP code is required";
    }
    
    if (empty($country)) {
        $errors[] = "Country is required";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    // If this is set as default, unset other defaults of the same type
    if ($is_default) {
        $unset_default_query = "UPDATE user_addresses 
                              SET is_default = 0 
                              WHERE user_id = $user_id 
                              AND address_type = '$address_type'";
        mysqli_query($conn, $unset_default_query);
    }
    
    if (empty($errors)) {
        // Insert new address
        $insert_query = "INSERT INTO user_addresses (
            user_id,
            address_type,
            full_name,
            address_line1,
            address_line2,
            city,
            state,
            zip_code,
            country,
            phone,
            is_default
        ) VALUES (
            $user_id,
            '$address_type',
            '$full_name',
            '$address_line1',
            '$address_line2',
            '$city',
            '$state',
            '$zip_code',
            '$country',
            '$phone',
            $is_default
        )";
        
        if (mysqli_query($conn, $insert_query)) {
            $_SESSION['success'] = "Address added successfully!";
        } else {
            $_SESSION['error'] = "Error adding address: " . mysqli_error($conn);
        }
    } else {
        $_SESSION['error'] = implode("<br>", $errors);
    }
    
    mysqli_close($conn);
    header("Location: profile.php");
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
  <title>Add New Address</title>
  <style>
    .form-container {
      max-width: 600px;
      margin: 50px auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 500;
      color: #555;
    }
    
    .form-group input,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }
    
    .form-group input:focus,
    .form-group select:focus {
      border-color: #088178;
      outline: none;
    }
    
    .checkbox-group {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .checkbox-group input[type="checkbox"] {
      width: auto;
    }
    
    .btn-container {
      text-align: center;
      margin-top: 20px;
    }
    
    .btn-submit {
      background-color: #088178;
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
    }
    
    .btn-submit:hover {
      background-color: #066963;
    }
    
    .message {
      padding: 15px;
      border-radius: 5px;
      margin-bottom: 20px;
      text-align: center;
    }
    
    .success-message {
      background-color: #e6ffe6;
      color: #009933;
    }
    
    .error-message {
      background-color: #ffe6e6;
      color: #cc0000;
    }
  </style>
</head>

<body>
  <?php include 'header.php'; ?>

  <div class="form-container">
    <h1>Add New Address</h1>
    
    <?php if (isset($_SESSION['error'])): ?>
      <div class="message error-message"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="add_address.php">
      <div class="form-group">
        <label for="address_type">Address Type</label>
        <select id="address_type" name="address_type" required>
          <option value="shipping">Shipping Address</option>
          <option value="billing">Billing Address</option>
        </select>
      </div>
      
      <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" id="full_name" name="full_name" required>
      </div>
      
      <div class="form-group">
        <label for="address_line1">Address Line 1</label>
        <input type="text" id="address_line1" name="address_line1" required>
      </div>
      
      <div class="form-group">
        <label for="address_line2">Address Line 2 (Optional)</label>
        <input type="text" id="address_line2" name="address_line2">
      </div>
      
      <div class="form-group">
        <label for="city">City</label>
        <input type="text" id="city" name="city" required>
      </div>
      
      <div class="form-group">
        <label for="state">State</label>
        <input type="text" id="state" name="state" required>
      </div>
      
      <div class="form-group">
        <label for="zip_code">ZIP Code</label>
        <input type="text" id="zip_code" name="zip_code" required>
      </div>
      
      <div class="form-group">
        <label for="country">Country</label>
        <input type="text" id="country" name="country" required>
      </div>
      
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="tel" id="phone" name="phone" required>
      </div>
      
      <div class="form-group">
        <div class="checkbox-group">
          <input type="checkbox" id="is_default" name="is_default">
          <label for="is_default">Set as default address</label>
        </div>
      </div>
      
      <div class="btn-container">
        <button type="submit" class="btn-submit">Add Address</button>
      </div>
    </form>
  </div>

  <?php include 'footer.php'; ?>
</body>

</html> 