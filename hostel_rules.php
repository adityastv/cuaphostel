<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$query = "SELECT * FROM hostel_rules";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Rules</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Hostel Rules and Regulations</h1>
        <ul>
            <?php while ($rule = mysqli_fetch_assoc($result)) : ?>
                <li><?php echo $rule['rule']; ?></li>
            <?php endwhile; ?>
        </ul>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>