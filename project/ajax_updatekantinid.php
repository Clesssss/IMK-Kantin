<?php
    if (isset($_POST['new_kantin_id'])) {
        $new_kantin_id = $_POST['new_kantin_id'];

        // You should validate and sanitize the input to avoid SQL injection

        // Assuming $conn is your PDO connection
        include("connection.php");

        // Update the session variable or database with the new kantin_id
        // For example, you can store it in a session variable
        session_start();
        $_SESSION['kantin_id'] = $new_kantin_id;

        // You can also update the database if needed

        // Respond to the AJAX request
        echo 'success';
    } else {
        // Handle the case where new_kantin_id is not set in the POST data
        echo 'error';
    }
?>