<?php

session_start();

require "db.php";
require "feature_helpers.php";

$user_id = require_logged_in_user();

$order_id = 0;

if (isset($_SESSION["pending_order_id"])) {
    $order_id = (int) $_SESSION["pending_order_id"];
} elseif (isset($_GET["order_id"])) {
    $order_id = (int) $_GET["order_id"];
}

/*
Read the order amount from the database and confirm that the order belongs
to the logged-in user. Payment information is loaded through order_payment.
*/

$order_sql = "
    SELECT
        orders.order_id,
        orders.total_amount,
        payment.payment_id,
        payment.payment_date,
        payment.payment_method,
        payment.payment_status
    FROM orders
    LEFT JOIN order_payment
        ON order_payment.order_id = orders.order_id
    LEFT JOIN payment
        ON payment.payment_id = order_payment.payment_id
    WHERE orders.order_id = ?
        AND orders.user_id = ?
";

$order_statement = mysqli_prepare($conn, $order_sql);
mysqli_stmt_bind_param($order_statement, "ii", $order_id, $user_id);
mysqli_stmt_execute($order_statement);

$order_result = mysqli_stmt_get_result($order_statement);
$order = mysqli_fetch_assoc($order_result);

mysqli_stmt_close($order_statement);

if (!$order) {
    http_response_code(404);
    die("The requested order was not found.");
}

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($order["payment_id"]) {
        $error_message = "This order already has a payment.";
    } else {
        $payment_method = $_POST["payment_method"] ?? "";

        $allowed_payment_methods = [
            "Cash",
            "Card",
            "Mobile Banking"
        ];

        if (!in_array($payment_method, $allowed_payment_methods, true)) {
            $error_message = "Please select a valid payment method.";
        } else {
            $payment_amount = (float) $order["total_amount"];
            $payment_status = "Successful";

            mysqli_begin_transaction($conn);

            try {
                $payment_sql = "
                    INSERT INTO payment (
                        amount,
                        payment_method,
                        payment_status
                    )
                    VALUES (?, ?, ?)
                ";

                $payment_statement = mysqli_prepare($conn, $payment_sql);
                mysqli_stmt_bind_param(
                    $payment_statement,
                    "dss",
                    $payment_amount,
                    $payment_method,
                    $payment_status
                );

                if (!mysqli_stmt_execute($payment_statement)) {
                    throw new Exception("The payment could not be saved.");
                }

                $payment_id = mysqli_insert_id($conn);
                mysqli_stmt_close($payment_statement);

                $relationship_sql = "
                    INSERT INTO order_payment (
                        order_id,
                        payment_id
                    )
                    VALUES (?, ?)
                ";

                $relationship_statement = mysqli_prepare(
                    $conn,
                    $relationship_sql
                );

                mysqli_stmt_bind_param(
                    $relationship_statement,
                    "ii",
                    $order_id,
                    $payment_id
                );

                if (!mysqli_stmt_execute($relationship_statement)) {
                    throw new Exception("The payment could not be connected to the order.");
                }

                mysqli_stmt_close($relationship_statement);
                mysqli_commit($conn);

                unset($_SESSION["pending_order_id"]);

                header("Location: payment.php?order_id=" . $order_id);
                exit;
            } catch (Throwable $error) {
                mysqli_rollback($conn);
                $error_message = "The payment could not be recorded. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" href="feature_style.css">
</head>
<body>

    <?php require "header.php"; ?>

    <main class="feature-container">
        <h1>Payment</h1>

        <?php if ($error_message !== "") { ?>
            <p class="feature-error">
                <?php echo escape_output($error_message); ?>
            </p>
        <?php } ?>

        <?php if ($order["payment_id"]) { ?>

            <section class="feature-card">
                <h2>Payment Receipt</h2>

                <p class="feature-success">Payment recorded successfully.</p>
                <p>Payment ID: <strong><?php echo (int) $order["payment_id"]; ?></strong></p>
                <p>Order ID: <strong><?php echo (int) $order["order_id"]; ?></strong></p>
                <p>Amount: <strong><?php echo format_money((float) $order["total_amount"]); ?></strong></p>
                <p>Method: <strong><?php echo escape_output($order["payment_method"]); ?></strong></p>
                <p>Status: <strong><?php echo escape_output($order["payment_status"]); ?></strong></p>
                <p>Date: <strong><?php echo escape_output($order["payment_date"]); ?></strong></p>

                <a href="orders.php">View My Orders</a>
            </section>

        <?php } else { ?>

            <section class="feature-card">
                <h2>Order #<?php echo (int) $order["order_id"]; ?></h2>

                <p class="feature-total">
                    Amount due: <?php echo format_money((float) $order["total_amount"]); ?>
                </p>

                <p>
                    This is a classroom simulation. No real money is transferred.
                </p>

                <form method="POST" class="feature-form">
                    <label>
                        Payment method
                        <select name="payment_method" required>
                            <option value="">Choose a method</option>
                            <option value="Cash">Cash</option>
                            <option value="Card">Card</option>
                            <option value="Mobile Banking">Mobile Banking</option>
                        </select>
                    </label>

                    <button type="submit" class="feature-button">
                        Complete Simulated Payment
                    </button>
                </form>
            </section>

        <?php } ?>
    </main>
</body>
</html>
