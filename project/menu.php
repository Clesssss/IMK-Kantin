<?php
    session_start();
    include("connection.php");

    if (isset($_SESSION['kantin_id'])) {
        $current_kantin_id = $_SESSION['kantin_id'];
    } else {
        $current_kantin_id = 1;
    }

    $query = "SELECT stall_id FROM stall WHERE kantin_id = :kantin_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':kantin_id', $current_kantin_id, PDO::PARAM_INT);
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
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Menu</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script>
            $(document).ready(function () {
                $('.magic').on('click', function () {
                    var newKantinId = $(this).data('kantinid');
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_updatekantinid.php',
                        data: { new_kantin_id: newKantinId },
                        success: function () {
                        // Request updated stalls content
                            $.ajax({
                                type: 'POST',
                                url: 'ajax_getmenus.php',
                                data: { new_kantin_id: newKantinId },
                                success: function (response) {
                                    // Replace the existing card content with the new HTML
                                    $('.album .container .row').html(response);
                                }
                            });
                        }
                    });
                });
                $(document).on('click', '.btn-add-to-cart', function () {
                    <?php if (!isset($_SESSION['user_name'])): ?>
                        $('#loginModal').modal('show');
                    <?php else: ?>
                        // Continue with the existing code for adding to cart
                        // Get the menuId from the data attribute
                        var menuId = $(this).data('menu-id');

                        // Set the menuId in the modal form
                        $('#addToCartModal').find('[name="menuId"]').val(menuId);

                        // Show the modal
                        $('#addToCartModal').modal('show');
                    <?php endif; ?>
                });

                // Handle the form submission
                $('#addToCartForm').submit(function (event) {
                    event.preventDefault();

                    // Get the values from the form
                    var quantity = $('#quantity').val();
                    var specialRequests = $('#specialRequests').val();
                    var menuId = $('#menuId').val(); // Retrieve menuId from a hidden input field

                    // Perform AJAX request to add to cart (replace this with your actual logic)
                    $.ajax({
                        type: 'POST',
                        url: 'ajax_addtocart.php', // Replace with your server-side script
                        data: {
                            quantity: quantity,
                            specialRequests: specialRequests,
                            menuId: menuId
                        },
                        success: function (response) {
                            // Handle success, close the modal, or update UI
                            $('#addToCartModal').modal('hide');
                        },
                        error: function () {
                            // Handle error
                            console.error('Error adding to cart.');
                        }
                    });
                });
            });
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
            .box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }

            a:hover {
                text-decoration: underline;
            }
            a {
                text-decoration: none;
            }

        </style>
    </head>
    <body>
        <?php include("navbar.php"); ?>
        <main role="main">
            <section class="jumbotron text-center">
                <div class="container">
                    <h1 class="jumbotron-heading">Building</h1>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary magic" data-kantinid="1">P</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary magic" data-kantinid="2">Q</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary magic" data-kantinid="3">W</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary magic" data-kantinid="4">T</button>
                    </div>
                </div>
            </section>
            <div class="album py-5 bg-light">
                <div class="container">
                    <div class="row">
                        <?php foreach ($menus as $menu): ?>
                            <div class="col-md-4">
                                <div class="card mb-4 box-shadow">
                                    <img class="card-img-top" src="<?= $menu['image']; ?>" alt="Menu Image">
                                    <div class="card-body">
                                        <h1 class="h6"><?= $menu['name']; ?></h1>
                                        <p class="card-text"><?= $menu['description']; ?></p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button type="button" class="btn btn-add-to-cart btn-sm btn-outline-secondary" data-menu-id="<?= $menu['menu_id']; ?>">Add to Cart</button>
                                            <small class="text-muted"><?= $menu['price']; ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
        <div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="addToCartModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addToCartModalLabel">Add to Cart</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form inside modal -->
                        <form id="addToCartForm">
                            <form id="addToCartForm">
                                <input type="hidden" id="menuId" name="menuId" value="">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity:</label>
                                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                                </div>
                                <div class="mb-3">
                                    <label for="specialRequests" class="form-label">Special Requests:</label>
                                    <textarea class="form-control" id="specialRequests" name="specialRequests" rows="3"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>