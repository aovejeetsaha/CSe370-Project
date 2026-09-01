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
Get the item ID from the URL
*/

if (!isset($_GET["item_id"])) {
    header("Location: admin_menu.php");
    exit();
}

$item_id = $_GET["item_id"];


/*
Get the current item information
*/

$sql = "SELECT * FROM menu_items
        WHERE item_id = '$item_id'";

$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) != 1) {
    header("Location: admin_menu.php");
    exit();
}

$item = mysqli_fetch_assoc($result);


/*
Update the item
*/

if (isset($_POST["update_item"])) {

    $item_name = $_POST["item_name"];
    $description = $_POST["description"];
    $price = $_POST["price"];

    $sql = "UPDATE menu_items
            SET item_name = '$item_name',
                description = '$description',
                price = '$price'
            WHERE item_id = '$item_id'";

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

    <title>Edit Menu Item</title>

</head>

<body>

    <h1>Catering Service</h1>

    <?php require "admin_header.php"; ?>

    <h2>Edit Menu Item</h2>


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
            value="<?php echo htmlspecialchars($item["item_name"]); ?>"
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
        ><?php echo htmlspecialchars($item["description"]); ?></textarea>

        <br><br>


        <label>Price:</label>

        <input
            type="number"
            name="price"
            step="0.01"
            min="0"
            value="<?php echo $item["price"]; ?>"
            required
        >

        Taka

        <br><br>


        <button type="submit" name="update_item">
            Save Changes
        </button>

    </form>


    <br>

    <a href="admin_menu.php">
        Cancel
    </a>

</body>

</html>