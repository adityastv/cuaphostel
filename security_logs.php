<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$is_admin = isAdmin();

$logs_query = "SELECT c.*, u.username 
               FROM check_in_out c 
               JOIN users u ON c.student_id = u.id 
               " . ($is_admin ? "" : "WHERE c.student_id = $user_id ") . "
               ORDER BY c.timestamp DESC 
               LIMIT 50";
$logs_result = mysqli_query($conn, $logs_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Logs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Security Logs</h1>
        <table>
            <tr>
                <th>Student</th>
                <th>Action</th>
                <th>Timestamp</th>
            </tr>
            <?php while ($log = mysqli_fetch_assoc($logs_result)) : ?>
                <tr>
                    <td><?php echo $log['username']; ?></td>
                    <td><?php echo ucfirst(str_replace('_', '-', $log['action'])); ?></td>
                    <td><?php echo $log['timestamp']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>