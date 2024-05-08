<?php
session_start();
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve data from the POST request
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;
    $specialRequests = isset($_POST['specialRequests']) ? $_POST['specialRequests'] : null;
    $menuId = isset($_POST['menuId']) ? $_POST['menuId'] : null;

    echo $quantity;
    echo $specialRequests;
    echo $menuId;
    // Validate the data (you can add more validation as needed)
    if ($quantity !== null && $specialRequests !== null && $menuId !== null) {
        // Get the user ID from the session
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

        if ($userId) {
            // Retrieve the price from the menu table
            $query = "SELECT price FROM menu WHERE menu_id = :menuId";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':menuId', $menuId, PDO::PARAM_INT);
            $stmt->execute();
            $menuPrice = $stmt->fetchColumn();

            if ($menuPrice !== false) {
                // Calculate the subtotal
                $subtotal = $quantity * $menuPrice;

                // Insert data into the SQL table
                $query = "INSERT INTO cart (user_id, menu_id, quantity, note, subtotal) VALUES (:userId, :menuId, :quantity, :specialRequests, :subtotal)";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $stmt->bindParam(':menuId', $menuId, PDO::PARAM_INT);
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->bindParam(':specialRequests', $specialRequests, PDO::PARAM_STR);
                $stmt->bindParam(':subtotal', $subtotal, PDO::PARAM_STR);

                // Execute the query
                if ($stmt->execute()) {
                    // Return success message or any other data to the client
                    echo json_encode(['success' => true]);
                } else {
                    // Return an error message to the client
                    echo json_encode(['error' => 'Failed to add to cart: ' . implode(" ", $stmt->errorInfo())]);
                }
            } else {
                // Return an error message if menu price retrieval fails
                echo json_encode(['error' => 'Failed to retrieve menu price']);
            }
        } else {
            // Return an error message if user ID is not set in the session
            echo json_encode(['error' => 'User ID not found in session']);
        }
    } else {
        // Return an error message to the client for invalid data
        echo json_encode(['error' => 'Invalid data']);
    }
} else {
    // Return an error message for invalid request method
    echo json_encode(['error' => 'Invalid request method']);
}
?>