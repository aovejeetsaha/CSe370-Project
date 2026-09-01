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
Add new menu item
*/

if (isset($_POST["add_item"])) {

    $item_name = $_POST["item_name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    $sql = "INSERT INTO menu_items
            (item_name, description, price, available)
            VALUES
            ('$item_name', '$description', '$price', 1)";

    if (mysqli_query($conn, $sql)) {

        header("Location: admin_menu.php");
        exit();

    } else {

        $error = "Error: " . mysqli_error($conn);

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Menu Item</title>

</head>

<body>

    <h1>Catering Service</h1>

    <?php require "admin_header.php"; ?>

    <h2>Add New Menu Item</h2>


    <?php

    if (isset($error)) {

        echo "<p>" . $error . "</p>";

    }

    ?>


    <form method="POST">

        <label>Item Name:</label>

        <input
            type="text"
            name="item_name"
            required
        >

        <br><br>


        <label>Description:</label>

        <br>

        <textarea
            name="description"
            rows="4"
            cols="40"
            required
        ></textarea>

        <br><br>


        <label>Price:</label>

        <input
            type="number"
            name="price"
            step="0.01"
            min="0"
            required
        >

        Taka

        <br><br>


        <button type="submit" name="add_item">
            Add Item
        </button>

    </form>


    <br>

    <a href="admin_menu.php">
        Back to Manage Menu
    </a>

</body>

</html>