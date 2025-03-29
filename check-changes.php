<?php
header('Content-Type: application/json');

// Get the current file's last modification time
$currentFile = $_SERVER['HTTP_REFERER'] ?? '';
$currentFile = basename($currentFile);

if (file_exists($currentFile)) {
    $lastModified = filemtime($currentFile);
    
    // Store the last modification time in a session
    session_start();
    $sessionKey = 'last_modified_' . $currentFile;
    
    if (!isset($_SESSION[$sessionKey])) {
        $_SESSION[$sessionKey] = $lastModified;
        echo json_encode(['changed' => false]);
    } else {
        if ($_SESSION[$sessionKey] != $lastModified) {
            $_SESSION[$sessionKey] = $lastModified;
            echo json_encode(['changed' => true]);
        } else {
            echo json_encode(['changed' => false]);
        }
    }
} else {
    echo json_encode(['changed' => false]);
} 