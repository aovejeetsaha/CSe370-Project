<?php

session_start();

require "db.php";
require "feature_helpers.php";

$customer_id = require_logged_in_user();

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $event_id = filter_input(
        INPUT_POST,
        "event_id",
        FILTER_VALIDATE_INT
    );

    $rating = filter_input(
        INPUT_POST,
        "rating",
        FILTER_VALIDATE_INT
    );

    $comment = trim($_POST["comment"] ?? "");

    if (!$event_id) {
        $error_message = "Please select an event.";
    } elseif (!$rating || $rating < 1 || $rating > 5) {
        $error_message = "Please select a rating from 1 to 5.";
    } elseif (mb_strlen($comment) > 500) {
        $error_message = "The comment must be 500 characters or fewer.";
    } else {
        /*
        INSERT ... SELECT saves the review only when the event exists.
        Customer_id comes from the login session, not from the HTML form.
        */

        $insert_sql = "
            INSERT INTO review (
                Customer_id,
                event_id,
                rating,
                comment
            )
            SELECT
                ?,
                event.event_id,
                ?,
                ?
            FROM event
            WHERE event.event_id = ?
        ";

        $insert_statement = mysqli_prepare($conn, $insert_sql);
        mysqli_stmt_bind_param(
            $insert_statement,
            "iisi",
            $customer_id,
            $rating,
            $comment,
            $event_id
        );

        try {
            mysqli_stmt_execute($insert_statement);

            if (mysqli_stmt_affected_rows($insert_statement) === 0) {
                $error_message = "The selected event does not exist.";
            } else {
                $success_message = "Your review was saved successfully.";
            }
        } catch (mysqli_sql_exception $database_error) {
            if ($database_error->getCode() === 1062) {
                $error_message = "You have already reviewed this event.";
            } else {
                $error_message = "The review could not be saved.";
            }
        } finally {
            mysqli_stmt_close($insert_statement);
        }
    }
}

$events_sql = "
    SELECT
        event_id,
        event_type,
        event_date,
        location
    FROM event
    ORDER BY event_date DESC
";

$events_result = mysqli_query($conn, $events_sql);

$reviews_sql = "
    SELECT
        review.rating,
        review.comment,
        review.review_date,
        users.name,
        event.event_type,
        event.event_date
    FROM review
    JOIN users
        ON users.user_id = review.Customer_id
    JOIN event
        ON event.event_id = review.event_id
    ORDER BY review.review_date DESC,
             review.review_id DESC
";

$reviews_result = mysqli_query($conn, $reviews_sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Event Reviews</title>
    <link rel="stylesheet" href="feature_style.css">
</head>
<body>

    <?php require "header.php"; ?>

    <main class="feature-container">
        <h1>Event Reviews</h1>

        <?php if ($success_message !== "") { ?>
            <p class="feature-success">
                <?php echo escape_output($success_message); ?>
            </p>
        <?php } ?>

        <?php if ($error_message !== "") { ?>
            <p class="feature-error">
                <?php echo escape_output($error_message); ?>
            </p>
        <?php } ?>

        <section class="feature-card">
            <h2>Write a Review</h2>

            <?php if (mysqli_num_rows($events_result) === 0) { ?>

                <p>No events are available yet.</p>

            <?php } else { ?>

                <form method="POST" class="feature-form">
                    <label>
                        Event
                        <select name="event_id" required>
                            <option value="">Choose an event</option>

                            <?php while ($event = mysqli_fetch_assoc($events_result)) { ?>
                                <option value="<?php echo (int) $event["event_id"]; ?>">
                                    <?php
                                    echo escape_output(
                                        $event["event_type"]
                                        . " - "
                                        . $event["event_date"]
                                        . " - "
                                        . $event["location"]
                                    );
                                    ?>
                                </option>
                            <?php } ?>
                        </select>
                    </label>

                    <label>
                        Rating
                        <select name="rating" required>
                            <option value="">Choose 1 to 5</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </label>

                    <label>
                        Comment
                        <textarea name="comment" maxlength="500" rows="4"></textarea>
                    </label>

                    <button type="submit" class="feature-button">
                        Submit Review
                    </button>
                </form>

            <?php } ?>
        </section>

        <section>
            <h2>Recent Reviews</h2>

            <?php while ($review = mysqli_fetch_assoc($reviews_result)) { ?>
                <article class="feature-card">
                    <h3>
                        <?php echo escape_output($review["event_type"]); ?>
                        - Rating <?php echo (int) $review["rating"]; ?>/5
                    </h3>

                    <p>
                        <?php echo nl2br(escape_output($review["comment"])); ?>
                    </p>

                    <small>
                        <?php echo escape_output($review["name"]); ?>,
                        <?php echo escape_output($review["review_date"]); ?>
                    </small>
                </article>
            <?php } ?>
        </section>
    </main>
</body>
</html>
