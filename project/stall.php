    <?php
        session_start();
        include("connection.php");

        // Check if the Kantin ID is set in the session
        if (isset($_SESSION['kantin_id'])) {
            $current_kantin_id = $_SESSION['kantin_id'];
        } else {
            // If no Kantin ID is set, set a default value or handle it as needed
            $current_kantin_id = 1; // Set a default Kantin ID   or handle it differently
        }

        // Fetch stalls based on the current Kantin ID
        $query = "SELECT stall.*, user.user_id AS owner_id
            FROM stall
            INNER JOIN user ON stall.owner_id = user.user_id
            WHERE kantin_id = :kantin_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':kantin_id', $current_kantin_id, PDO::PARAM_INT);
        $stmt->execute();
        $stalls = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <title>Stall</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
            <script>
                $(document).ready(function () {
                    $(document).on('click', '.view-menu', function () {
                        var stallId = $(this).data('stallid');

                        // Set the stall name in the modal
                        $('#stallName').text($(this).closest('.card').find('.h6').text());
                        $('#menuModal').modal('show');

                        // Use AJAX to fetch menu data from the server based on stallId
                        $.ajax({
                            url: 'ajax_getmenuforeachstall.php', // Replace with your server-side script to fetch menu data
                            method: 'POST',
                            data: { stall_id: stallId },
                            success: function (response) {
                                // Populate the modal with menu data
                                $('#menuContent').html(response);
                            }
                        });
                    });
                    $(document).on('click', '.add-to-cart', function () {
                        <?php if (!isset($_SESSION['user_name'])): ?>
                            $('#loginModal').modal('show');
                        <?php else: ?>
                            // Continue with the existing code for adding to cart
                            // Get the menuId from the data attribute
                            var menuId = $(this).data('menuid');

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
                    $(document).on('click', '.edit-stall', function () {
                        var ownerId = $(this).data('ownerid'); 
                        var stallId = $(this).data('stallid');
                        // Use AJAX to check user authentication on the server
                        $.ajax({
                            url: 'ajax_checkauth.php',
                            method: 'POST',
                            data: { ownerId: ownerId},
                            dataType: 'json',
                            success: function (response) {
                                if (response.authenticated && response.user_id == ownerId) {
                                    // If the user is authenticated and has the correct user_id
                                    
                                    // Show the edit modal
                                    $('#editModal').modal('show');
                                    $('.add-menu').data('stallid', stallId);

                                    // Use AJAX to fetch stall data from the server based on stallId
                                    $.ajax({
                                        url: 'ajax_getstalldata.php',
                                        method: 'POST',
                                        data: { stall_id: stallId },
                                        dataType: 'json',
                                        success: function (data) {
                                            // Populate the modal inputs with existing stall data
                                            $('#editStallName').val(data.name);
                                            $('#editStallDescription').val(data.description);
                                            $('#editKantinId').val(data.kantin_id);
                                            $('#editStallImage').val(data.image);
                                            $('#editStallId').val(stallId);
                                            // Fetch and populate the menu table
                                            fetchAndPopulateMenuTable(stallId);
                                        },
                                        error: function (xhr, status, error) {
                                            console.error('Error fetching stall data:', status, error);
                                        }
                                    });
                                } else {
                                    // If the user is not authenticated or does not have the correct user_id
                                    $('#permissionModal').modal('show');
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error('Error checking authentication:', status, error);
                            }
                        });
                    });
                    // Event listener for the stall form submission
                    $('#editStallForm').submit(function (e) {
                        e.preventDefault();

                        // Get form data
                        var formData = $(this).serialize();

                        // Use AJAX to send updated stall data to the server
                        $.ajax({
                            url: 'ajax_updatestall.php',
                            method: 'POST',
                            data: formData,
                            success: function (response) {
                                // Handle success, e.g., close the modal or update the UI
                                console.log(response);
                                $('#editModal').modal('hide');
                                location.reload();
                            },
                            error: function (xhr, status, error) {
                                console.error('Error updating stall data:', status, error);
                                // Handle error, e.g., show an alert to the user
                            }
                        });
                    });
                    
                    // Function to fetch and populate the menu table dynamically
                    function fetchAndPopulateMenuTable(stallId) {
                        $.ajax({
                            url: 'ajax_getmenuforstall.php',
                            method: 'POST',
                            data: { stall_id: stallId },
                            dataType: 'json',
                            success: function (menuData) {
                                // Populate the menu table dynamically
                                var tableBody = $('#menuTableBody');
                                tableBody.empty();

                                $.each(menuData, function (index, menu) {
                                    var row = '<tr>' +
                                        '<td>' + menu.name + '</td>' +
                                        '<td>' +
                                            '<button type="button" class="btn btn-sm btn-outline-secondary edit-menu" data-menuid="' + menu.menu_id + '">Edit</button>' +
                                            '<button type="button" class="btn btn-sm btn-outline-secondary delete-menu" data-menuid="' + menu.menu_id + '">Delete</button>' +
                                        '</td>' +
                                        '</tr>';
                                    tableBody.append(row);
                                });
                            },
                            error: function (xhr, status, error) {
                                console.error('Error fetching menu data:', status, error);
                            }
                        });
                    }
                    // Add this click event handler for edit-menu and delete-menu buttons
                    $(document).on('click', '.edit-menu, .delete-menu', function () {
                        var menuId = $(this).data('menuid');

                        if ($(this).hasClass('edit-menu')) {
                            // Handle edit-menu action
                            $('#editMenuModal').modal('show');
                            // Fetch menu data based on menuId
                            $.ajax({
                                url: 'ajax_getmenudata.php',
                                method: 'POST',
                                data: { menu_id: menuId },
                                dataType: 'json',
                                success: function (menuData) {
                                    // Populate the edit menu modal with existing menu data
                                    $('#editMenuName').val(menuData.name);
                                    $('#editMenuDescription').val(menuData.description);
                                    $('#editMenuImage').val(menuData.image);
                                    $('#editMenuPrice').val(menuData.price);
                                    $('#editMenuId').val(menuId);

                                    // Show the edit menu modal
                                    $('#editMenuModal').modal('show');
                                    $('#editModal').modal('hide');
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error fetching menu data:', status, error);
                                }
                            });
                        } else if ($(this).hasClass('delete-menu')) {
                            // Handle delete-menu action
                            $.ajax({
                                url: 'ajax_deletemenu.php',
                                method: 'POST',
                                data: { menu_id: menuId },
                                success: function (response) {
                                    // Handle success, e.g., refresh the menu table
                                    console.log(response);
                                    fetchAndPopulateMenuTable($('#editStallId').val());
                                },
                                error: function (xhr, status, error) {
                                    console.error('Error deleting menu data:', status, error);
                                    // Handle error, e.g., show an alert to the user
                                }
                            });
                        }
                    });
                    // New script for handling editMenuForm submission
                    $('#editMenuForm').submit(function (e) {
                        e.preventDefault();

                        // Get form data
                        var formData = $(this).serialize();

                        // Use AJAX to send updated menu data to the server
                        $.ajax({
                            url: 'ajax_updatemenu.php',
                            method: 'POST',
                            data: formData,
                            success: function (response) {
                                // Handle success, e.g., close the modal or update the UI
                                console.log(response);
                                $('#editMenuModal').modal('hide');
                                // Get the stallId from the edit form or wherever it is stored
                                var stallId = $('#editStallId').val();

                                // Fetch and populate the menu table
                                fetchAndPopulateMenuTable(stallId);
                                $('#editModal').modal('show');
                                // You may want to update the menu table or take other actions
                            },
                            error: function (xhr, status, error) {
                                console.error('Error updating menu data:', status, error);
                                // Handle error, e.g., show an alert to the user
                            }
                        });
                    });
                    
                    // Event listener for the "Add Menu" button click
                    $(document).on('click', '.add-menu', function () {

                        var stallId = $(this).data('stallid');
                        // Show the "Add Menu" modal
                        $('#editModal').modal('hide');
                        $('#addMenuModal').modal('show');
                        $('#addMenuForm input[name="stall_id"]').val(stallId);
                    });
                    // Event listener for the addMenuForm submission
                    $('#addMenuForm').submit(function (e) {
                        e.preventDefault();

                        // Get form data
                        var formData = $(this).serialize();
                        formData += '&stall_id=' + $('#addMenuStallId').val();
                    
                        // Use AJAX to send new menu data to the server
                        $.ajax({
                            url: 'ajax_addmenu.php', // Replace with your server-side script to add menu data
                            method: 'POST',
                            data: formData,
                            success: function (response) {
                                // Handle success, e.g., close the modal or update the UI
                                console.log(response);
                                $('#addMenuModal').modal('hide');

                                // Get the stallId from the edit form or wherever it is stored
                                var stallId = $('#editStallId').val();

                                // Fetch and populate the updated menu table
                                fetchAndPopulateMenuTable(stallId);
                                $('#editModal').modal('show');
                                // You may want to update the menu table or take other actions
                            },
                            error: function (xhr, status, error) {
                                console.error('Error adding menu data:', status, error);
                                // Handle error, e.g., show an alert to the user
                            }
                        });
                    });
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
                                    url: 'ajax_getstalls.php',
                                    data: { new_kantin_id: newKantinId },
                                    success: function (response) {
                                        // Replace the existing card content with the new HTML
                                        $('.album .container .row').html(response);
                                    }
                                });
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
                            <?php foreach ($stalls as $stall): ?>
                                <div class="col-md-4">
                                    <div class="card mb-4 box-shadow">
                                        <img class="card-img-top" src="<?= $stall['image']; ?>" alt="Stall Image">
                                        <div class="card-body">
                                            <h1 class="h6"><?= $stall['name']; ?></h1>
                                            <p class="card-text"><?= $stall['description']; ?></p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary view-menu" data-stallid="<?= $stall['stall_id']; ?>">View</button>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary edit-stall" data-stallid="<?= $stall['stall_id']; ?>" data-ownerid="<?= $stall['owner_id']; ?>">Edit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <!-- Modal for displaying menu -->
                <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="stallName" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="stallName"></h5>
                            </div>
                            <div class="modal-body">
                                <div id="menuContent"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add a new modal for editing -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Stall</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Form for editing stall details -->
                                <form id="editStallForm" method="post">
                                    <div class="form-group">
                                        <label for="editStallName">Name:</label>
                                        <input type="text" class="form-control" id="editStallName" name="edit_stall_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editStallDescription">Description:</label>
                                        <textarea class="form-control" id="editStallDescription" name="edit_stall_description" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="editStallImage">Image URL:</label>
                                        <textarea class="form-control" id="editStallImage" name="edit_stall_image" rows="3" required></textarea>
                                    </div>
                                    <input type="hidden" id="editStallId" name="edit_stall_id">
                                    <div class="form-group">
                                        <label for="editKantinId">Kantin:</label>
                                        <!-- Replace the input field with a select element for Kantin ID -->
                                        <select class="form-control" id="editKantinId" name="edit_kantin_id" required>
                                            <!-- You can dynamically populate the options using PHP if needed -->
                                            <option value="1">P</option>
                                            <option value="2">Q</option>
                                            <option value="3">W</option>
                                            <option value="4">T</option>
                                        </select>
                                    </div>

                                    <!-- Table view for each menu -->
                                    <h5 class ="mt-3">Menu List</h5>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Menu Name</th>
                                                <th>Action</th>
                                                <!-- Add more columns as needed -->
                                            </tr>
                                        </thead>
                                        <tbody id="menuTableBody">
                                            <!-- Table rows will be dynamically populated using JavaScript -->
                                        </tbody>
                                    </table>
                                    <button type="button" class="btn btn-sm btn-outline-secondary add-menu" data-stallid="" >Add Menu</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- New modal for editing the menu -->
                <div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog" aria-labelledby="editMenuModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editMenuModalLabel">Edit Menu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Form for editing menu details -->
                                <form id="editMenuForm" method="post">
                                    <div class="form-group">
                                        <label for="editMenuName">Name:</label>
                                        <input type="text" class="form-control" id="editMenuName" name="edit_menu_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="editMenuDescription">Description:</label>
                                        <textarea class="form-control" id="editMenuDescription" name="edit_menu_description" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="editMenuImage">Image URL:</label>
                                        <textarea class="form-control" id="editMenuImage" name="edit_menu_image" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="editMenuPrice">Price:</label>
                                        <input type="text" class="form-control" id="editMenuPrice" name="edit_menu_price" required>
                                    </div>
                                    <!-- Hidden input for menu_id -->
                                    <input type="hidden" id="editMenuId" name="edit_menu_id">

                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal for adding a new menu -->
                <div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog" aria-labelledby="addMenuModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addMenuModalLabel">Add Menu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Form for adding menu details -->
                                <form id="addMenuForm" method="post">       
                                    <input type="hidden" id="addMenuStallId" name="stall_id">                 
                                    <div class="form-group">
                                        <label for="addMenuName">Name:</label>
                                        <input type="text" class="form-control" id="addMenuName" name="add_menu_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="addMenuDescription">Description:</label>
                                        <textarea class="form-control" id="addMenuDescription" name="add_menu_description" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="addMenuImage">Image URL:</label>
                                        <textarea class="form-control" id="addMenuImage" name="add_menu_image" rows="3" required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="addMenuPrice">Price:</label>
                                        <input type="text" class="form-control" id="addMenuPrice" name="add_menu_price" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Add Menu</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal for displaying permission message -->
                <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="permissionModalLabel">Permission Denied</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>You don't have permission to edit this stall.</p>
                            </div>
                        </div>
                    </div>
                </div>
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
            </main>
            
        </body>
    </html>