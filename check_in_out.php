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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $student_id = $is_admin ? $_POST['student_id'] : $user_id;
    
    $query = "INSERT INTO check_in_out (student_id, action, timestamp) 
              VALUES ($student_id, '$action', NOW())";
    mysqli_query($conn, $query);
}

if ($is_admin) {
    $students_query = "SELECT * FROM users WHERE role = 'student'";
    $students_result = mysqli_query($conn, $students_query);
}

$check_in_out_query = "SELECT c.*, u.username 
                       FROM check_in_out c 
                       JOIN users u ON c.student_id = u.id 
                       " . ($is_admin ? "" : "WHERE c.student_id = $user_id ") . "
                       ORDER BY c.timestamp DESC";
$check_in_out_result = mysqli_query($conn, $check_in_out_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in/Check-out</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Check-in/Check-out</h1>
        <form method="POST" action="">
            <?php if ($is_admin) : ?>
                <select name="student_id" required>
                    <?php while ($student = mysqli_fetch_assoc($students_result)) : ?>
                        <option value="<?php echo $student['id']; ?>"><?php echo $student['username']; ?></option>
                    <?php endwhile; ?>
                </select>
            <?php endif; ?>
            <select name="action" required>
                <option value="check_in">Check-in</option>
                <option value="check_out">Check-out</option>
            </select>
            <button type="submit">Record Action</button>
        </form>
        
        <h2>Check-in/Check-out Records</h2>
        <table>
            <tr>
                <th>Student</th>
                <th>Action</th>
                <th>Timestamp</th>
            </tr>
            <?php while ($record = mysqli_fetch_assoc($check_in_out_result)) : ?>
                <tr>
                    <td><?php echo $record['username']; ?></td>
                    <td><?php echo ucfirst(str_replace('_', '-', $record['action'])); ?></td>
                    <td><?php echo $record['timestamp']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>