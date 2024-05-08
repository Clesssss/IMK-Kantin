<?php
session_start();
include("connection.php");

// Assuming you have the owner_id in the session
$owner_id = $_SESSION['user_id'];

// Fetch data from the transaction table, join with the payment table, and filter by stall_id
$query = "SELECT t.transaction_id, t.date, t.total, p.name AS payment_name, t.status
          FROM transaction t
          JOIN payment p ON t.payment_id = p.payment_id
          WHERE t.stall_id IN (SELECT stall_id FROM stall WHERE owner_id = :owner_id)";

$stmt = $conn->prepare($query);
$stmt->bindParam(':owner_id', $owner_id, PDO::PARAM_INT);
$stmt->execute();
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Order</title>
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
            $('.btn-view').on('click', function () {
                $('#viewModal').modal('show');
                var transactionId = $(this).data('transaction-id');
                // Use AJAX to fetch data from the server based on transactionId
                $.ajax({
                    url: 'ajax_fetchtransactiondetails.php',
                    method: 'POST',
                    data: { transaction_id: transactionId },
                    dataType: 'json',
                    success: function (data) {
                        // Populate the modal body with transaction details
                        var detailsBody = $('#transactionDetailsBody');
                        detailsBody.empty(); // Clear existing content
                        $.each(data, function (index, item) {
                            detailsBody.append('<tr><td>' + item.name + '</td><td>' + item.quantity + '</td><td>' + item.note + '</td><td>' + item.subtotal + '</td></tr>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error('Error fetching transaction details:', status, error);
                    }
                });
            });
            $('.btn-finish').on('click', function () {
                // Handle the "Finish Order" button click event
                var transactionId = $(this).data('transaction-id');

                // Use AJAX to run the update query to set status to "Order Finished"
                $.ajax({
                    url: 'ajax_finishorder.php',
                    method: 'POST',
                    data: { transaction_id: transactionId },
                    success: function (response) {
                        // You can handle the response as needed
                        console.log(response);
                        // You might want to refresh the page or update the UI accordingly
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.error('Error finishing order:', status, error);
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

        .box-shadow {
            box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05);
        }

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
                <?php if (empty($transactions)): ?>
                    <p>No orders currently.</p>
                <?php else: ?>
                    <h1 class="jumbotron-heading">Order</h1>
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th>Order Date</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?= $transaction['date']; ?></td>
                                    <td><?= $transaction['total']; ?></td>
                                    <td><?= $transaction['payment_name']; ?></td>
                                    <td><?= $transaction['status']; ?></td>
                                    <td>
                                        <!-- View button triggers the modal -->
                                        <button type="button" class="btn btn-outline-secondary btn-sm btn-view" data-transaction-id="<?= $transaction['transaction_id'] ?>">
                                            View
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary btn-sm btn-finish" data-transaction-id="<?= $transaction['transaction_id'] ?>">
                                            Finish Order
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewModalLabel">Transaction Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Menu Name</th>
                                <th>Quantity</th>
                                <th>Note</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="transactionDetailsBody">
                            <!-- Details will be populated here dynamically using JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 