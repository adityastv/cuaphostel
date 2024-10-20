<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_id = $_POST['request_id'];
    $status = $_POST['status'];
    
    $query = "UPDATE leave_requests SET status = '$status' WHERE id = $request_id";
    mysqli_query($conn, $query);
    $success = "Leave request status updated successfully.";
}

$requests_query = "SELECT lr.*, u.username 
                   FROM leave_requests lr 
                   JOIN users u ON lr.student_id = u.id 
                   WHERE lr.status = 'pending' 
                   ORDER BY lr.start_date ASC";
$requests_result = mysqli_query($conn, $requests_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Requests</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Leave Requests</h1>
        
        <?php if (isset($success)) : ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <table>
            <tr>
                <th>Student</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Action</th>
            </tr>
            <?php while ($request = mysqli_fetch_assoc($requests_result)) : ?>
                <tr>
                    <td><?php echo $request['username']; ?></td>
                    <td><?php echo $request['start_date']; ?></td>
                    <td><?php echo $request['end_date']; ?></td>
                    <td><?php echo $request['reason']; ?></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                            <select name="status" required>
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                            </select>
                            <button type="submit">Update</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>