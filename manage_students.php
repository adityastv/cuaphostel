<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = 'student';
    
    $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    mysqli_query($conn, $query);
    $success = "New student added successfully.";
}

$students_query = "SELECT * FROM users WHERE role = 'student'";
$students_result = mysqli_query($conn, $students_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Students</h1>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Add Student</button>
        </form>
        
        <?php if (isset($success)) : ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <h2>Student List</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
            </tr>
            <?php while ($student = mysqli_fetch_assoc($students_result)) : ?>
                <tr>
                    <td><?php echo $student['id']; ?></td>
                    <td><?php echo $student['username']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>