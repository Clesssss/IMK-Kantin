<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); 
    }
    $welcomeMessage = "";
    if (isset($_SESSION['user_name'])) {
        $welcomeMessage = "Welcome, <strong>" . $_SESSION['user_name'] . "</strong>";
    }
?>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
    <a href ="index.php"><img src="download (2).png" alt="PCU" width="60" height="35.4838709677" class="me-2"></a>
    <a href ="index.php" style="color: black; text-decoration: none;"><h5 class="my-0 me-md-auto fw-normal">Canteen</h5></a>
    <nav class="my-2 my-md-0 ms-md-auto">
        <a class="p-2 text-dark" href="stall.php">Stall</a>
        <a class="p-2 text-dark" href="menu.php">Menu</a>
        <a id="cartLink" class="p-2 text-dark" href="cart.php">Cart</a>
        <a id="historyLink" class="p-2 text-dark" href="history.php">History</a>
        <a id="orderLink" class="p-2 text-dark" href="order.php">Order</a>
    </nav>
    <?php if (!empty($welcomeMessage)): ?>
        <!-- Divider between navigation links and welcome message -->
         <div class="vertical-divider" style="height: 35px; width: 1px; background-color: #000; margin: 0 10px; display: inline-block;"></div> 

        <!-- Display welcome message and logout link if the user is logged in -->
        <span><?= $welcomeMessage; ?></span>
        <a class="btn btn-link" href="logout.php">Logout</a>
    <?php else: ?>
        <!-- Display Sign Up button if the user is not logged in -->
        <a class="btn btn-outline-primary mx-3" href="signup.php">Sign up</a>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Login Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You need to log in to access this feature. Click the button below to log in.</p>
                <a href="login.php" class="btn btn-primary">Log In</a>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Show login modal when the Cart link is clicked
        $('#cartLink').on('click', function (e) {
            <?php if (!isset($_SESSION['user_name'])): ?>
                e.preventDefault(); // Prevent navigation to cart.php
                $('#loginModal').modal('show');
            <?php endif; ?>
        });
        // Show login modal when the Cart link is clicked
        $('#historyLink').on('click', function (e) {
            <?php if (!isset($_SESSION['user_name'])): ?>
                e.preventDefault(); // Prevent navigation to cart.php
                $('#loginModal').modal('show');
            <?php endif; ?>
        });
        $('#orderLink').on('click', function (e) {
            <?php if (!isset($_SESSION['user_name'])): ?>
                e.preventDefault(); // Prevent navigation to cart.php
                $('#loginModal').modal('show');
            <?php endif; ?>
        });
    });
</script>