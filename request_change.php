<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() || isAdmin()) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reason = $_POST['reason'];
    
    $query = "INSERT INTO room_change_requests (student_id, reason, request_date, status) 
              VALUES ($user_id, '$reason', NOW(), 'pending')";
    mysqli_query($conn, $query);
    $success = "Your room change request has been submitted.";
}

$requests_query = "SELECT * FROM room_change_requests WHERE student_id = $user_id ORDER BY request_date DESC";
$requests_result = mysqli_query($conn, $requests_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request Room Change</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Request Room Change</h1>
        <form method="POST" action="">
            <textarea name="reason" placeholder="Reason for room change request" required></textarea>
            <button type="submit">Submit Request</button>
        </form>
        
        <?php if (isset($success)) : ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <h2>Your Room Change Requests</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
            <?php while ($request = mysqli_fetch_assoc($requests_result)) : ?>
                <tr>
                    <td><?php echo $request['request_date']; ?></td>
                    <td><?php echo $request['reason']; ?></td>
                    <td><?php echo ucfirst($request['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>