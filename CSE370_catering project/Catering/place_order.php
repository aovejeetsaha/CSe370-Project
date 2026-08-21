<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$user_id = $_SESSION["user_id"];


/*
Get everything from the user's cart
*/

$sql = "SELECT cart.item_id,
               cart.quantity,
               menu_items.price
        FROM cart
        JOIN menu_items
        ON cart.item_id = menu_items.item_id
        WHERE cart.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Your cart is empty.");
}


/*
Calculate total price
*/

$total_price = 0;

$cart_items = [];

while ($item = mysqli_fetch_assoc($result)) {

    $item_total = $item["price"] * $item["quantity"];

    $total_price += $item_total;

    $cart_items[] = $item;
}


/*
Create the order
*/

$sql = "INSERT INTO orders (user_id, total_amount)
        VALUES ('$user_id', '$total_price')";

mysqli_query($conn, $sql);


/*
Get the newly created Order ID
*/

$order_id = mysqli_insert_id($conn);


/*
Insert every cart item into order_items
*/

foreach ($cart_items as $item) {

    $item_id = $item["item_id"];
    $quantity = $item["quantity"];
    $price = $item["price"];

    $sql = "INSERT INTO order_items
            (order_id, item_id, quantity, price)
            VALUES
            ('$order_id', '$item_id', '$quantity', '$price')";

    mysqli_query($conn, $sql);
}


/*
Clear the cart
*/

$sql = "DELETE FROM cart
        WHERE user_id = '$user_id'";

mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>Order Confirmed</title>
</head>

<body>
    <?php require "header.php"; ?>
    <h1>Order Confirmed!</h1>

    <details>

        <summary>
            <strong>Order ID: <?php echo $order_id; ?></strong>
        </summary>

        <br>

        <?php

        $sql = "SELECT menu_items.item_name,
                       order_items.quantity,
                       order_items.price
                FROM order_items
                JOIN menu_items
                ON order_items.item_id = menu_items.item_id
                WHERE order_items.order_id = '$order_id'";

        $order_result = mysqli_query($conn, $sql);

        while ($item = mysqli_fetch_assoc($order_result)) {

            $item_total = $item["price"] * $item["quantity"];

            echo "<p>";
            echo $item["item_name"];
            echo " × ";
            echo $item["quantity"];
            echo " = ";
            echo $item_total;
            echo " Taka";
            echo "</p>";

        }

        ?>

    </details>

    <h3>Total: <?php echo $total_price; ?> Taka</h3>

    <h3>Thank you for your order.</h3>

    <a href="menu.php">
        <button>Back to Menu</button>
    </a>

</body>

</html>