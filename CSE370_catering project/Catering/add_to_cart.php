<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$user_id = $_SESSION["user_id"];
$item_id = $_POST["item_id"];
$from = $_POST["from"] ?? "menu";
/*
Check if this item is already in the user's cart
*/

$sql = "SELECT * FROM cart
        WHERE user_id = '$user_id'
        AND item_id = '$item_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 1) {

    // Item already exists, increase quantity
    $sql = "UPDATE cart
            SET quantity = quantity + 1
            WHERE user_id = '$user_id'
            AND item_id = '$item_id'";

} else {

    // Item doesn't exist, add it
    $sql = "INSERT INTO cart (user_id, item_id, quantity)
            VALUES ('$user_id', '$item_id', 1)";
}

mysqli_query($conn, $sql);

// Go back to menu instead of cart
if ($from == "favorites") {

    header("Location: favorites.php");

} else {

    header("Location: menu.php");

}

exit();

?>