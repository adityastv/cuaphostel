<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Fetch notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = $user_id OR user_id IS NULL ORDER BY created_at DESC LIMIT 5";
$notifications_result = mysqli_query($conn, $notifications_query);

// Fetch room details for students
if (!isAdmin()) {
    $room_query = "SELECT r.*, b.name as building_name
                   FROM allocations a
                   JOIN rooms r ON a.room_id = r.id
                   JOIN buildings b ON r.building_id = b.id
                   WHERE a.student_id = $user_id";
    $room_result = mysqli_query($conn, $room_query);
    $room = mysqli_fetch_assoc($room_result);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hostel Management System - Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $username; ?></h1>
        <p>Role: <?php echo $role; ?></p>
        
        <h2>Notifications</h2>
        <ul>
            <?php while ($notification = mysqli_fetch_assoc($notifications_result)) : ?>
                <li><?php echo $notification['title']; ?> - <?php echo $notification['created_at']; ?></li>
            <?php endwhile; ?>
        </ul>
        <a href="notifications.php">View All Notifications</a>
        
        <?php if (isAdmin()) : ?>
            <h2>Admin Functions</h2>
            <ul>
                <li><a href="manage_buildings.php">Manage Buildings</a></li>
                <li><a href="manage_rooms.php">Manage Rooms</a></li>
                <li><a href="allocate_rooms.php">Allocate Rooms</a></li>
                <li><a href="manage_students.php">Manage Students</a></li>
                <li><a href="attendance.php">Attendance Tracking</a></li>
                <li><a href="payments.php">Payment Management</a></li>
                <li><a href="check_in_out.php">Check-in/Check-out</a></li>
                <li><a href="manage_leave_requests.php">Manage Leave Requests</a></li>
                <li><a href="security_logs.php">Security Logs</a></li>
            </ul>
        <?php else : ?>
            <h2>Student Functions</h2>
            <?php if ($room) : ?>
                <h3>Your Room Details</h3>
                <p>Building: <?php echo $room['building_name']; ?>, Room: <?php echo $room['room_number']; ?></p>
            <?php else : ?>
                <p>You have not been allocated a room yet.</p>
            <?php endif; ?>
            <ul>
                <li><a href="view_room.php">View Room Details</a></li>
                <li><a href="request_change.php">Request Room Change</a></li>
                <li><a href="view_attendance.php">View Attendance</a></li>
                <li><a href="make_payment.php">Make Payment</a></li>
                <li><a href="check_in_out.php">Check-in/Check-out</a></li>
                <li><a href="leave_request.php">Submit Leave Request</a></li>
                <li><a href="student_info.php">Update Personal Information</a></li>
                <li><a href="security_logs.php">View Security Logs</a></li>
                <li><a href="hostel_rules.php">View Hostel Rules</a></li>
            </ul>
        <?php endif; ?>
        
        <a href="logout.php">Logout</a>
    </div>
</body>
</html>