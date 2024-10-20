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
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    
    $query = "INSERT INTO payments (student_id, amount, payment_date, description) 
              VALUES ($user_id, $amount, NOW(), '$description')";
    mysqli_query($conn, $query);
    $success = "Your payment has been recorded.";
}

$payments_query = "SELECT * FROM payments WHERE student_id = $user_id ORDER BY payment_date DESC";
$payments_result = mysqli_query($conn, $payments_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Make Payment</h1>
        <form method="POST" action="">
            <input type="number" name="amount" step="0.01" placeholder="Amount" required>
            <input type="text" name="description" placeholder="Payment Description" required>
            <button type="submit">Submit Payment</button>
        </form>
        
        <?php if (isset($success)) : ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <h2>Your Payment History</h2>
        <table>
            <tr>
                <th>Date</th>
                <th>Amount</th>
                <th>Description</th>
            </tr>
            <?php while ($payment = mysqli_fetch_assoc($payments_result)) : ?>
                <tr>
                    <td><?php echo $payment['payment_date']; ?></td>
                    <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                    <td><?php echo $payment['description']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>