<?php
    // Include the database connection file
    include("connection.php");

    // Check if the request is a POST request
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Retrieve data from the POST request
        // Assuming you have the stall_id stored somewhere
        $stallId = $_POST['stall_id'];
        $menuName = $_POST['add_menu_name'];
        $menuDescription = $_POST['add_menu_description'];
        $menuImage = $_POST['add_menu_image'];
        $menuPrice = $_POST['add_menu_price'];
        
        
        try {
            // Prepare the SQL statement to insert a new menu
            $query = "INSERT INTO menu (name, description,   image, price, stall_id) VALUES (:name, :description, :image, :price, :stall_id)";
            $stmt = $conn->prepare($query);
            
            // Bind parameters
            $stmt->bindParam(':name', $menuName, PDO::PARAM_STR);
            $stmt->bindParam(':description', $menuDescription, PDO::PARAM_STR);
            $stmt->bindParam(':image', $menuImage, PDO::PARAM_STR);
            $stmt->bindParam(':price', $menuPrice, PDO::PARAM_STR);
            $stmt->bindParam(':stall_id', $stallId, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();

            // Return success message
            echo json_encode(['status' => 'success', 'message' => 'Menu added successfully']);
        } catch (PDOException $e) {
            // Return error message
            echo json_encode(['status' => 'error', 'message' => 'Error adding menu: ' . $e->getMessage()]);
        }
    } else {
        // Return error message for invalid request
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
?>