<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get current notification preferences
$check_query = "SELECT * FROM notification_preferences WHERE user_id = $user_id";
$check_result = mysqli_query($conn, $check_query);

// Prepare the update query
$email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
$sms_notifications = isset($_POST['sms_notifications']) ? 1 : 0;
$order_updates = isset($_POST['order_updates']) ? 1 : 0;
$promotional_offers = isset($_POST['promotional_offers']) ? 1 : 0;
$newsletter = isset($_POST['newsletter']) ? 1 : 0;

if (mysqli_num_rows($check_result) > 0) {
    // Update existing preferences
    $update_query = "UPDATE notification_preferences SET 
        email_notifications = $email_notifications,
        sms_notifications = $sms_notifications,
        order_updates = $order_updates,
        promotional_offers = $promotional_offers,
        newsletter = $newsletter
        WHERE user_id = $user_id";
} else {
    // Insert new preferences
    $update_query = "INSERT INTO notification_preferences (
        user_id,
        email_notifications,
        sms_notifications,
        order_updates,
        promotional_offers,
        newsletter
    ) VALUES (
        $user_id,
        $email_notifications,
        $sms_notifications,
        $order_updates,
        $promotional_offers,
        $newsletter
    )";
}

if (mysqli_query($conn, $update_query)) {
    $_SESSION['success'] = "Notification preferences updated successfully!";
} else {
    $_SESSION['error'] = "Error updating notification preferences: " . mysqli_error($conn);
}

mysqli_close($conn);
header("Location: profile.php");
exit();
?>