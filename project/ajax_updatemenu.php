<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the form
    $menuName = $_POST['edit_menu_name'];
    $menuDescription = $_POST['edit_menu_description'];
    $menuImage = $_POST['edit_menu_image'];
    $menuPrice = $_POST['edit_menu_price'];
    $menuId = $_POST['edit_menu_id'];

    try {
        // Update menu data in the database
        $query = "UPDATE menu SET name = :name, description = :description, image = :image, price = :price WHERE menu_id = :menu_id";
        $stmt = $conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':name', $menuName, PDO::PARAM_STR);
        $stmt->bindParam(':description', $menuDescription, PDO::PARAM_STR);
        $stmt->bindParam(':image', $menuImage, PDO::PARAM_STR);
        $stmt->bindParam(':price', $menuPrice, PDO::PARAM_STR);
        $stmt->bindParam(':menu_id', $menuId, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Return a success message (you may customize it as needed)
        echo "Menu data updated successfully";
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error updating menu data: " . $e->getMessage();
    }
} else {
    // Handle non-POST requests (if needed)
    echo "Invalid request method";
}