<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$user_id = $_SESSION["user_id"];
$item_id = $_POST["item_id"];


/*
Check if this item is already a favorite
*/

$sql = "SELECT * FROM favorites
        WHERE user_id = '$user_id'
        AND item_id = '$item_id'";

$result = mysqli_query($conn, $sql);


if (mysqli_num_rows($result) == 1) {

    /*
    Already favorite → remove it
    */

    $sql = "DELETE FROM favorites
            WHERE user_id = '$user_id'
            AND item_id = '$item_id'";

} else {

    /*
    Not favorite → add it
    */

    $sql = "INSERT INTO favorites (user_id, item_id)
            VALUES ('$user_id', '$item_id')";
}

mysqli_query($conn, $sql);


/*
Return to menu
*/

header("Location: menu.php");
exit();

?>