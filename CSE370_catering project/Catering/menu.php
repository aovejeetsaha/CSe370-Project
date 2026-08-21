<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$user_id = $_SESSION["user_id"];

/*
Get all menu items
*/

$sql = "SELECT * FROM menu_items";

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


/*
Calculate total number of items
*/

$total_items = 0;

foreach ($cart as $quantity) {
    $total_items += $quantity;
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Menu</title>

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

        .cart-button {
            margin-bottom: 20px;
        }

    </style>

</head>

<body>
    <?php require "header.php"; ?>

    <h1>Catering Service</h1>

    <h2>Menu</h2>

    <!-- Cart button at the top -->

    <div class="cart-button">

        <a href="cart.php">

            <button>
                Go to Cart (<?php echo $total_items; ?>)
            </button>

        </a>

    </div>


    <!-- Menu items -->

    <div class="menu-grid">

    <?php

    while ($item = mysqli_fetch_assoc($result)) {

        $item_id = $item["item_id"];

        if (isset($cart[$item_id])) {
            $quantity = $cart[$item_id];
        } else {
            $quantity = 0;
        }

        echo "<div class='menu-item'>";

        echo "<h3>" . $item["item_name"] . "</h3>";

        echo "<p>" . $item["description"] . "</p>";

        echo "<p>Price: " . $item["price"] . " Taka</p>";


        /*
        Minus button
        */

        if ($quantity > 0) {

            echo "<form method='POST' action='update_cart.php' style='display:inline;'>";

            $find_cart = "SELECT cart_id FROM cart
                          WHERE user_id = '$user_id'
                          AND item_id = '$item_id'";

            $find_result = mysqli_query($conn, $find_cart);

            $cart_row = mysqli_fetch_assoc($find_result);

            echo "<input type='hidden' name='cart_id' value='" . $cart_row["cart_id"] . "'>";

            echo "<input type='hidden' name='from' value='menu'>";

            echo "<button type='submit' name='action' value='decrease'>-</button>";

            echo "</form>";
        }


        echo " <strong>" . $quantity . "</strong> ";


        /*
        Plus button
        */

        echo "<form method='POST' action='add_to_cart.php' style='display:inline;'>";

        echo "<input type='hidden' name='item_id' value='" . $item_id . "'>";

        echo "<button type='submit'>+</button>";

        echo "</form>";

        echo "</div>";
    }

    ?>

    </div>

</body>

</html>