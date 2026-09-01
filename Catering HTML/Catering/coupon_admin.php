<?php

session_start();

require "db.php";
require "feature_helpers.php";

require_administrator();

$success_message = "";
$error_message = "";

/*
Create a new coupon.
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["create_coupon"])
) {
    $coupon_code = strtoupper(trim($_POST["coupon_code"] ?? ""));

    $discount_amount = filter_input(
        INPUT_POST,
        "discount_amount",
        FILTER_VALIDATE_FLOAT
    );

    $expiry_date = $_POST["expiry_date"] ?? "";

    $coupon_code_is_valid = preg_match(
        '/^[A-Z0-9_-]{3,30}$/',
        $coupon_code
    );

    if (!$coupon_code_is_valid) {
        $error_message = "Use 3 to 30 letters, numbers, underscores, or hyphens.";
    } elseif (!$discount_amount || $discount_amount <= 0) {
        $error_message = "The discount amount must be greater than zero.";
    } elseif ($expiry_date < date("Y-m-d")) {
        $error_message = "The expiry date cannot be in the past.";
    } else {
        $coupon_status = "Active";

        $insert_sql = "
            INSERT INTO discount_coupon (
                coupon_code,
                discount_amount,
                expiry_date,
                coupon_status
            )
            VALUES (?, ?, ?, ?)
        ";

        $insert_statement = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param(
            $insert_statement,
            "sdss",
            $coupon_code,
            $discount_amount,
            $expiry_date,
            $coupon_status
        );

        if (mysqli_stmt_execute($insert_statement)) {
            $success_message = "Coupon created successfully.";
        } elseif (mysqli_errno($conn) === 1062) {
            $error_message = "That coupon code already exists.";
        } else {
            $error_message = "The coupon could not be created.";
        }

        mysqli_stmt_close($insert_statement);
    }
}

/*
Switch an existing coupon between Active and Inactive.
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST["toggle_coupon"])
) {
    $coupon_id = filter_input(
        INPUT_POST,
        "coupon_id",
        FILTER_VALIDATE_INT
    );

    if (!$coupon_id) {
        $error_message = "The selected coupon is invalid.";
    } else {
        $update_sql = "
            UPDATE discount_coupon
            SET coupon_status = CASE
                WHEN coupon_status = 'Active' THEN 'Inactive'
                ELSE 'Active'
            END
            WHERE coupon_id = ?
        ";

        $update_statement = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_statement, "i", $coupon_id);

        if (mysqli_stmt_execute($update_statement)) {
            $success_message = "Coupon status updated.";
        } else {
            $error_message = "The coupon status could not be updated.";
        }

        mysqli_stmt_close($update_statement);
    }
}

$coupons_sql = "
    SELECT
        coupon_id,
        coupon_code,
        discount_amount,
        expiry_date,
        coupon_status
    FROM discount_coupon
    ORDER BY coupon_id DESC
";

$coupons_result = mysqli_query($conn, $coupons_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Coupon Administration</title>
    <link rel="stylesheet" href="feature_style.css">
</head>
<body>

    <?php require "admin_header.php"; ?>

    <main class="feature-container">
        <h1>Coupon Administration</h1>

        <?php if ($success_message !== "") { ?>
            <p class="feature-success">
                <?php echo escape_output($success_message); ?>
            </p>
        <?php } ?>

        <?php if ($error_message !== "") { ?>
            <p class="feature-error">
                <?php echo escape_output($error_message); ?>
            </p>
        <?php } ?>

        <section class="feature-card">
            <h2>Create a Coupon</h2>

            <form method="POST" class="feature-form">
                <label>
                    Coupon code
                    <input type="text" name="coupon_code" maxlength="30" required>
                </label>

                <label>
                    Discount amount
                    <input
                        type="number"
                        name="discount_amount"
                        min="0.01"
                        step="0.01"
                        required
                    >
                </label>

                <label>
                    Expiry date
                    <input
                        type="date"
                        name="expiry_date"
                        min="<?php echo date("Y-m-d"); ?>"
                        required
                    >
                </label>

                <button
                    type="submit"
                    name="create_coupon"
                    class="feature-button"
                >
                    Create Coupon
                </button>
            </form>
        </section>

        <section class="feature-card">
            <h2>Existing Coupons</h2>

            <div class="table-wrap">
                <table class="feature-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Discount</th>
                            <th>Expiry</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($coupon = mysqli_fetch_assoc($coupons_result)) { ?>
                            <tr>
                                <td><?php echo escape_output($coupon["coupon_code"]); ?></td>
                                <td><?php echo format_money((float) $coupon["discount_amount"]); ?></td>
                                <td><?php echo escape_output($coupon["expiry_date"]); ?></td>
                                <td><?php echo escape_output($coupon["coupon_status"]); ?></td>
                                <td>
                                    <form method="POST">
                                        <input
                                            type="hidden"
                                            name="coupon_id"
                                            value="<?php echo (int) $coupon["coupon_id"]; ?>"
                                        >

                                        <button type="submit" name="toggle_coupon">
                                            <?php
                                            if ($coupon["coupon_status"] === "Active") {
                                                echo "Deactivate";
                                            } else {
                                                echo "Activate";
                                            }
                                            ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>
