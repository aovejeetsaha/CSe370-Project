<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$user_id = $_SESSION["user_id"];


/*
Get all orders belonging to this user
*/

$sql = "SELECT *
        FROM orders
        WHERE user_id = '$user_id'
        ORDER BY order_id DESC";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>
    <?php require "header.php"; ?>

    <title>My Orders</title>

</head>

<body>

    <h1>My Orders</h1>

    <?php

    if (mysqli_num_rows($result) == 0) {

        echo "<p>You have no orders yet.</p>";

    } else {

        while ($order = mysqli_fetch_assoc($result)) {

            $order_id = $order["order_id"];

            echo "<details>";

            echo "<summary>";

            echo "<strong>Order ID: " . $order_id . "</strong>";

            echo " - Total: " . $order["total_amount"] . " Taka";

            echo "</summary>";

            echo "<br>";


            /*
            Get items belonging to this order
            */

            $item_sql = "SELECT menu_items.item_name,
                                order_items.quantity,
                                order_items.price
                         FROM order_items
                         JOIN menu_items
                         ON order_items.item_id = menu_items.item_id
                         WHERE order_items.order_id = '$order_id'";

            $item_result = mysqli_query($conn, $item_sql);


            while ($item = mysqli_fetch_assoc($item_result)) {

                echo "<p>";

                echo $item["item_name"];

                echo " × ";

                echo $item["quantity"];

                echo " = ";

                echo $item["price"] * $item["quantity"];

                echo " Taka";

                echo "</p>";
            }

            echo "</details>";

            echo "<hr>";
        }
    }

    ?>

    <br>

    <a href="menu.php">

        <button>
            Back to Menu
        </button>

    </a>

</body>

</html>