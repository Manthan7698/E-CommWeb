<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'cara';

// Create persistent connection
$conn = new mysqli('p:' . $db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to ensure proper encoding
$conn->set_charset("utf8mb4");

// Function to get a valid connection
function getConnection() {
    global $conn;
    if (!$conn || $conn->connect_errno) {
        $conn = new mysqli('p:' . $GLOBALS['db_host'], $GLOBALS['db_user'], $GLOBALS['db_pass'], $GLOBALS['db_name']);
        $conn->set_charset("utf8mb4");
    }
    return $conn;
}
?>
