<?php

session_start();

require "db.php";

if (!isset($_SESSION["user_id"])) {
    die("Please login first.");
}

$user_id = $_SESSION["user_id"];


/*
Today's spending
*/

$sql_today = "SELECT COALESCE(SUM(total_amount), 0) AS today_total
              FROM orders
              WHERE user_id = '$user_id'
              AND DATE(order_date) = CURDATE()";

$result_today = mysqli_query($conn, $sql_today);

$row_today = mysqli_fetch_assoc($result_today);

$today_total = $row_today["today_total"];


/*
This month's spending
*/

$sql_month = "SELECT COALESCE(SUM(total_amount), 0) AS month_total
              FROM orders
              WHERE user_id = '$user_id'
              AND MONTH(order_date) = MONTH(CURDATE())
              AND YEAR(order_date) = YEAR(CURDATE())";

$result_month = mysqli_query($conn, $sql_month);

$row_month = mysqli_fetch_assoc($result_month);

$month_total = $row_month["month_total"];


/*
This year's spending
*/

$sql_year = "SELECT COALESCE(SUM(total_amount), 0) AS year_total
             FROM orders
             WHERE user_id = '$user_id'
             AND YEAR(order_date) = YEAR(CURDATE())";

$result_year = mysqli_query($conn, $sql_year);

$row_year = mysqli_fetch_assoc($result_year);

$year_total = $row_year["year_total"];


/*
Get daily spending for this year
*/

$sql_daily = "SELECT DATE(order_date) AS spending_date,
                     SUM(total_amount) AS daily_total
              FROM orders
              WHERE user_id = '$user_id'
              AND YEAR(order_date) = YEAR(CURDATE())
              GROUP BY DATE(order_date)
              ORDER BY spending_date";

$result_daily = mysqli_query($conn, $sql_daily);

$daily_data = [];

while ($row = mysqli_fetch_assoc($result_daily)) {
    $daily_data[] = $row;
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Spending Tracker</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

    <?php require "header.php"; ?>

    <h1>Spending Tracker</h1>

    <h2>Today</h2>

    <p>
        <?php echo $today_total; ?> Taka
    </p>


    <h2>This Month</h2>

    <p>
        <?php echo $month_total; ?> Taka
    </p>


    <h2>This Year</h2>

    <p>
        <?php echo $year_total; ?> Taka
    </p>
    <h2>Daily Spending</h2>

    <div style="width: 700px;">
        <canvas id="spendingChart"></canvas>
    </div>
    <script>

    const dates = <?php

    $dates = [];

    foreach ($daily_data as $day) {
        $dates[] = $day["spending_date"];
    }

    echo json_encode($dates);

    ?>;


    const spending = <?php

    $amounts = [];

    foreach ($daily_data as $day) {
        $amounts[] = $day["daily_total"];
    }

    echo json_encode($amounts);

    ?>;


    const ctx = document.getElementById('spendingChart');

    new Chart(ctx, {

        type: 'line',

        data: {

            labels: dates,

            datasets: [{

                label: 'Daily Spending',

                data: spending,

                tension: 0.3

            }]

        },

        options: {

            responsive: true,

            scales: {

                y: {

                    beginAtZero: true,

                    title: {
                        display: true,
                        text: 'Taka'
                    }

                },

                x: {

                    title: {
                        display: true,
                        text: 'Date'
                    }

                }

            }

        }

    });

</script>
</body>

</html>