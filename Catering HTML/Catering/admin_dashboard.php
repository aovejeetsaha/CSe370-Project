<?php

session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: admin_login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard</title>

    <style>

        .admin-header {
            border-bottom: 1px solid black;
            padding: 15px;
            margin-bottom: 25px;
        }

        .admin-header a {
            margin-right: 25px;
            text-decoration: none;
        }

        .dashboard-boxes {
            display: flex;
            gap: 20px;
        }

        .box {
            border: 1px solid black;
            padding: 20px;
            width: 200px;
        }

    </style>

</head>

<body>

    <h1>Catering Service</h1>

    <!-- Admin navigation -->

    <div class="admin-header">

        <a href="admin_dashboard.php">
            Dashboard
        </a>

        <a href="admin_menu.php">
            Manage Menu
        </a>

        <a href="admin_orders.php">
            Orders
        </a>

        <a href="admin_reviews.php">
            Reviews
        </a>

        <a href="admin_manage.php">
            Manage Admins
        </a>

        <a href="logout_admin.php">
            Logout
        </a>

    </div>


    <h2>Admin Dashboard</h2>

    <p>
        Welcome,
        <?php echo $_SESSION["admin_username"]; ?>!
    </p>


    <!-- Dashboard information -->

    <div class="dashboard-boxes">

        <div class="box">

            <h3>Revenue</h3>

            <p>Coming soon</p>

        </div>


        <div class="box">

            <h3>Total Orders</h3>

            <p>Coming soon</p>

        </div>


        <div class="box">

            <h3>Best Selling</h3>

            <p>Coming soon</p>

        </div>

    </div>

</body>

</html>