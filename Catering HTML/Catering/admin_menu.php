<?php

session_start();

require "db.php";

/*
Only logged-in admins can access this page
*/

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}


/*
Change item availability
*/

if (isset($_POST["toggle_availability"])) {

    $item_id = $_POST["item_id"];

    $sql = "UPDATE menu_items
            SET available = NOT available
            WHERE item_id = '$item_id'";

    mysqli_query($conn, $sql);

    header("Location: admin_menu.php");
    exit();
}

/*
Move item to Trash
*/

if (isset($_POST["move_to_trash"])) {

    $item_id = $_POST["item_id"];

    $sql = "UPDATE menu_items
            SET deleted = 1
            WHERE item_id = '$item_id'";

    mysqli_query($conn, $sql);

    header("Location: admin_menu.php");
    exit();
}

/*
Get all menu items
*/

$sql = "SELECT * FROM menu_items
        WHERE deleted = 0
        AND permanently_deleted = 0
        ORDER BY item_id";
        
$result = mysqli_query($conn, $sql);



?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Menu</title>

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

    <h1>Catering Service</h1>

    <?php require "admin_header.php"; ?>


    <h2>Manage Menu</h2>


    <p>
        <a href="admin_add_item.php">
            <button>Add New Menu Item</button>
        </a>
    </p>


    <div class="menu-grid">

    <?php

    while ($item = mysqli_fetch_assoc($result)) {

        echo "<div class='menu-item'>";

        echo "<h3>" . $item["item_name"] . "</h3>";

        echo "<p>" . $item["description"] . "</p>";

        echo "<p>Price: " . $item["price"] . " Taka</p>";


        if ($item["available"] == 1) {

            echo "<p><strong>Available</strong></p>";

        } else {

            echo "<p><strong>Unavailable</strong></p>";

        }


        echo "<a href='admin_edit_item.php?item_id="
            . $item["item_id"]
            . "'>";

        echo "<button>Edit</button>";

        echo "</a>";

        echo " ";


        /*
        Available / Unavailable button
        */

        echo "<form method='POST' style='display:inline;'>";

        echo "<input type='hidden' name='item_id'
            value='" . $item["item_id"] . "'>";

        echo "<button type='submit'
            name='toggle_availability'>";

        if ($item["available"] == 1) {

            echo "Unavailable";

        } else {

            echo "Available";

        }

        echo "</button>";

        echo "</form>";

        echo " ";


        /*
        Remove button
        */

        echo "<form method='POST' style='display:inline;'>";

        echo "<input type='hidden' name='item_id'
            value='" . $item["item_id"] . "'>";

        echo "<button type='submit'
            name='move_to_trash'>";

        echo "Move to Trash";

        echo "</button>";

        echo "</form>";

        echo "</div>";

    }

    ?>

    </div>

</body>

</html>