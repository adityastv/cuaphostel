<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_POST['student_id'];
    $room_id = $_POST['room_id'];
    
    $query = "INSERT INTO allocations (student_id, room_id, allocation_date) VALUES ($student_id, $room_id, NOW())";
    mysqli_query($conn, $query);
}

$students_query = "SELECT * FROM users WHERE role = 'student'";
$students_result = mysqli_query($conn, $students_query);

$rooms_query = "SELECT r.*, b.name as building_name FROM rooms r JOIN buildings b ON r.building_id = b.id";
$rooms_result = mysqli_query($conn, $rooms_query);

$allocations_query = "SELECT a.*, u.username, r.room_number, b.name as building_name 
                      FROM allocations a 
                      JOIN users u ON a.student_id = u.id 
                      JOIN rooms r ON a.room_id = r.id 
                      JOIN buildings b ON r.building_id = b.id";
$allocations_result = mysqli_query($conn, $allocations_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allocate Rooms</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Allocate Rooms</h1>
        <form method="POST" action="">
            <select name="student_id" required>
                <?php while ($student = mysqli_fetch_assoc($students_result)) : ?>
                    <option value="<?php echo $student['id']; ?>"><?php echo $student['username']; ?></option>
                <?php endwhile; ?>
            </select>
            <select name="room_id" required>
                <?php while ($room = mysqli_fetch_assoc($rooms_result)) : ?>
                    <option value="<?php echo $room['id']; ?>"><?php echo $room['building_name']; ?> - Room <?php echo $room['room_number']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Allocate Room</button>
        </form>
        
        <h2>Current Allocations</h2>
        <ul>
            <?php while ($allocation = mysqli_fetch_assoc($allocations_result)) : ?>
                <li><?php echo $allocation['username']; ?> - <?php echo $allocation['building_name']; ?> Room <?php echo $allocation['room_number']; ?></li>
            <?php endwhile; ?>
        </ul>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>