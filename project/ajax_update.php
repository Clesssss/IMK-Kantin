<?php
    // ajax_update.php

    // Include your database connection code
    include("connection.php");

    // Assuming you have the user_id in the session
    session_start();
    $user_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the updated data from the POST request
        $menu_id = $_POST['edit_menu_id'];
        $quantity = $_POST['edit_quantity'];
        $note = $_POST['edit_note'];

        // Retrieve the price from the menu table
        $price_query = "SELECT price FROM menu WHERE menu_id = :menu_id";
        $price_stmt = $conn->prepare($price_query);
        $price_stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);
        $price_stmt->execute();
        $menu_price = $price_stmt->fetchColumn();

        if ($menu_price !== false) {
            // Update the data in the cart table
            $update_query = "UPDATE cart SET quantity = :quantity, note = :note, subtotal = :subtotal WHERE user_id = :user_id AND menu_id = :menu_id";
            $update_stmt = $conn->prepare($update_query);
            $subtotal = $quantity * $menu_price;
            $update_stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $update_stmt->bindParam(':note', $note, PDO::PARAM_STR);
            $update_stmt->bindParam(':subtotal', $subtotal, PDO::PARAM_STR);
            $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $update_stmt->bindParam(':menu_id', $menu_id, PDO::PARAM_INT);

            try {
                $update_stmt->execute();
                exit();
            } catch (PDOException $e) {
                $response = ['status' => 'error', 'message' => 'Error updating data: ' . $e->getMessage()];
                echo json_encode($response);
                exit();
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Failed to retrieve menu price'];
            echo json_encode($response);
            exit();
        }
    } else {
        // If the request method is not POST, return an error
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        exit();
    }
?>