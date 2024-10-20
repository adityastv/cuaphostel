<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() || isAdmin()) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT r.*, b.name as building_name, a.allocation_date
          FROM allocations a
          JOIN rooms r ON a.room_id = r.id
          JOIN buildings b ON r.building_id = b.id
          WHERE a.student_id = $user_id";
$result = mysqli_query($conn, $query);
$room = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Room Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Your Room Details</h1>
        <?php if ($room) : ?>
            <p>Building: <?php echo $room['building_name']; ?></p>
            <p>Room Number: <?php echo $room['room_number']; ?></p>
            <p>Capacity: <?php echo $room['capacity']; ?></p>
            <p>Allocated Since: <?php echo $room['allocation_date']; ?></p>
        <?php else : ?>
            <p>You have not been allocated a room yet.</p>
        <?php endif; ?>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>