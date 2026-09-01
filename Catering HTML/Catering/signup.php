<?php

require "db.php";

$error = "";

$name = "";
$email = "";

if (isset($_POST["signup"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];


    /*
    Check password length
    */

    if (strlen($password) < 6) {

        $error = "Password must be at least 6 characters long.";

    } else {


        /*
        Check if email already exists
        */

        $check_sql = "SELECT user_id
                      FROM users
                      WHERE email = '$email'";

        $check_result = mysqli_query($conn, $check_sql);


        if (mysqli_num_rows($check_result) > 0) {

            $error = "Email already exists.";

        } else {


            /*
            Hash password
            */

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);


            /*
            Create account
            */

            $sql = "INSERT INTO users (name, email, password)
                    VALUES ('$name', '$email', '$hashed_password')";


            if (mysqli_query($conn, $sql)) {

                header("Location: login.php");
                exit();

            } else {

                $error = "Error: " . mysqli_error($conn);

            }

        }

    }

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Sign Up</title>

</head>

<body>

    <h1>Catering Service</h1>

    <h2>Sign Up</h2>


    <?php

    if ($error != "") {

        echo "<p style='color:red;'>" . $error . "</p>";

    }

    ?>


    <form method="POST">

        <label>Name:</label>

        <input
            type="text"
            name="name"
            value="<?php echo htmlspecialchars($name); ?>"
            required
        >

        <br><br>


        <label>Email:</label>

        <input
            type="email"
            name="email"
            value="<?php echo htmlspecialchars($email); ?>"
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


        <button type="submit" name="signup">
            Sign Up
        </button>

    </form>


    <p>
        <a href="login.php">
            Login
        </a>

    </p>

</body>

</html>