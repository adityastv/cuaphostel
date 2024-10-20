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
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $description = $_POST['description'];
    
    $query = "INSERT INTO payments (student_id, amount, payment_date, description) 
              VALUES ($student_id, $amount, '$payment_date', '$description')";
    mysqli_query($conn, $query);
}

$students_query = "SELECT * FROM users WHERE role = 'student'";
$students_result = mysqli_query($conn, $students_query);

$payments_query = "SELECT p.*, u.username 
                   FROM payments p 
                   JOIN users u ON p.student_id = u.id 
                   ORDER BY p.payment_date DESC";
$payments_result = mysqli_query($conn, $payments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Payment Management</h1>
        <form method="POST" action="">
            <select name="student_id" required>
                <?php while ($student = mysqli_fetch_assoc($students_result)) : ?>
                    <option value="<?php echo $student['id']; ?>"><?php echo $student['username']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="number" name="amount" step="0.01" placeholder="Amount" required>
            <input type="date" name="payment_date" required>
            <input type="text" name="description" placeholder="Payment Description" required>
            <button type="submit">Record Payment</button>
        </form>
        
        <h2>Payment Records</h2>
        <table>
            <tr>
                <th>Student</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Description</th>
            </tr>
            <?php while ($payment = mysqli_fetch_assoc($payments_result)) : ?>
                <tr>
                    <td><?php echo $payment['username']; ?></td>
                    <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                    <td><?php echo $payment['payment_date']; ?></td>
                    <td><?php echo $payment['description']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>