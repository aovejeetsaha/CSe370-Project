<?php

session_start();

require "db.php";

if (isset($_POST["login"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admins
            WHERE username = '$username'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin["password"])) {

            $_SESSION["admin_id"] = $admin["admin_id"];
            $_SESSION["admin_username"] = $admin["username"];

            header("Location: admin_dashboard.php");
            exit();

        } else {

            $error = "Invalid username or password.";

        }

    } else {

        $error = "Invalid username or password.";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Login</title>

</head>

<body>

    <h1>Catering Service</h1>

    <h2>Admin Login</h2>

    <?php

    if (isset($error)) {
        echo "<p>" . $error . "</p>";
    }

    ?>

    <form method="POST">

        <label>Username:</label>

        <input type="text" name="username" required>

        <br><br>

        <label>Password:</label>

        <input type="password" name="password" required>

        <br><br>

        <button type="submit" name="login">
            Admin Login
        </button>

    </form>

    <br>

    <a href="login.php">Customer Login</a>

</body>

</html>