<?php
// Include your database connection file
include("connection.php");

// Check if the POST data is set
if (isset($_POST['stall_id'])) {
    // Retrieve stall_id from POST data
    $stallId = $_POST['stall_id'];

    // Prepare and execute the query to fetch menu data for the specified stall
    $query = "SELECT * FROM menu WHERE stall_id = :stall_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':stall_id', $stallId, PDO::PARAM_INT);
    $stmt->execute();
    $menuData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the menu data as JSON
    echo json_encode($menuData);
} else {
    // If POST data is not set
    echo json_encode(['error' => 'Invalid request']);
}
?>