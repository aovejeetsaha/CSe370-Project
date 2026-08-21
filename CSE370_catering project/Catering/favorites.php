<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$user_id = $_SESSION["user_id"];


/*
Get user's favorite menu items
*/

$sql = "SELECT menu_items.*
        FROM favorites
        JOIN menu_items
        ON favorites.item_id = menu_items.item_id
        WHERE favorites.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);


/*
Get the current user's cart quantities
*/

$cart = [];

$sql_cart = "SELECT item_id, quantity
             FROM cart
             WHERE user_id = '$user_id'";

$cart_result = mysqli_query($conn, $sql_cart);

while ($row = mysqli_fetch_assoc($cart_result)) {
    $cart[$row["item_id"]] = $row["quantity"];
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Favorites</title>

    <style>

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .menu-item {
            border: 1px solid black;
            padding: 15px;
        }

    </style>

</head>

<body>

    <?php require "header.php"; ?>

    <h1>Favorites</h1>

    <div class="menu-grid">

    <?php

    if (mysqli_num_rows($result) == 0) {

        echo "<p>You have no favorite items yet.</p>";

    } else {

        while ($item = mysqli_fetch_assoc($result)) {

            $item_id = $item["item_id"];


            /*
            Get quantity currently in cart
            */

            if (isset($cart[$item_id])) {
                $quantity = $cart[$item_id];
            } else {
                $quantity = 0;
            }


            echo "<div class='menu-item'>";


            /*
            Item name + yellow favorite star
            */

            echo "<h3>";

            echo $item["item_name"];

            echo " ";

            echo "<form method='POST' action='favorite.php' style='display:inline;'>";

            echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";

            echo "<button type='submit' style='border:none; background:none; font-size:20px; color:#FFD700; cursor:pointer;'>★</button>";

            echo "</form>";

            echo "</h3>";


            echo "<p>" . $item["description"] . "</p>";

            echo "<p>Price: " . $item["price"] . " Taka</p>";


            /*
            Minus button
            */

            if ($quantity > 0) {

                echo "<form method='POST' action='update_cart.php' style='display:inline;'>";

                /*
                Find cart_id
                */

                $find_cart = "SELECT cart_id
                              FROM cart
                              WHERE user_id = '$user_id'
                              AND item_id = '$item_id'";

                $find_result = mysqli_query($conn, $find_cart);

                $cart_row = mysqli_fetch_assoc($find_result);

                echo "<input type='hidden' name='cart_id' value='" . $cart_row["cart_id"] . "'>";

                /*
                Tell update_cart.php to return to favorites
                */

                echo "<input type='hidden' name='from' value='favorites'>";

                echo "<button type='submit' name='action' value='decrease'>-</button>";

                echo "</form>";

            }


            /*
            Current quantity
            */

            echo " <strong>" . $quantity . "</strong> ";


            /*
            Plus button
            */

            echo "<form method='POST' action='add_to_cart.php' style='display:inline;'>";

            echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";

            echo "<input type='hidden' name='from' value='favorites'>";

            echo "<button type='submit'>+</button>";

            echo "</form>";


            echo "</div>";

        }

    }

    ?>

    </div>

</body>

</html>