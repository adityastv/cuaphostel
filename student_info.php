<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $emergency_contact = $_POST['emergency_contact'];
    
    $query = "UPDATE users SET full_name = '$full_name', email = '$email', phone = '$phone', address = '$address', emergency_contact = '$emergency_contact' WHERE id = $user_id";
    mysqli_query($conn, $query);
    $success = "Your information has been updated successfully.";
}

$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Student Information</h1>
        <form method="POST" action="">
            <input type="text" name="full_name" placeholder="Full Name" value="<?php echo $user['full_name']; ?>" required>
            <input type="email" name="email" placeholder="Email" value="<?php echo $user['email']; ?>" required>
            <input type="tel" name="phone" placeholder="Phone" value="<?php echo $user['phone']; ?>" required>
            <textarea name="address" placeholder="Address" required><?php echo $user['address']; ?></textarea>
            <input type="text" name="emergency_contact" placeholder="Emergency Contact" value="<?php echo $user['emergency_contact']; ?>" required>
            <button type="submit">Update Information</button>
        </form>
        
        <?php if (isset($success)) : ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>