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
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];
    
    $query = "INSERT INTO leave_requests (student_id, start_date, end_date, reason, status) 
              VALUES ($user_id, '$start_date', '$end_date', '$reason', 'pending')";
    mysqli_query($conn, $query);
    $success = "Your leave request has been submitted.";
}

$requests_query = "SELECT * FROM leave_requests WHERE student_id = $user_id ORDER BY start_date DESC";
$requests_result = mysqli_query($conn, $requests_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Submit Leave Request</h1>
        <form method="POST" action="">
            <input type="date" name="start_date" required>
            <input type="date" name="end_date" required>
            <textarea name="reason" placeholder="Reason for leave" required></textarea>
            <button type="submit">Submit Request</button>
        </form>
        
        <?php if (isset($success)) : ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <h2>Your Leave Requests</h2>
        <table>
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
            <?php while ($request = mysqli_fetch_assoc($requests_result)) : ?>
                <tr>
                    <td><?php echo $request['start_date']; ?></td>
                    <td><?php echo $request['end_date']; ?></td>
                    <td><?php echo $request['reason']; ?></td>
                    <td><?php echo ucfirst($request['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>