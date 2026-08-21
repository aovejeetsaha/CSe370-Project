<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$user_id = $_SESSION["user_id"];

$cart_id = $_POST["cart_id"];
$action = $_POST["action"];


if ($action == "increase") {

    $sql = "UPDATE cart
            SET quantity = quantity + 1
            WHERE cart_id = '$cart_id'
            AND user_id = '$user_id'";

}


elseif ($action == "decrease") {

    $sql = "UPDATE cart
            SET quantity = quantity - 1
            WHERE cart_id = '$cart_id'
            AND user_id = '$user_id'
            AND quantity > 1";

}


mysqli_query($conn, $sql);


/*
Go back to the page that sent the request
*/

if (isset($_POST["from"]) && $_POST["from"] == "menu") {

    header("Location: menu.php");

} elseif (isset($_POST["from"]) && $_POST["from"] == "favorites") {

    header("Location: favorites.php");

} else {

    header("Location: cart.php");

}

exit();

?>