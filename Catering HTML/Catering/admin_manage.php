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
Create a new admin
*/

if (isset($_POST["create_admin"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    /*
    Check if username already exists
    */

    $check_sql = "SELECT * FROM admins
                  WHERE username = '$username'";

    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {

        $message = "This username already exists.";

    } else {

        /*
        Hash the password
        */

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        /*
        Insert new admin
        */

        $sql = "INSERT INTO admins (username, password)
                VALUES ('$username', '$hashed_password')";

        if (mysqli_query($conn, $sql)) {

            $message = "New admin created successfully!";

        } else {

            $message = "Error: " . mysqli_error($conn);

        }
    }
}


/*
Get all admins
*/

$sql = "SELECT admin_id, username
        FROM admins
        ORDER BY admin_id";

$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Admins</title>

</head>

<body>

    <h1>Catering Service</h1>


    <?php require "admin_header.php"; ?>

    <h2>Manage Admins</h2>

    <hr>


    

    <?php

    if (isset($message)) {

        echo "<p><strong>" . $message . "</strong></p>";

    }

    ?>


    <h3>Create New Admin</h3>


    <form method="POST">

        <label>Username:</label>

        <input
            type="text"
            name="username"
            required
        >

        <br><br>


        <label>Password:</label>

        <input
            type="password"
            name="password"
            minlength="6"
            required
        >

        <br><br>


        <button type="submit" name="create_admin">
            Create Admin
        </button>

    </form>


    <hr>


    <h3>Existing Administrators</h3>


    <table border="1" cellpadding="10">

        <tr>

            <th>Admin ID</th>

            <th>Username</th>

        </tr>


        <?php

        while ($admin = mysqli_fetch_assoc($result)) {

            echo "<tr>";

            echo "<td>" . $admin["admin_id"] . "</td>";

            echo "<td>" . $admin["username"] . "</td>";

            echo "</tr>";

        }

        ?>

    </table>


</body>

</html>