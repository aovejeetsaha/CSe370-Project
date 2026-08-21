<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$user_id = $_SESSION["user_id"];


$sql = "SELECT cart.cart_id,
               menu_items.item_name,
               menu_items.price,
               cart.quantity
        FROM cart
        JOIN menu_items
        ON cart.item_id = menu_items.item_id
        WHERE cart.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);


$total_items = 0;
$total_price = 0;

?>

<!DOCTYPE html>
<html>

<head>

    <title>Cart</title>

    <style>

        .cart-row {
            display: flex;
            align-items: center;
            width: 500px;
            padding: 10px 0;
            border-bottom: 1px solid #ccc;
        }

        .item-name {
            width: 220px;
        }

        .quantity {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .total {
            margin-top: 20px;
        }

    </style>

</head>

<body>
    <?php require "header.php"; ?>
    <h1>Your Cart</h1>


    <?php

    if (mysqli_num_rows($result) == 0) {

        echo "<p>Your cart is empty.</p>";

    } else {

        while ($item = mysqli_fetch_assoc($result)) {

            $item_total = $item["price"] * $item["quantity"];

            $total_items += $item["quantity"];

            $total_price += $item_total;

            echo "<div class='cart-row'>";


            /*
            Item name
            */

            echo "<div class='item-name'>";
            echo $item["item_name"];
            echo "</div>";


            /*
            Minus
            */

            echo "<div class='quantity'>";

            echo "<form method='POST' action='update_cart.php'>";

            echo "<input type='hidden' name='cart_id' value='" . $item["cart_id"] . "'>";

            echo "<button type='submit' name='action' value='decrease'>-</button>";

            echo "</form>";


            /*
            Quantity
            */

            echo "<strong>" . $item["quantity"] . "</strong>";


            /*
            Plus
            */

            echo "<form method='POST' action='update_cart.php'>";

            echo "<input type='hidden' name='cart_id' value='" . $item["cart_id"] . "'>";

            echo "<button type='submit' name='action' value='increase'>+</button>";

            echo "</form>";

            echo "</div>";


            /*
            Remove button
            */

            echo "<form method='POST' action='delete_cart.php' style='margin-left:20px;'>";

            echo "<input type='hidden' name='cart_id' value='" . $item["cart_id"] . "'>";

            echo "<button type='submit'>Remove</button>";

            echo "</form>";

            echo "</div>";
        }

    }

    ?>


    <div class="total">

        <h3>
            Total Items: <?php echo $total_items; ?>
        </h3>

        <h2>
            Total: <?php echo $total_price; ?> Taka
        </h2>


        <?php if ($total_items > 0) { ?>

            <form method="POST" action="place_order.php">

                <button type="submit">
                    Confirm Order
                </button>

            </form>

        <?php } ?>

        <br>



    </div>

</body>

</html>