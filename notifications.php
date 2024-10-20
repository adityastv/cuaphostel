<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$notifications_query = "SELECT * FROM notifications WHERE user_id = $user_id OR user_id IS NULL ORDER BY created_at DESC LIMIT 20";
$notifications_result = mysqli_query($conn, $notifications_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Notifications</h1>
        <ul>
            <?php while ($notification = mysqli_fetch_assoc($notifications_result)) : ?>
                <li>
                    <strong><?php echo $notification['title']; ?></strong>
                    <p><?php echo $notification['message']; ?></p>
                    <small><?php echo $notification['created_at']; ?></small>
                </li>
            <?php endwhile; ?>
        </ul>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>