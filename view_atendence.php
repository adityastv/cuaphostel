<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() || isAdmin()) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$attendance_query = "SELECT * FROM attendance WHERE student_id = $user_id ORDER BY date DESC";
$attendance_result = mysqli_query($conn, $attendance_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Your Attendance Record</h1>
        <table>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php while ($attendance = mysqli_fetch_assoc($attendance_result)) : ?>
                <tr>
                    <td><?php echo $attendance['date']; ?></td>
                    <td><?php echo ucfirst($attendance['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>