<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
require 'config.php'; // Add database connection

$client = new Google\Client();
$client->setClientId("979257647670-62bvrvca3c6211mtee5gr12tkd2ngctp.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("http://localhost/E-CommWeb/google_callback.php");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (!isset($token['error'])) {
        // Get user profile data
        $client->setAccessToken($token['access_token']);
        $service = new Google\Service\Oauth2($client);
        $user = $service->userinfo->get();

        // Add logging for the picture URL
        error_log("Google User Picture URL: " . print_r($user->picture, true));

        // Check if user exists in database
        $google_id = mysqli_real_escape_string($conn, $user->id);
        $email = mysqli_real_escape_string($conn, $user->email);
        $name = mysqli_real_escape_string($conn, $user->name);
        $picture = mysqli_real_escape_string($conn, $user->picture);

        $check_user = "SELECT * FROM users WHERE google_id = '$google_id' OR email = '$email'";
        $result = mysqli_query($conn, $check_user);

        if (mysqli_num_rows($result) == 0) {
            // User doesn't exist, create new user
            $username = strtolower(str_replace(' ', '', $name)) . rand(100, 999);
            $insert_user = "INSERT INTO users (google_id, name, username, email, profile_picture, is_google_user) 
                           VALUES ('$google_id', '$name', '$username', '$email', '$picture', 1)";
            
            if (mysqli_query($conn, $insert_user)) {
                $user_id = mysqli_insert_id($conn);
            } else {
                error_log("Error creating user: " . mysqli_error($conn));
                header('Location: login.php?error=db_error');
                exit();
            }
        } else {
            // User exists, update their info
            $user_data = mysqli_fetch_assoc($result);
            $user_id = $user_data['id'];
            
            $update_user = "UPDATE users SET 
                           name = '$name',
                           profile_picture = '$picture',
                           is_google_user = 1
                           WHERE id = $user_id";
            mysqli_query($conn, $update_user);
        }

        // Store user data in session
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_picture'] = $user->picture;
        $_SESSION['logged_in'] = true;
        $_SESSION['is_google_user'] = true;
        
        mysqli_close($conn);
        header('Location: index.php');
        exit();
    }
}

// If there's an error, redirect to login page
header('Location: login.php');
exit();
?> 