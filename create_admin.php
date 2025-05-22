<?php
include 'config.php';

// Admin user details
$name = "Admin User";
$email = "admin@cara.com";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$role = "admin";

// Check if admin already exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // Insert admin user
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $password, $role);
    
    if (mysqli_stmt_execute($stmt)) {
        echo "Admin user created successfully!<br>";
        echo "Email: admin@cara.com<br>";
        echo "Password: admin123<br>";
        echo "<a href='login.php'>Go to Login</a>";
    } else {
        echo "Error creating admin user: " . mysqli_error($conn);
    }
} else {
    echo "Admin user already exists!<br>";
    echo "Email: admin@cara.com<br>";
    echo "Password: admin123<br>";
    echo "<a href='login.php'>Go to Login</a>";
}

mysqli_close($conn);
?> 