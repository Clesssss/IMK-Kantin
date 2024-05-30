<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); 
    }
    $welcomeMessage = "";
    if (isset($_SESSION['user_name'])) {
        $welcomeMessage = "Welcome, <strong>" . $_SESSION['user_name'] . "</strong>";
    }
?>

<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 border-bottom box-shadow" style="background-color: #19304c" >
    <a href ="index.php"><img src="logopcu.webp" alt="PCU" width="100" height="35.4838709677" class="me-2"></a>
    <nav class="my-2 my-md-0 ms-md-auto">
        <a class="p-2 text-white" href="stall.php">Stall</a>
        <a id="menuLink" class="p-2 text-white" href="menu.php">Menu</a>
        <a id="cartLink" class="p-2 text-white" href="cart.php">Cart</a>
        <a id="historyLink" class="p-2 text-white" href="history.php">History</a>
        <a id="orderLink" class="p-2 text-white" href="order.php">Order</a>
    </nav>
    <?php if (!empty($welcomeMessage)): ?>
        <!-- Divider between navigation links and welcome message -->
         <div class="vertical-divider" style="height: 35px; width: 1px; background-color: #000; margin: 0 10px; display: inline-block;"></div> 

        <!-- Display welcome message and logout link if the user is logged in -->
        <span style="color: white"><?= $welcomeMessage; ?></span>
        <a class="btn btn-link" href="logout.php">Logout</a>
    <?php else: ?>
        <!-- Display Sign Up button if the user is not logged in -->
        <a class="btn btn-outline-light mx-3" href="signup.php">Sign up</a>
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