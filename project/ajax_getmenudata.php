<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve menu ID from the request
    $menuId = $_POST['menu_id'];

    try {
        // Fetch menu data based on menu ID
        $query = "SELECT * FROM menu WHERE menu_id = :menu_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':menu_id', $menuId, PDO::PARAM_INT);
        $stmt->execute();
        $menuData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return menu data as JSON
        echo json_encode($menuData);
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error fetching menu data: " . $e->getMessage();
    }
} else {
    // Handle non-POST requests (if needed)
    echo "Invalid request method";
}