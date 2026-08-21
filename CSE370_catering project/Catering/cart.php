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
</head>

<body>

    <h1>Your Cart</h1>

    <?php

    if (mysqli_num_rows($result) == 0) {

        echo "<p>Your cart is empty.</p>";

    } else {

        while ($item = mysqli_fetch_assoc($result)) {

            $item_total = $item["price"] * $item["quantity"];

            $total_items += $item["quantity"];

            $total_price += $item_total;

            echo "<h3>" . $item["item_name"] . "</h3>";

            echo "<p>Price: " . $item["price"] . " Taka</p>";

            /*
            Quantity controls
            */

            echo "<form method='POST' action='update_cart.php' style='display:inline;'>";

            echo "<input type='hidden' name='cart_id' value='" . $item["cart_id"] . "'>";

            echo "<button type='submit' name='action' value='decrease'>-</button>";

            echo "</form>";

            echo " <strong>" . $item["quantity"] . "</strong> ";

            echo "<form method='POST' action='update_cart.php' style='display:inline;'>";

            echo "<input type='hidden' name='cart_id' value='" . $item["cart_id"] . "'>";

            echo "<button type='submit' name='action' value='increase'>+</button>";

            echo "</form>";

            echo "<p>Item Total: " . $item_total . " Taka</p>";

            /*
            Remove entire item
            */

            echo "<form method='POST' action='delete_cart.php'>";

            echo "<input type='hidden' name='cart_id' value='" . $item["cart_id"] . "'>";

            echo "<button type='submit'>Remove</button>";

            echo "</form>";

            echo "<hr>";
        }

        echo "<h2>Total Items: " . $total_items . "</h2>";

        echo "<h2>Total Price: " . $total_price . " Taka</h2>";
        echo "<form method='POST' action='place_order.php'>";
        echo "<button type='submit'>Place Order</button>";
        echo "</form>";
    }

    ?>

    <br>

    <a href="menu.php">
        <button>Back to Menu</button>
    </a>

</body>

</html>