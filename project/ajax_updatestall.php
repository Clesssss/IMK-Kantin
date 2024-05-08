<?php
// ajax_updatestall.php

session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $stallName = $_POST['edit_stall_name'];
    $stallDescription = $_POST['edit_stall_description'];
    $stallImage = $_POST['edit_stall_image'];
    $kantinId = $_POST['edit_kantin_id'];
    $stallId = $_POST['edit_stall_id'];

    try {
        // Update stall data in the database
        $query = "UPDATE stall SET name = :name, description = :description, image = :image, kantin_id = :kantin_id WHERE stall_id = :stall_id";
        $stmt = $conn->prepare($query);
        
        // Bind parameters
        $stmt->bindParam(':name', $stallName, PDO::PARAM_STR);
        $stmt->bindParam(':description', $stallDescription, PDO::PARAM_STR);
        $stmt->bindParam(':image', $stallImage, PDO::PARAM_STR);
        $stmt->bindParam(':kantin_id', $kantinId, PDO::PARAM_INT);
        $stmt->bindParam(':stall_id', $stallId, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Return a success message (you can customize this)
        echo "success"; // Adjust the message as needed
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error updating stall data: " . $e->getMessage();
    }
} else {
    // Handle non-POST requests (if needed)
    echo "Invalid request method";
}
?>