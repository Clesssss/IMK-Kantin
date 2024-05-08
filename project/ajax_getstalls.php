<?php
include("connection.php");

if (isset($_POST['new_kantin_id'])) {
    $new_kantin_id = $_POST['new_kantin_id'];

    // You should validate and sanitize the input to avoid SQL injection

    $query = "SELECT * FROM stall WHERE kantin_id = :kantin_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':kantin_id', $new_kantin_id, PDO::PARAM_INT);
    $stmt->execute();
    $stalls = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Update the card structure based on your requirements
    $html = '';
    foreach ($stalls as $stall) {
        $html .= '<div class="col-md-4">';
        $html .= '<div class="card mb-4 box-shadow">';
        $html .= '<img class="card-img-top" src="' . $stall['image'] . '" alt="Card image cap">';
        $html .= '<div class="card-body">';
        $html .= '<h1 class="h6">' . $stall['name'] . '</h1>';
        $html .= '<p class="card-text">' . $stall['description'] . '</p>';
        $html .= '<div class="d-flex justify-content-between align-items-center">';
        $html .= '<div class="btn-group">';
        $html .= '<button type="button" class="btn btn-sm btn-outline-secondary view-menu" data-stallid="' . $stall['stall_id'] . '">View</button>';
        $html .= '<button type="button" class="btn btn-sm btn-outline-secondary edit-stall" data-stallid="' . $stall['stall_id'] . '" data-ownerid="' . $stall['owner_id'] . '">Edit</button>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';    
    }

    // Send the HTML back to the AJAX request
    echo $html;
} else {
    // Handle the case where new_kantin_id is not set in the POST data
    echo 'error';
}
?>