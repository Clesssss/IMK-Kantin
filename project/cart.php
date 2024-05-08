<?php
session_start();
include("connection.php");

// Assuming you have the user_id in the session
$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_item'])) {
    $menu_id_to_remove = $_POST['remove_item'];
    $remove_query = "DELETE FROM cart WHERE user_id = :user_id AND menu_id = :menu_id";
    $remove_stmt = $conn->prepare($remove_query);
    $remove_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $remove_stmt->bindParam(':menu_id', $menu_id_to_remove, PDO::PARAM_INT);
    $remove_stmt->execute();
    // Redirect to prevent form resubmission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Retrieve data from the cart table and join with the menu table
$query = "SELECT menu.name, cart.menu_id, cart.quantity, cart.subtotal, cart.note, menu.stall_id, stall.name as stall_name
          FROM cart 
          INNER JOIN menu ON cart.menu_id = menu.menu_id 
          INNER JOIN stall ON menu.stall_id = stall.stall_id
          WHERE cart.user_id = :user_id
          ORDER BY menu.stall_id, cart.menu_id";

$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize cart items by stall_id
$groupedCart = [];
foreach ($result as $row) {
    $stallId = $row['stall_id'];
    if (!isset($groupedCart[$stallId])) {
        $groupedCart[$stallId] = [
            'items' => [],
            'total' => 0
        ];
    }

    $groupedCart[$stallId]['items'][] = $row;
    $groupedCart[$stallId]['total'] += $row['subtotal'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
        crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.btn-edit').on('click', function () {
                // Get the menuId from the data attribute
                var menuId = $(this).data('menu-id');

                // Set the menuId in the modal form
                $('#editModal').find('[name="menuId"]').val(menuId);
                $('#editModal').modal('show');

                // Use AJAX to fetch data from the server based on menuId
                $.ajax({
                    url: 'ajax_fetchdata.php', // Replace with your server-side script to fetch data
                    method: 'POST',
                    data: { menu_id: menuId },
                    dataType: 'json',
                    success: function (data) {
                        // Populate the modal inputs with existing data
                        $('#editQuantity').val(data.quantity);
                        $('#editNote').val(data.note);
                        $('#editMenuId').val(menuId);

                        // Show the modal
                        $('#editModal').modal('show');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching data:', status, error);
                    }
                });
            });

            // Event listener for the "Save Changes" button in the modal
            $('#editForm').submit(function (e) {
                e.preventDefault();

                // Get form data
                var formData = $(this).serialize();

                // Use AJAX to send updated data to the server
                $.ajax({
                    url: 'ajax_update.php', // Replace with your server-side script to handle updates
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        // Handle success, e.g., close the modal or update the UI
                        console.log(response);
                        $('#editModal').modal('hide');
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error updating data:', status, error);
                        // Handle error, e.g., show an alert to the user
                    }
                });
            });

            // Event listener for the "Order" button
            $('#orderButton').on('click', function () {
                // Show the order modal
                $('#orderModal').modal('show');
            });

            // Event listener for the "Order" button in the order modal
            $('#orderForm').submit(function (e) {
                e.preventDefault();

                // Get form data
                var formData = $(this).serialize();

                // Use AJAX to send order data to the server
                $.ajax({
                    url: 'ajax_processorder.php', // Your server-side script to handle order processing
                    method: 'POST',
                    data: formData,
                    success: function (response) {
                        // Handle success, e.g., show a success message or redirect
                        console.log(response);
                        alert('Order placed successfully!');
                        $('#orderModal').modal('hide');
                        location.reload(); // Optionally reload the page after successful order
                    },
                    error: function (xhr, status, error) {
                        console.error('Error placing order:', status, error);
                        // Handle error, e.g., show an alert to the user
                    }
                });
            });
            // Event listener for the "Order" button in each stall section
            $('.add-order').on('click', function () {
                // Set the selected stall_id
                selectedStallId = $(this).data('stall-id');

                // Set the hidden input value
                $('#stallId').val(selectedStallId);
                // Show the order modal for the selected stall
                var stallName = $(this).data('stall-name');
                $('#orderModal').find('.modal-title').text('Place Order from ' + stallName);
                $('#orderModal').modal('show');
                // Optionally, you can pass the stallName to the modal or use it in your order processing logic
            });
        });

        function confirmRemove(menuId) {
            var confirmation = confirm("Are you sure you want to remove this item?");
            if (confirmation) {
                // If the user confirms, submit the form
                document.getElementById('removeForm_' + menuId).submit();
            }
        }
    </script>
    <style>
        :root {
            --jumbotron-padding-y: 3rem;
        }

        .jumbotron {
            padding-top: var(--jumbotron-padding-y);
            padding-bottom: var(--jumbotron-padding-y);
            margin-bottom: 0;
            background-color: #fff;
        }

        @media (min-width: 768px) {
            .jumbotron {
                padding-top: calc(var(--jumbotron-padding-y) * 2);
                padding-bottom: calc(var(--jumbotron-padding-y) * 2);
            }
        }

        .jumbotron p:last-child {
            margin-bottom: 0;
        }

        .jumbotron-heading {
            font-weight: 300;
        }

        .jumbotron .container {
            max-width: 40rem;
        }

        .box-shadow {
            box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05);
        }

        a:hover {
            text-decoration: underline;
        }

        a {
            text-decoration: none;
        }

        /* Add styles for the order modal as needed */
        #orderModal .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }

        /* Adjust layout for the total and order button on the same row */
        .total-and-order {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body>
    <?php include("navbar.php"); ?>
    <main role="main">
        <section class="jumbotron text-center">
            <div class="container">
                <?php if (empty($result)): ?>
                    <p>Your cart is empty.</p>
                <?php else: ?>
                    <?php foreach ($groupedCart as $stallId => $stallData): ?>
                        <!-- Open a new section for the current stall -->
                        <h2><?= $stallData['items'][0]['stall_name']; ?></h2>
                        <table class="table table-bordered mt-2">
                            <tr>
                                <th style="font-weight: 600;">Menu</th>
                                <th style="font-weight: 600;">Quantity</th>
                                <th style="font-weight: 600;">Subtotal</th>
                                <th style="font-weight: 600;">Special Request</th>
                                <th style="font-weight: 600;">Action</th>
                            </tr>
                            <?php foreach ($stallData['items'] as $row): ?>
                                <tr>
                                    <td><?= $row['name']; ?></td>
                                    <td><?= $row['quantity']; ?></td>
                                    <td><?= $row['subtotal']; ?></td>
                                    <td><?= $row['note']; ?></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- Edit button triggers the modal -->
                                            <button type="button" class="btn btn-outline-secondary btn-sm btn-edit"
                                                data-menu-id="<?= $row['menu_id']; ?>">Edit</button>

                                            <!-- Remove button form -->
                                            <form id="removeForm_<?= $row['menu_id']; ?>" method="post">
                                                <input type="hidden" name="remove_item"
                                                    value="<?= $row['menu_id']; ?>">
                                                <button type="button"
                                                    class="btn btn-outline-secondary btn-sm"
                                                    onclick="confirmRemove(<?= $row['menu_id']; ?>)">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <div class="total-and-order">
                            <p class="float-left"><span style="font-weight: 600;">Total </span> = <?= $stallData['total']; ?></p>
                            <button type="button" class="btn btn-outline-secondary float-right add-order" data-stall-name="<?= $row['stall_name']; ?>" data-stall-id="<?= $row['stall_id']; ?>">Order</button>
                        </div>
                        <!-- Close the current stall's section -->
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
                </div>
                <div class="modal-body">
                    <!-- Form for editing -->
                    <form id="editForm" method="post">
                        <div class="form-group">
                            <label for="editQuantity">Quantity:</label>
                            <input type="number" class="form-control" id="editQuantity" name="edit_quantity"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="editNote">Special Request:</label>
                            <input type="text" class="form-control" id="editNote" name="edit_note">
                        </div>
                        <!-- Hidden input for menu_id -->
                        <input type="hidden" id="editMenuId" name="edit_menu_id">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Modal -->
    <div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderModalLabel">Place Order</h5>
                </div>
                <div class="modal-body">
                    <!-- Add your order form and payment choices here -->
                    <!-- Example: -->
                    <form id="orderForm" method="post">
                        <input type="hidden" id="stallId" name="stall_id" value="">
                        <div class="form-group">
                            <label for="paymentChoice">Payment Choice:</label>
                            <select class="form-control" id="paymentChoice" name="payment_choice" required>
                                <option value="1">Credit Card</option>
                                <option value="2">PayPal</option>
                                <!-- Add more payment choices as needed -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary order">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>