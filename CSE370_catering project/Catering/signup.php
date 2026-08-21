<?php

require "db.php";

if (isset($_POST["signup"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$hashed_password')";

    if (mysqli_query($conn, $sql)) {
        echo "Account created successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

?><!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>

<body>

    <h1>Catering Service</h1>
    <h2>Sign Up</h2>

    <form method="POST">

        <label>Name:</label>
        <input type="text" name="name" required>

        <br><br>

        <label>Email:</label>
        <input type="email" name="email" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit" name="signup">Sign Up</button>

    </form>

    <p>
        Already have an account?
        <a href="login.php">Login</a>
    </p>

</body>
</html>