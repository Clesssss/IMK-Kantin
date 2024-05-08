<?php
// Include your database connection file
include("connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stall_id'])) {
    // Get the stall ID from the POST data
    $stall_id = $_POST['stall_id'];

    // Fetch menu items based on the stall ID
    $query = "SELECT * FROM menu WHERE stall_id = :stall_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':stall_id', $stall_id, PDO::PARAM_INT);
    $stmt->execute();
    $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the menu items with "Add to Cart" button
    if ($menu_items) {
        foreach ($menu_items as $menu_item) {
            echo '<div>';
            echo '<h6>' . $menu_item['name'] . '</h6>';
            echo '<p>' . $menu_item['description'] . '</p>';
            echo '<p>Rp. ' . $menu_item['price'] . '</p>';
            echo '<button class="btn btn-sm btn-outline-secondary add-to-cart" data-menuid="' . $menu_item['menu_id'] . '">Add to Cart</button>';
            echo '</div>';
            echo '<hr>';
        }
    } else {
        echo '<p>No menu items available for this stall.</p>';
    }
} else {
    echo '<p>Invalid request.</p>';
}
?>