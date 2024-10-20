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
    $date = $_POST['date'];
    $status = $_POST['status'];
    
    $query = "INSERT INTO attendance (student_id, date, status) VALUES ($student_id, '$date', '$status')
              ON DUPLICATE KEY UPDATE status = '$status'";
    mysqli_query($conn, $query);
}

$students_query = "SELECT * FROM users WHERE role = 'student'";
$students_result = mysqli_query($conn, $students_query);

$attendance_query = "SELECT a.*, u.username 
                     FROM attendance a 
                     JOIN users u ON a.student_id = u.id 
                     ORDER BY a.date DESC, u.username";
$attendance_result = mysqli_query($conn, $attendance_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Tracking</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Attendance Tracking</h1>
        <form method="POST" action="">
            <select name="student_id" required>
                <?php while ($student = mysqli_fetch_assoc($students_result)) : ?>
                    <option value="<?php echo $student['id']; ?>"><?php echo $student['username']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="date" name="date" required>
            <select name="status" required>
                <option value="present">Present</option>
                <option value="absent">Absent</option>
            </select>
            <button type="submit">Mark Attendance</button>
        </form>
        
        <h2>Attendance Records</h2>
        <table>
            <tr>
                <th>Student</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
            <?php while ($attendance = mysqli_fetch_assoc($attendance_result)) : ?>
                <tr>
                    <td><?php echo $attendance['username']; ?></td>
                    <td><?php echo $attendance['date']; ?></td>
                    <td><?php echo ucfirst($attendance['status']); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>