<?php

session_start();

require "db.php";
require "feature_helpers.php";

$user_id = require_logged_in_user();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: cart.php");
    exit;
}

/*
Read the cart again from the database.
This prevents the browser from sending a false price or total.
*/

$cart_sql = "
    SELECT
        cart.item_id,
        cart.quantity,
        menu_items.price
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
$subtotal = 0.00;

while ($item = mysqli_fetch_assoc($cart_result)) {
    $item_total = (float) $item["price"] * (int) $item["quantity"];

    $subtotal += $item_total;
    $cart_items[] = $item;
}

mysqli_stmt_close($cart_statement);

if (count($cart_items) === 0) {
    die("Your cart is empty.");
}

/*
Validate the coupon again immediately before creating the order.
*/

$coupon = null;
$saved_coupon_code = $_SESSION["coupon_code"] ?? "";

if ($saved_coupon_code !== "") {
    [$coupon] = find_valid_coupon(
        $conn,
        $saved_coupon_code,
        $subtotal
    );
}

$discount_amount = 0.00;

if ($coupon) {
    $discount_amount = (float) $coupon["discount_amount"];
}

$final_total = max(0, $subtotal - $discount_amount);

/*
The transaction keeps the order, items, coupon relationship, and cart cleanup
together. If one query fails, none of these changes are saved.
*/

mysqli_begin_transaction($conn);

try {
    $order_sql = "
        INSERT INTO orders (
            user_id,
            total_amount
        )
        VALUES (?, ?)
    ";

    $order_statement = mysqli_prepare($conn, $order_sql);
    mysqli_stmt_bind_param(
        $order_statement,
        "id",
        $user_id,
        $final_total
    );

    if (!mysqli_stmt_execute($order_statement)) {
        throw new Exception("The order could not be created.");
    }

    $order_id = mysqli_insert_id($conn);
    mysqli_stmt_close($order_statement);

    $item_sql = "
        INSERT INTO order_items (
            order_id,
            item_id,
            quantity,
            price
        )
        VALUES (?, ?, ?, ?)
    ";

    $item_statement = mysqli_prepare($conn, $item_sql);

    foreach ($cart_items as $item) {
        $item_id = (int) $item["item_id"];
        $quantity = (int) $item["quantity"];
        $price = (float) $item["price"];

        mysqli_stmt_bind_param(
            $item_statement,
            "iiid",
            $order_id,
            $item_id,
            $quantity,
            $price
        );

        if (!mysqli_stmt_execute($item_statement)) {
            throw new Exception("An order item could not be saved.");
        }
    }

    mysqli_stmt_close($item_statement);

    if ($coupon) {
        $coupon_id = (int) $coupon["coupon_id"];

        $coupon_order_sql = "
            INSERT INTO coupon_orders (
                coupon_id,
                order_id
            )
            VALUES (?, ?)
        ";

        $coupon_order_statement = mysqli_prepare($conn, $coupon_order_sql);
        mysqli_stmt_bind_param(
            $coupon_order_statement,
            "ii",
            $coupon_id,
            $order_id
        );

        if (!mysqli_stmt_execute($coupon_order_statement)) {
            throw new Exception("The coupon could not be connected to the order.");
        }

        mysqli_stmt_close($coupon_order_statement);
    }

    $clear_cart_sql = "
        DELETE FROM cart
        WHERE user_id = ?
    ";

    $clear_cart_statement = mysqli_prepare($conn, $clear_cart_sql);
    mysqli_stmt_bind_param($clear_cart_statement, "i", $user_id);

    if (!mysqli_stmt_execute($clear_cart_statement)) {
        throw new Exception("The cart could not be cleared.");
    }

    mysqli_stmt_close($clear_cart_statement);

    mysqli_commit($conn);

    unset($_SESSION["coupon_code"]);
    $_SESSION["pending_order_id"] = $order_id;

    header("Location: payment.php");
    exit;
} catch (Throwable $error) {
    mysqli_rollback($conn);

    http_response_code(500);
    die("The order could not be completed. Please try again.");
}
?>
