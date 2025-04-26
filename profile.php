<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$success_message = '';
$error_message = '';

// Handle form submission for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['update_profile'])) {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        // Handle profile picture upload
        $profile_picture = $user_data['profile_picture'];
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['profile_picture']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed)) {
                $new_filename = uniqid() . '.' . $ext;
                $upload_path = 'uploads/profile_pictures/' . $new_filename;
                
                if (!file_exists('uploads/profile_pictures')) {
                    mkdir('uploads/profile_pictures', 0777, true);
                }
                
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                    $profile_picture = $new_filename;
                }
            }
        }
        
        // Validate input
        $errors = [];
        
        if (empty($name)) {
            $errors[] = "Name is required";
        }
        
        if (empty($username)) {
            $errors[] = "Username is required";
        }
        
        if (empty($email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
        
        // Check if username or email already exists for another user
        $check_user = "SELECT * FROM users WHERE (username = '$username' OR email = '$email') AND id != $user_id";
        $result = mysqli_query($conn, $check_user);
        
        if (mysqli_num_rows($result) > 0) {
            $errors[] = "Username or email already exists for another user";
        }
        
        // If password change is requested
        if (!empty($current_password)) {
            // Get current user data
            $user_query = "SELECT * FROM users WHERE id = $user_id";
            $user_result = mysqli_query($conn, $user_query);
            $user_data = mysqli_fetch_assoc($user_result);
            
            // Verify current password
            if (!password_verify($current_password, $user_data['password'])) {
                $errors[] = "Current password is incorrect";
            }
            
            // Validate new password
            if (empty($new_password)) {
                $errors[] = "New password is required";
            } elseif (strlen($new_password) < 6) {
                $errors[] = "New password must be at least 6 characters";
            }
            
            if ($new_password !== $confirm_password) {
                $errors[] = "New passwords do not match";
            }
        }
        
        // If no errors, proceed with update
        if (empty($errors)) {
            // Update user information
            $update_query = "UPDATE users SET 
                name = '$name',
                username = '$username',
                email = '$email',
                phone = '$phone',
                profile_picture = '$profile_picture'";
            
            // Add password update if provided
            if (!empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_query .= ", password = '$hashed_password'";
            }
            
            $update_query .= " WHERE id = $user_id";
            
            if (mysqli_query($conn, $update_query)) {
                // Update session variables
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                
                $success_message = "Profile updated successfully!";
            } else {
                $error_message = "Error updating profile: " . mysqli_error($conn);
            }
        } else {
            $error_message = implode("<br>", $errors);
        }
    }
    
    // Handle address form submission
    if (isset($_POST['add_address'])) {
        $address_type = mysqli_real_escape_string($conn, $_POST['address_type']);
        $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address_line1 = mysqli_real_escape_string($conn, $_POST['address_line1']);
        $address_line2 = mysqli_real_escape_string($conn, $_POST['address_line2']);
        $city = mysqli_real_escape_string($conn, $_POST['city']);
        $state = mysqli_real_escape_string($conn, $_POST['state']);
        $zip_code = mysqli_real_escape_string($conn, $_POST['zip_code']);
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $is_default = isset($_POST['is_default']) ? 1 : 0;
        
        // If this is set as default, unset other defaults of the same type
        if ($is_default) {
            $reset_default = "UPDATE user_addresses SET is_default = 0 
                            WHERE user_id = $user_id AND address_type = '$address_type'";
            mysqli_query($conn, $reset_default);
        }
        
        $insert_address = "INSERT INTO user_addresses 
            (user_id, address_type, full_name, phone, address_line1, address_line2, 
            city, state, zip_code, country, is_default) 
            VALUES 
            ($user_id, '$address_type', '$full_name', '$phone', '$address_line1', 
            '$address_line2', '$city', '$state', '$zip_code', '$country', $is_default)";
        
        if (mysqli_query($conn, $insert_address)) {
            $success_message = "Address added successfully!";
        } else {
            $error_message = "Error adding address: " . mysqli_error($conn);
        }
    }
}

// Get user data
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

// Get user addresses
$addresses_query = "SELECT * FROM user_addresses WHERE user_id = $user_id ORDER BY is_default DESC, created_at DESC";
$addresses_result = mysqli_query($conn, $addresses_query);

// Initialize addresses array
$addresses = [];
if ($addresses_result) {
    while ($address = mysqli_fetch_assoc($addresses_result)) {
        $addresses[] = $address;
    }
}

mysqli_close($conn);
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
  <title>My Profile</title>
  <style>
    .profile-container {
      max-width: 1200px;
      margin: 50px auto;
      padding: 30px;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
    
    .profile-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .profile-header h1 {
      color: #333;
      margin-bottom: 10px;
    }
    
    .profile-content {
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 30px;
    }
    
    .profile-picture {
      text-align: center;
    }
    
    .profile-picture img {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      object-fit: cover;
      margin-bottom: 20px;
    }
    
    .profile-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
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
    .form-group textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }
    
    .form-group input:focus,
    .form-group textarea:focus {
      border-color: #088178;
      outline: none;
    }
    
    .password-section {
      grid-column: span 2;
      border-top: 1px solid #eee;
      padding-top: 20px;
      margin-top: 20px;
    }
    
    .password-section h3 {
      margin-bottom: 20px;
      color: #333;
    }
    
    .btn-container {
      grid-column: span 2;
      text-align: center;
      margin-top: 20px;
    }
    
    .btn-update {
      background-color: #088178;
      color: white;
      border: none;
      padding: 12px 30px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
    }
    
    .btn-update:hover {
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
    
    .addresses-section {
      margin-top: 40px;
      grid-column: span 2;
    }
    
    .addresses-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    
    .addresses-header h2 {
      color: #333;
    }
    
    .add-address-btn {
      background-color: #088178;
      color: white;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
    }
    
    .addresses-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }
    
    .address-card {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 5px;
      border: 1px solid #eee;
    }
    
    .address-card h3 {
      margin-bottom: 10px;
      color: #333;
    }
    
    .address-card p {
      margin: 5px 0;
      color: #666;
    }
    
    .address-actions {
      margin-top: 15px;
      display: flex;
      gap: 10px;
    }
    
    .address-actions button {
      padding: 5px 10px;
      border: none;
      border-radius: 3px;
      cursor: pointer;
      font-size: 12px;
    }
    
    .edit-btn {
      background-color: #088178;
      color: white;
    }
    
    .delete-btn {
      background-color: #dc3545;
      color: white;
    }
    
    .default-badge {
      display: inline-block;
      background-color: #28a745;
      color: white;
      padding: 3px 8px;
      border-radius: 3px;
      font-size: 12px;
      margin-left: 10px;
    }
    
    .address-form {
      background-color: #f9f9f9;
      padding: 20px;
      border-radius: 10px;
      margin-top: 20px;
    }
    
    .address-form h3 {
      margin-bottom: 20px;
      color: #333;
    }
    
    .form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 15px;
    }
    
    .form-row .form-group {
      flex: 1;
    }
    
    .default-address-option {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 15px 0;
    }
    
    .default-address-option select {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      width: 200px;
    }
    
    .default-address-option label {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }
    
    .default-address-option input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
    }
    
    .address-form-buttons {
      display: flex;
      justify-content: flex-start;
      gap: 10px;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <?php include 'header.php'; ?>

  <div class="profile-container">
    <div class="profile-header">
      <h1>My Profile</h1>
      <p>Manage your account information</p>
    </div>
    
    <?php if (!empty($success_message)): ?>
      <div class="message success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
      <div class="message error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <div class="profile-content">
      <div class="profile-picture">
        <img src="uploads/profile_pictures/<?php echo isset($user_data['profile_picture']) ? $user_data['profile_picture'] : 'default.jpg'; ?>" alt="Profile Picture">
        <form method="POST" action="profile.php" enctype="multipart/form-data" class="profile-picture-form">
          <input type="hidden" name="update_profile" value="1">
          <input type="file" name="profile_picture" accept="image/*" class="profile-picture-input">
          <button type="submit" class="btn-update">Upload Picture</button>
        </form>
      </div>
      
      <form method="POST" action="profile.php" class="profile-form" enctype="multipart/form-data">
        <input type="hidden" name="update_profile" value="1">
        
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user_data['username'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
        </div>
        
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>">
        </div>
        
        <div class="password-section">
          <h3>Change Password</h3>
          <p>Leave blank if you don't want to change your password</p>
          
          <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password">
          </div>
          
          <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password">
          </div>
          
          <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password">
          </div>
        </div>
        
        <div class="btn-container">
          <button type="submit" class="btn-update">Update Profile</button>
        </div>
      </form>
      
      <div class="addresses-section">
        <div class="addresses-header">
          <h2>Saved Addresses</h2>
          <button class="add-address-btn" onclick="showAddressForm()">Add New Address</button>
        </div>
        
        <div class="addresses-grid">
          <?php if (!empty($addresses)): ?>
            <?php foreach ($addresses as $address): ?>
              <div class="address-card">
                <h3>
                  <?php echo ucfirst($address['address_type']); ?> Address
                  <?php if ($address['is_default']): ?>
                    <span class="default-badge">Default</span>
                  <?php endif; ?>
                </h3>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($address['full_name']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($address['phone']); ?></p>
                <p><strong>Address:</strong> <?php echo htmlspecialchars($address['address_line1']); ?></p>
                <?php if (!empty($address['address_line2'])): ?>
                  <p><?php echo htmlspecialchars($address['address_line2']); ?></p>
                <?php endif; ?>
                <p>
                  <?php echo htmlspecialchars($address['city']); ?>, 
                  <?php echo htmlspecialchars($address['state']); ?> 
                  <?php echo htmlspecialchars($address['zip_code']); ?>
                </p>
                <p><?php echo htmlspecialchars($address['country']); ?></p>
                <div class="address-actions">
                  <button class="edit-btn">Edit</button>
                  <button class="delete-btn">Delete</button>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="no-addresses">
              <p>No addresses saved yet. Add your first address below.</p>
            </div>
          <?php endif; ?>
        </div>
        
        <div id="address-form" style="display: none;">
          <h3>Add New Address</h3>
          <form method="POST" action="profile.php" class="address-form">
            <input type="hidden" name="add_address" value="1">
            
            <div class="form-group">
              <label>Address Type</label>
              <select name="address_type" required>
                <option value="shipping">Shipping Address</option>
                <option value="billing">Billing Address</option>
              </select>
            </div>
            
            <div class="form-group">
              <label>Full Name</label>
              <input type="text" name="full_name" required>
            </div>
            
            <div class="form-group">
              <label>Phone Number</label>
              <input type="tel" name="phone" required>
            </div>
            
            <div class="form-group">
              <label>Address Line 1</label>
              <input type="text" name="address_line1" required>
            </div>
            
            <div class="form-group">
              <label>Address Line 2</label>
              <input type="text" name="address_line2">
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>City</label>
                <input type="text" name="city" required>
              </div>
              <div class="form-group">
                <label>State</label>
                <input type="text" name="state" required>
              </div>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label>ZIP Code</label>
                <input type="text" name="zip_code" required>
              </div>
              <div class="form-group">
                <label>Country</label>
                <input type="text" name="country" required>
              </div>
            </div>
            
            <div class="default-address-option">
              <label>
                <input type="checkbox" name="is_default" id="is_default">
                Set as default address
              </label>
              <select name="default_type" id="default_type" style="display: none;">
                <option value="shipping">Shipping Address</option>
                <option value="billing">Billing Address</option>
              </select>
            </div>
            
            <div class="address-form-buttons">
              <button type="submit" class="btn-update">Save Address</button>
              <button type="button" class="btn-update" onclick="hideAddressForm()">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    function showAddressForm() {
      document.getElementById('address-form').style.display = 'block';
    }
    
    function hideAddressForm() {
      document.getElementById('address-form').style.display = 'none';
    }

    // Handle default address checkbox
    document.getElementById('is_default').addEventListener('change', function() {
      const defaultTypeSelect = document.getElementById('default_type');
      defaultTypeSelect.style.display = this.checked ? 'block' : 'none';
    });
  </script>
</body>

</html> 