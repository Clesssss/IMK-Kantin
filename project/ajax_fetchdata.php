<?php
// fetch_data.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have a database connection in your connection.php file
    include("connection.php");

    $menuId = $_POST['menu_id'];

    // Use prepared statement to fetch data
    $query = "SELECT * FROM cart WHERE menu_id = :menu_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':menu_id', $menuId, PDO::PARAM_INT);
    $stmt->execute();

    // Fetch data as an associative array
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Return data as JSON
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
?>