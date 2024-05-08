<?php
include("connection.php");

if (isset($_POST['new_kantin_id'])) {
    $new_kantin_id = $_POST['new_kantin_id'];
    $query = "SELECT stall_id FROM stall WHERE kantin_id = :kantin_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':kantin_id', $new_kantin_id, PDO::PARAM_INT);
    $stmt->execute();
    $stallIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if (!empty($stallIds)) {
        $query = "SELECT * FROM menu WHERE stall_id IN (" . implode(',', array_fill(0, count($stallIds), '?')) . ")";
        $stmt = $conn->prepare($query);

        foreach ($stallIds as $key => $stallId) {
            $stmt->bindValue(($key + 1), $stallId, PDO::PARAM_INT);
        }

        $stmt->execute();
        $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Handle the case when there are no stalls for the given kantin_id
        echo "No stalls found for the given Kantin ID.";
    }

    // Update the card structure based on your requirements
$html = '';
foreach ($menus as $menu) {
    $html .= '<div class="col-md-4">';
    $html .= '<div class="card mb-4 box-shadow">';
    $html .= '<img class="card-img-top" src="' . $menu['image'] . '" alt="Menu Image">';
    $html .= '<div class="card-body">';
    $html .= '<h1 class="h6">' . $menu['name'] . '</h1>';
    $html .= '<p class="card-text">' . $menu['description'] . '</p>';
    $html .= '<div class="d-flex justify-content-between align-items-center">';
    $html .= '<button type="button" class="btn btn-add-to-cart btn-sm btn-outline-secondary" data-menu-id="' . $menu['menu_id'] . '">Add to Cart</button>';
    $html .= '<small class="text-muted">' . $menu['price'] . '</small>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
} 
    echo $html;
} else {
    // Handle the case where new_kantin_id is not set in the POST data
    echo 'error';
}
?>