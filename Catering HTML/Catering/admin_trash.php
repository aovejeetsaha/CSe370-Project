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
Restore an item
*/

if (isset($_POST["restore_item"])) {

    $item_id = $_POST["item_id"];

    $sql = "UPDATE menu_items
            SET deleted = 0,
                permanently_deleted = 0,
                available = 0
            WHERE item_id = '$item_id'";

    mysqli_query($conn, $sql);

    header("Location: admin_trash.php");
    exit();
}


/*
Permanently remove an item
*/

if (isset($_POST["delete_permanently"])) {

    $item_id = $_POST["item_id"];

    $sql = "UPDATE menu_items
            SET deleted = 1,
                permanently_deleted = 1,
                available = 0
            WHERE item_id = '$item_id'";

    mysqli_query($conn, $sql);

    header("Location: admin_trash.php");
    exit();
}


/*
Get items that are in Trash
but have NOT been permanently removed
*/

$sql = "SELECT *
        FROM menu_items
        WHERE deleted = 1
        AND permanently_deleted = 0
        ORDER BY item_id";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Trash</title>

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

    <h2>Trash</h2>


    <?php

    if (mysqli_num_rows($result) == 0) {

        echo "<p>Trash is empty.</p>";

    } else {

    ?>

    <div class="menu-grid">

    <?php

        while ($item = mysqli_fetch_assoc($result)) {

            echo "<div class='menu-item'>";

            echo "<h3>" . $item["item_name"] . "</h3>";

            echo "<p>" . $item["description"] . "</p>";

            echo "<p>Price: " . $item["price"] . " Taka</p>";

            echo "<p><strong>In Trash</strong></p>";


            /*
            Restore button
            */

            echo "<form method='POST' style='display:inline;'>";

            echo "<input type='hidden'
                   name='item_id'
                   value='" . $item["item_id"] . "'>";

            echo "<button type='submit'
                   name='restore_item'>";

            echo "Restore";

            echo "</button>";

            echo "</form>";

            echo " ";


            /*
            Delete Permanently button
            */

            echo "<form method='POST' style='display:inline;'>";

            echo "<input type='hidden'
                   name='item_id'
                   value='" . $item["item_id"] . "'>";

            echo "<button type='submit'
                   name='delete_permanently'>";

            echo "Delete Permanently";

            echo "</button>";

            echo "</form>";


            echo "</div>";

        }

    ?>

    </div>

    <?php

    }

    ?>

</body>

</html>