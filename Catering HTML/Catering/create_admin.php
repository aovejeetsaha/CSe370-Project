<?php

require "db.php";

$username = "admin";
$password = "admin";

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO admins (username, password)
        VALUES ('$username', '$hashed_password')";

if (mysqli_query($conn, $sql)) {
    echo "Admin created successfully!";
} else {
    echo "Error: " . mysqli_error($conn);
}

?>