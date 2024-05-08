<?php
// ajax_getstalldata.php

session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve stall ID from the AJAX request
    $stallId = $_POST['stall_id'];

    try {
        // Fetch stall data from the database based on stall ID
        $query = "SELECT * FROM stall WHERE stall_id = :stall_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':stall_id', $stallId, PDO::PARAM_INT);
        $stmt->execute();
        $stallData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return stall data as JSON
        echo json_encode($stallData);
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error fetching stall data: " . $e->getMessage();
    }
} else {
    // Handle non-POST requests (if needed)
    echo "Invalid request method";
}
?>