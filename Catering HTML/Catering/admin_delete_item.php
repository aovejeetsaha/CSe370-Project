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
Check that an item ID was provided
*/

if (!isset($_GET["item_id"])) {
    header("Location: admin_menu.php");
    exit();
}

$item_id = $_GET["item_id"];


/*
Check whether this item has ever been ordered
*/

$sql = "SELECT COUNT(*) AS total
        FROM order_items
        WHERE item_id = '$item_id'";

$result = mysqli_query($conn, $sql);

$row = mysqli_fetch_assoc($result);

$total_orders = $row["total"];


/*
If the item has been ordered before,
keep it but make it unavailable.
*/

if ($total_orders > 0) {

    $sql = "UPDATE menu_items
            SET available = 0
            WHERE item_id = '$item_id'";

    mysqli_query($conn, $sql);

}


/*
If the item has never been ordered,
we can remove it completely.
*/

else {

    /*
    Remove from favorites first
    */

    $sql = "DELETE FROM favorites
            WHERE item_id = '$item_id'";

    mysqli_query($conn, $sql);


    /*
    Remove from cart
    */

    $sql = "DELETE FROM cart
            WHERE item_id = '$item_id'";

    mysqli_query($conn, $sql);


    /*
    Finally remove the menu item
    */

    $sql = "DELETE FROM menu_items
            WHERE item_id = '$item_id'";

    mysqli_query($conn, $sql);

}


/*
Return to admin menu
*/

header("Location: admin_menu.php");
exit();

?>