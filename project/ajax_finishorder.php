<?php
// finishorder.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['transaction_id'])) {
    // Include your database connection file
    include("connection.php");

    // Sanitize the input
    $transactionId = filter_var($_POST['transaction_id'], FILTER_SANITIZE_NUMBER_INT);

    try {
        // Update the status to "Order Finished" for the specified transaction ID
        $updateQuery = "UPDATE transaction SET status = 'Order Finished' WHERE transaction_id = :transaction_id";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
        $stmt->execute();

        // Return a success message or handle as needed
        echo json_encode(['success' => true, 'message' => 'Order finished successfully']);
    } catch (PDOException $e) {
        // Handle the exception (e.g., log it, display an error message)
        echo json_encode(['success' => false, 'message' => 'Error finishing order: ' . $e->getMessage()]);
    }
} else {
    // Invalid request method or missing parameters
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>