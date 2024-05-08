<?php
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you are passing transaction_id via POST
    $transaction_id = $_POST['transaction_id'];

    // Fetch transaction details from the database based on transaction_id
    $query = "SELECT menu.name, td.quantity, td.note, td.subtotal
              FROM transaction_detail td
              JOIN menu ON td.menu_id = menu.menu_id
              WHERE td.transaction_id = :transaction_id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':transaction_id', $transaction_id, PDO::PARAM_INT);
    $stmt->execute();
    $transactionDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($transactionDetails);
} else {
    // Handle invalid request method
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method";
}
?>