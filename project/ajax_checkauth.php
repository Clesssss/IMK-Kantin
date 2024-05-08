<?php
// check_auth.php

// Start or resume the session
session_start();

// Initialize the response array
$response = array('authenticated' => false, 'user_id' => null);

// Check if the user is logged in and has the correct user_id
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_POST['ownerId']) {
    $response['authenticated'] = true;
    $response['user_id'] = $_SESSION['user_id'];
} else {
    // Add an error message to the response
    $response['error'] = 'Authentication failed';
}

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>