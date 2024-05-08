<?php
// Include your database connection file
include("connection.php");

// Check if menu_id is set in the POST data
if (isset($_POST['menu_id'])) {
    $menuId = $_POST['menu_id'];

    // Prepare and execute a DELETE query
    $query = "DELETE FROM menu WHERE menu_id = :menu_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':menu_id', $menuId, PDO::PARAM_INT);

    try {
        $stmt->execute();
        echo "Menu deleted successfully";
    } catch (PDOException $e) {
        // Handle any potential errors
        echo "Error: " . $e->getMessage();
    }
} else {
    // If menu_id is not set in the POST data, return an error message
    echo "Error: Menu ID not provided";
}
?>