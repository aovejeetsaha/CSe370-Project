<?php

session_start();

require "db.php";

$message = "";

if (isset($_POST["login"])) {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["user_id"];

        // The role is used only to protect the coupon administration page.
        $_SESSION["role"] = $user["role"] ?? "customer";
            $_SESSION["user_name"] = $user["name"];

            header("Location: menu.php");
            exit();

        } else {

            $message = "Wrong password.";

        }

    } else {

        $message = "Email not found.";

    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>

    <h1>Catering Service</h1>

    <h2>Login</h2>

    <form method="POST">

        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit" name="login">Login</button>

    </form>

    <p><?php echo $message; ?></p>

    <p>
        Don't have an account?
        <a href="signup.php">Sign Up</a>
    </p>

</body>

</html>
