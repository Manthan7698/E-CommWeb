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
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    // Check if email already exists for another user
    $check_email = "SELECT * FROM users WHERE email = '$email' AND id != $user_id";
    $result = mysqli_query($conn, $check_email);
    
    if (mysqli_num_rows($result) > 0) {
        $errors[] = "Email already exists for another user";
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
        $update_query = "UPDATE users SET name = '$name', email = '$email', phone = '$phone'";
        
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

// Get user data
$user_query = "SELECT * FROM users WHERE id = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user_data = mysqli_fetch_assoc($user_result);

// Get user addresses
$addresses_query = "SELECT * FROM user_addresses WHERE user_id = $user_id";
$addresses_result = mysqli_query($conn, $addresses_query);

// Get wishlist items
$wishlist_query = "SELECT w.*, p.product_name, p.product_price, p.product_img 
                  FROM wishlist w 
                  JOIN products p ON w.product_id = p.id 
                  WHERE w.user_id = $user_id";
$wishlist_result = mysqli_query($conn, $wishlist_query);

// Get saved carts
$saved_carts_query = "SELECT * FROM saved_carts WHERE user_id = $user_id";
$saved_carts_result = mysqli_query($conn, $saved_carts_query);

// Get payment methods
$payment_methods_query = "SELECT * FROM payment_methods WHERE user_id = $user_id";
$payment_methods_result = mysqli_query($conn, $payment_methods_query);

// Get subscriptions
$subscriptions_query = "SELECT * FROM subscriptions WHERE user_id = $user_id";
$subscriptions_result = mysqli_query($conn, $subscriptions_query);

// Get reward points
$reward_points_query = "SELECT SUM(points) as total_points FROM reward_points 
                       WHERE user_id = $user_id AND status = 'active'";
$reward_points_result = mysqli_query($conn, $reward_points_query);
$reward_points = mysqli_fetch_assoc($reward_points_result)['total_points'] ?? 0;

// Get coupons
$coupons_query = "SELECT * FROM coupons WHERE user_id = $user_id AND status = 'active'";
$coupons_result = mysqli_query($conn, $coupons_query);

// Get notification preferences
$notifications_query = "SELECT * FROM notification_preferences WHERE user_id = $user_id";
$notifications_result = mysqli_query($conn, $notifications_query);
$notifications = mysqli_fetch_assoc($notifications_result);

// Get recent login history
$login_history_query = "SELECT * FROM login_history 
                       WHERE user_id = $user_id 
                       ORDER BY login_time DESC 
                       LIMIT 5";
$login_history_result = mysqli_query($conn, $login_history_query);

// Get support tickets
$tickets_query = "SELECT * FROM support_tickets 
                 WHERE user_id = $user_id 
                 ORDER BY created_at DESC 
                 LIMIT 5";
$tickets_result = mysqli_query($conn, $tickets_query);

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
    
    .profile-tabs {
      display: flex;
      border-bottom: 1px solid #eee;
      margin-bottom: 30px;
    }
    
    .profile-tab {
      padding: 15px 30px;
      cursor: pointer;
      border-bottom: 2px solid transparent;
      transition: all 0.3s;
    }
    
    .profile-tab.active {
      border-bottom-color: #088178;
      color: #088178;
    }
    
    .profile-tab:hover {
      color: #088178;
    }
    
    .tab-content {
      display: none;
    }
    
    .tab-content.active {
      display: block;
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
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
    }
    
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      border-color: #088178;
      outline: none;
    }
    
    .btn-container {
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
    
    .card {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-bottom: 20px;
    }
    
    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    
    .card-title {
      font-size: 18px;
      font-weight: 600;
      color: #333;
    }
    
    .card-action {
      color: #088178;
      text-decoration: none;
      font-size: 14px;
    }
    
    .address-list,
    .wishlist-items,
    .saved-carts,
    .payment-methods,
    .subscriptions,
    .coupons,
    .login-history,
    .support-tickets {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 20px;
    }
    
    .address-item,
    .wishlist-item,
    .saved-cart,
    .payment-method,
    .subscription,
    .coupon,
    .login-entry,
    .ticket {
      background-color: #f9f9f9;
      padding: 15px;
      border-radius: 5px;
    }
    
    .reward-points {
      text-align: center;
      padding: 30px;
      background-color: #f0f8ff;
      border-radius: 8px;
    }
    
    .points-value {
      font-size: 36px;
      font-weight: 700;
      color: #088178;
      margin: 10px 0;
    }
    
    .notification-preferences {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }
    
    .preference-item {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .preference-item input[type="checkbox"] {
      width: auto;
    }
  </style>
</head>

<body>
  <?php include 'header.php'; ?>

  <div class="profile-container">
    <div class="profile-header">
      <h1>My Profile</h1>
      <p>Manage your account information and preferences</p>
    </div>
    
    <?php if (!empty($success_message)): ?>
      <div class="message success-message"><?php echo $success_message; ?></div>
    <?php endif; ?>
    
    <?php if (!empty($error_message)): ?>
      <div class="message error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>
    
    <div class="profile-tabs">
      <div class="profile-tab active" data-tab="personal">Personal Information</div>
      <div class="profile-tab" data-tab="addresses">Addresses</div>
      <div class="profile-tab" data-tab="wishlist">Wishlist</div>
      <div class="profile-tab" data-tab="saved-carts">Saved Carts</div>
      <div class="profile-tab" data-tab="payment">Payment Methods</div>
      <div class="profile-tab" data-tab="subscriptions">Subscriptions</div>
      <div class="profile-tab" data-tab="rewards">Rewards & Coupons</div>
      <div class="profile-tab" data-tab="notifications">Notifications</div>
      <div class="profile-tab" data-tab="security">Security</div>
      <div class="profile-tab" data-tab="support">Support</div>
    </div>
    
    <!-- Personal Information Tab -->
    <div class="tab-content active" id="personal">
      <form method="POST" action="profile.php" class="profile-form">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" value="<?php echo $user_data['name']; ?>" required>
        </div>
        
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" value="<?php echo $user_data['email']; ?>" required>
        </div>
        
        <div class="form-group">
          <label for="phone">Phone Number</label>
          <input type="tel" id="phone" name="phone" value="<?php echo $user_data['phone'] ?? ''; ?>">
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
    </div>
    
    <!-- Addresses Tab -->
    <div class="tab-content" id="addresses">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Saved Addresses</h3>
          <a href="#" class="card-action">Add New Address</a>
        </div>
        <div class="address-list">
          <?php while ($address = mysqli_fetch_assoc($addresses_result)): ?>
            <div class="address-item">
              <h4><?php echo $address['address_type'] == 'shipping' ? 'Shipping Address' : 'Billing Address'; ?></h4>
              <p><?php echo $address['full_name']; ?></p>
              <p><?php echo $address['address_line1']; ?></p>
              <?php if (!empty($address['address_line2'])): ?>
                <p><?php echo $address['address_line2']; ?></p>
              <?php endif; ?>
              <p><?php echo $address['city'] . ', ' . $address['state'] . ' ' . $address['zip_code']; ?></p>
              <p><?php echo $address['country']; ?></p>
              <p>Phone: <?php echo $address['phone']; ?></p>
              <?php if ($address['is_default']): ?>
                <span class="default-badge">Default</span>
              <?php endif; ?>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
    
    <!-- Wishlist Tab -->
    <div class="tab-content" id="wishlist">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">My Wishlist</h3>
        </div>
        <div class="wishlist-items">
          <?php while ($item = mysqli_fetch_assoc($wishlist_result)): ?>
            <div class="wishlist-item">
              <img src="<?php echo $item['product_img']; ?>" alt="<?php echo $item['product_name']; ?>">
              <h4><?php echo $item['product_name']; ?></h4>
              <p class="price">$<?php echo $item['product_price']; ?></p>
              <button class="btn-update">Add to Cart</button>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
    
    <!-- Saved Carts Tab -->
    <div class="tab-content" id="saved-carts">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Saved Carts</h3>
        </div>
        <div class="saved-carts">
          <?php while ($cart = mysqli_fetch_assoc($saved_carts_result)): ?>
            <div class="saved-cart">
              <h4><?php echo $cart['cart_name']; ?></h4>
              <p>Created: <?php echo date('F j, Y', strtotime($cart['created_at'])); ?></p>
              <button class="btn-update">Load Cart</button>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
    
    <!-- Payment Methods Tab -->
    <div class="tab-content" id="payment">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Payment Methods</h3>
          <a href="#" class="card-action">Add New Payment Method</a>
        </div>
        <div class="payment-methods">
          <?php while ($method = mysqli_fetch_assoc($payment_methods_result)): ?>
            <div class="payment-method">
              <h4><?php echo ucfirst($method['payment_type']); ?></h4>
              <p>Card Number: **** **** **** <?php echo substr($method['card_number'], -4); ?></p>
              <p>Expires: <?php echo date('m/Y', strtotime($method['expiry_date'])); ?></p>
              <?php if ($method['is_default']): ?>
                <span class="default-badge">Default</span>
              <?php endif; ?>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
    
    <!-- Subscriptions Tab -->
    <div class="tab-content" id="subscriptions">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">My Subscriptions</h3>
        </div>
        <div class="subscriptions">
          <?php while ($subscription = mysqli_fetch_assoc($subscriptions_result)): ?>
            <div class="subscription">
              <h4><?php echo $subscription['plan_name']; ?></h4>
              <p>Status: <?php echo ucfirst($subscription['status']); ?></p>
              <p>Start Date: <?php echo date('F j, Y', strtotime($subscription['start_date'])); ?></p>
              <?php if ($subscription['end_date']): ?>
                <p>End Date: <?php echo date('F j, Y', strtotime($subscription['end_date'])); ?></p>
              <?php endif; ?>
              <p>Auto-renew: <?php echo $subscription['auto_renew'] ? 'Yes' : 'No'; ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
    
    <!-- Rewards & Coupons Tab -->
    <div class="tab-content" id="rewards">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Reward Points</h3>
        </div>
        <div class="reward-points">
          <h4>Available Points</h4>
          <div class="points-value"><?php echo $reward_points; ?></div>
          <p>Points can be redeemed for discounts on future purchases</p>
        </div>
      </div>
      
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Available Coupons</h3>
        </div>
        <div class="coupons">
          <?php while ($coupon = mysqli_fetch_assoc($coupons_result)): ?>
            <div class="coupon">
              <h4><?php echo $coupon['code']; ?></h4>
              <p><?php echo $coupon['discount_type'] == 'percentage' ? 
                $coupon['discount_value'] . '% off' : 
                '$' . $coupon['discount_value'] . ' off'; ?></p>
              <p>Expires: <?php echo date('F j, Y', strtotime($coupon['expiry_date'])); ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
    
    <!-- Notifications Tab -->
    <div class="tab-content" id="notifications">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Notification Preferences</h3>
        </div>
        <form action="update_notifications.php" method="POST">
          <div class="notification-preferences">
            <div class="preference-item">
              <input type="checkbox" id="email_notifications" name="email_notifications" 
                <?php echo $notifications['email_notifications'] ? 'checked' : ''; ?>>
              <label for="email_notifications">Email Notifications</label>
            </div>
            <div class="preference-item">
              <input type="checkbox" id="sms_notifications" name="sms_notifications"
                <?php echo $notifications['sms_notifications'] ? 'checked' : ''; ?>>
              <label for="sms_notifications">SMS Notifications</label>
            </div>
            <div class="preference-item">
              <input type="checkbox" id="order_updates" name="order_updates"
                <?php echo $notifications['order_updates'] ? 'checked' : ''; ?>>
              <label for="order_updates">Order Updates</label>
            </div>
            <div class="preference-item">
              <input type="checkbox" id="promotional_offers" name="promotional_offers"
                <?php echo $notifications['promotional_offers'] ? 'checked' : ''; ?>>
              <label for="promotional_offers">Promotional Offers</label>
            </div>
            <div class="preference-item">
              <input type="checkbox" id="newsletter" name="newsletter"
                <?php echo $notifications['newsletter'] ? 'checked' : ''; ?>>
              <label for="newsletter">Newsletter</label>
            </div>
          </div>
          <div class="btn-container">
            <button type="submit" class="btn-update">Save Preferences</button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Security Tab -->
    <div class="tab-content" id="security">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Recent Login Activity</h3>
        </div>
        <div class="login-history">
          <?php while ($login = mysqli_fetch_assoc($login_history_result)): ?>
            <div class="login-entry">
              <p><strong>Date:</strong> <?php echo date('F j, Y g:i A', strtotime($login['login_time'])); ?></p>
              <p><strong>IP Address:</strong> <?php echo $login['ip_address']; ?></p>
              <p><strong>Device:</strong> <?php echo $login['device_info']; ?></p>
              <p><strong>Status:</strong> <?php echo ucfirst($login['status']); ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
    
    <!-- Support Tab -->
    <div class="tab-content" id="support">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Recent Support Tickets</h3>
          <a href="#" class="card-action">Create New Ticket</a>
        </div>
        <div class="support-tickets">
          <?php while ($ticket = mysqli_fetch_assoc($tickets_result)): ?>
            <div class="ticket">
              <h4><?php echo $ticket['subject']; ?></h4>
              <p><strong>Status:</strong> <?php echo ucfirst($ticket['status']); ?></p>
              <p><strong>Priority:</strong> <?php echo ucfirst($ticket['priority']); ?></p>
              <p><strong>Created:</strong> <?php echo date('F j, Y', strtotime($ticket['created_at'])); ?></p>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    // Tab switching functionality
    document.querySelectorAll('.profile-tab').forEach(tab => {
      tab.addEventListener('click', () => {
        // Remove active class from all tabs and contents
        document.querySelectorAll('.profile-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding content
        tab.classList.add('active');
        document.getElementById(tab.dataset.tab).classList.add('active');
      });
    });
  </script>
</body>

</html> 