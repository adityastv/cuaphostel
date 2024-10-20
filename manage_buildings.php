<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $building_name = $_POST['building_name'];
    $floors = $_POST['floors'];
    
    $query = "INSERT INTO buildings (name, floors) VALUES ('$building_name', $floors)";
    mysqli_query($conn, $query);
}

$query = "SELECT * FROM buildings";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Buildings</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Buildings</h1>
        <form method="POST" action="">
            <input type="text" name="building_name" placeholder="Building Name" required>
            <input type="number" name="floors" placeholder="Number of Floors" required>
            <button type="submit">Add Building</button>
        </form>
        
        <h2>Existing Buildings</h2>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <li><?php echo $row['name']; ?> - <?php echo $row['floors']; ?> floors</li>
            <?php endwhile; ?>
        </ul>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>