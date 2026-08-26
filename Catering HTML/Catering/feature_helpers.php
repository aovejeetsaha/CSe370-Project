<?php

function require_logged_in_user(): int
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }

    return (int) $_SESSION["user_id"];
}

function require_administrator(): void
{
    require_logged_in_user();

    $role = $_SESSION["role"] ?? "customer";

    if ($role !== "admin") {
        http_response_code(403);
        die("Admin access is required.");
    }
}

function escape_output(?string $value): string
{
    return htmlspecialchars($value ?? "", ENT_QUOTES, "UTF-8");
}

function format_money(float $amount): string
{
    return number_format($amount, 2) . " Taka";
}

function find_valid_coupon(
    mysqli $conn,
    string $coupon_code,
    float $subtotal
): array {
    $coupon_code = strtoupper(trim($coupon_code));

    if ($coupon_code === "") {
        return [null, "Please enter a coupon code."];
    }

    $sql = "
        SELECT
            coupon_id,
            coupon_code,
            discount_amount,
            expiry_date,
            coupon_status
        FROM discount_coupon
        WHERE coupon_code = ?
    ";

    $statement = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($statement, "s", $coupon_code);
    mysqli_stmt_execute($statement);

    $result = mysqli_stmt_get_result($statement);
    $coupon = mysqli_fetch_assoc($result);

    mysqli_stmt_close($statement);

    if (!$coupon) {
        return [null, "Coupon code was not found."];
    }

    if ($coupon["coupon_status"] !== "Active") {
        return [null, "This coupon is inactive."];
    }

    if ($coupon["expiry_date"] < date("Y-m-d")) {
        return [null, "This coupon has expired."];
    }

    $discount_amount = (float) $coupon["discount_amount"];

    if ($discount_amount > $subtotal) {
        return [null, "The discount is larger than the cart subtotal."];
    }

    return [$coupon, "Coupon applied successfully."];
}
?>
