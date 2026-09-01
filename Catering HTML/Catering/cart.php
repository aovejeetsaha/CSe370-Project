<?php

session_start();

require "db.php";
require "feature_helpers.php";

$user_id = require_logged_in_user();

/*
Get the same cart information that the original page displayed.
The prepared query keeps the logged-in user's ID separate from the SQL text.
*/

$cart_sql = "
    SELECT
        cart.cart_id,
        menu_items.item_name,
        menu_items.price,
        cart.quantity
    FROM cart
    JOIN menu_items
        ON cart.item_id = menu_items.item_id
    WHERE cart.user_id = ?
";

$cart_statement = mysqli_prepare($conn, $cart_sql);
mysqli_stmt_bind_param($cart_statement, "i", $user_id);
mysqli_stmt_execute($cart_statement);

$cart_result = mysqli_stmt_get_result($cart_statement);

$cart_items = [];
$total_items = 0;
$subtotal = 0.00;

while ($item = mysqli_fetch_assoc($cart_result)) {
    $item_total = (float) $item["price"] * (int) $item["quantity"];

    $item["item_total"] = $item_total;
    $cart_items[] = $item;

    $total_items += (int) $item["quantity"];
    $subtotal += $item_total;
}

mysqli_stmt_close($cart_statement);

/*
Validate a newly entered coupon, or restore the coupon saved in the session.
*/

$coupon = null;
$coupon_message = "";
$coupon_was_accepted = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $entered_coupon_code = $_POST["coupon_code"] ?? "";

    [$coupon, $coupon_message] = find_valid_coupon(
        $conn,
        $entered_coupon_code,
        $subtotal
    );

    if ($coupon) {
        $_SESSION["coupon_code"] = $coupon["coupon_code"];
        $coupon_was_accepted = true;
    } else {
        unset($_SESSION["coupon_code"]);
    }
} elseif (!empty($_SESSION["coupon_code"])) {
    [$coupon, $coupon_message] = find_valid_coupon(
        $conn,
        $_SESSION["coupon_code"],
        $subtotal
    );

    if ($coupon) {
        $coupon_was_accepted = true;
    } else {
        unset($_SESSION["coupon_code"]);
    }
}

$discount_amount = 0.00;

if ($coupon) {
    $discount_amount = (float) $coupon["discount_amount"];
}

$final_total = max(0, $subtotal - $discount_amount);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="feature_style.css">
</head>
<body>

    <?php require "header.php"; ?>

    <main class="feature-container">
        <h1>Your Cart</h1>

        <?php if (count($cart_items) === 0) { ?>

            <div class="feature-card">
                <p>Your cart is empty.</p>
            </div>

        <?php } ?>

        <?php foreach ($cart_items as $item) { ?>

            <div class="feature-card">
                <h3><?php echo escape_output($item["item_name"]); ?></h3>

                <p>
                    <?php echo format_money((float) $item["price"]); ?>
                    ×
                    <?php echo (int) $item["quantity"]; ?>
                    =
                    <?php echo format_money((float) $item["item_total"]); ?>
                </p>

                <form method="POST" action="update_cart.php">
                    <input
                        type="hidden"
                        name="cart_id"
                        value="<?php echo (int) $item["cart_id"]; ?>"
                    >

                    <button type="submit" name="action" value="decrease">-</button>
                    <button type="submit" name="action" value="increase">+</button>
                </form>

                <form method="POST" action="delete_cart.php">
                    <input
                        type="hidden"
                        name="cart_id"
                        value="<?php echo (int) $item["cart_id"]; ?>"
                    >

                    <button type="submit">Remove</button>
                </form>
            </div>

        <?php } ?>

        <?php if ($total_items > 0) { ?>

            <section class="feature-card">
                <h2>Discount Coupon</h2>

                <form method="POST" class="feature-form">
                    <label>
                        Coupon code
                        <input
                            type="text"
                            name="coupon_code"
                            maxlength="30"
                            value="<?php echo escape_output($_SESSION["coupon_code"] ?? ""); ?>"
                        >
                    </label>

                    <button type="submit" class="feature-button">Apply Coupon</button>
                </form>

                <?php if ($coupon_message !== "") { ?>

                    <p class="<?php echo $coupon_was_accepted ? "feature-success" : "feature-error"; ?>">
                        <?php echo escape_output($coupon_message); ?>
                    </p>

                <?php } ?>

                <p>Total items: <strong><?php echo $total_items; ?></strong></p>
                <p>Subtotal: <strong><?php echo format_money($subtotal); ?></strong></p>
                <p>Discount: <strong>-<?php echo format_money($discount_amount); ?></strong></p>
                <p class="feature-total">Final total: <?php echo format_money($final_total); ?></p>

                <form method="POST" action="place_order.php">
                    <button type="submit" class="feature-button">Confirm Order</button>
                </form>
            </section>

        <?php } ?>
    </main>
</body>
</html>
