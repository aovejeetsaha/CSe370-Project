<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$cart_id = $_POST["cart_id"];

$sql = "DELETE FROM cart
        WHERE cart_id = '$cart_id'";

mysqli_query($conn, $sql);

header("Location: cart.php");
exit();

?>