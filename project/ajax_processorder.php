<?php
session_start();
include("connection.php");

// Assuming you have the user_id and cart items in the session
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get other form data
    $payment_choice = $_POST['payment_choice'];
    $stall_id = $_POST['stall_id']; 

    // Retrieve data from the cart table and join with the menu table
    $query = "SELECT cart.menu_id, cart.quantity, cart.subtotal, cart.note
              FROM cart 
              INNER JOIN menu ON cart.menu_id = menu.menu_id
              WHERE cart.user_id = :user_id AND menu.stall_id = :stall_id";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':stall_id', $stall_id, PDO::PARAM_INT);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Calculate the total price and subtotal for each item
    $totalPrice = 0;
    foreach ($cartItems as $item) {
        $totalPrice += $item['subtotal'];
    }

    // Insert into the transaction table with the total and stall_id
    $transactionQuery = "INSERT INTO transaction (date, payment_id, user_id, total, status, stall_id) VALUES (CURRENT_DATE(), :payment_id, :user_id, :total, :status, :stall_id)";
    $transactionStmt = $conn->prepare($transactionQuery);
    $transactionStmt->bindValue(':payment_id', $payment_choice, PDO::PARAM_INT);
    $transactionStmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $transactionStmt->bindValue(':total', $totalPrice, PDO::PARAM_STR);
    $transactionStmt->bindValue(':status', "Order Placed", PDO::PARAM_STR);
    $transactionStmt->bindValue(':stall_id', $stall_id, PDO::PARAM_INT);
    $transactionStmt->execute();

    // Get the last inserted transaction_id
    $transactionId = $conn->lastInsertId();

    // Iterate through each menu in the cart and insert into transaction_detail if stall_id matches
    foreach ($cartItems as $item) {
        $menuId = $item['menu_id'];
        $quantity = $item['quantity'];
        $note = $item['note'];
        $subtotal = $item['subtotal'];

        // Check if the menu item belongs to the selected stall
        $checkStallQuery = "SELECT stall_id FROM menu WHERE menu_id = :menu_id";
        $checkStallStmt = $conn->prepare($checkStallQuery);
        $checkStallStmt->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
        $checkStallStmt->execute();
        $menuStallId = $checkStallStmt->fetchColumn();

        if ($menuStallId == $stall_id) {
            // Insert into transaction_detail
            $transactionDetailQuery = "INSERT INTO transaction_detail (transaction_id, menu_id, quantity, subtotal, note) VALUES (:transaction_id, :menu_id, :quantity, :subtotal, :note)";
            $transactionDetailStmt = $conn->prepare($transactionDetailQuery);
            $transactionDetailStmt->bindValue(':transaction_id', $transactionId, PDO::PARAM_INT);
            $transactionDetailStmt->bindValue(':menu_id', $menuId, PDO::PARAM_INT);
            $transactionDetailStmt->bindValue(':quantity', $quantity, PDO::PARAM_INT);
            $transactionDetailStmt->bindValue(':subtotal', $subtotal, PDO::PARAM_STR);
            $transactionDetailStmt->bindValue(':note', $note, PDO::PARAM_STR);
            $transactionDetailStmt->execute();
        }
    }
    // Clear the cart where stall_id matches
    $clearCartQuery = "DELETE FROM cart WHERE user_id = :user_id AND menu_id IN (SELECT menu_id FROM menu WHERE stall_id = :stall_id)";
    $clearCartStmt = $conn->prepare($clearCartQuery);
    $clearCartStmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $clearCartStmt->bindValue(':stall_id', $stall_id, PDO::PARAM_INT);
    $clearCartStmt->execute();


    // Send a success response
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>