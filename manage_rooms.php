<?php
session_start();
require_once 'config.php';
require_once 'functions.php';

if (!isLoggedIn() || !isAdmin()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $building_id = $_POST['building_id'];
    $room_number = $_POST['room_number'];
    $capacity = $_POST['capacity'];
    
    $query = "INSERT INTO rooms (building_id, room_number, capacity) VALUES ($building_id, '$room_number', $capacity)";
    mysqli_query($conn, $query);
}

$buildings_query = "SELECT * FROM buildings";
$buildings_result = mysqli_query($conn, $buildings_query);

$rooms_query = "SELECT r.*, b.name as building_name FROM rooms r JOIN buildings b ON r.building_id = b.id";
$rooms_result = mysqli_query($conn, $rooms_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Rooms</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Manage Rooms</h1>
        <form method="POST" action="">
            <select name="building_id" required>
                <?php while ($building = mysqli_fetch_assoc($buildings_result)) : ?>
                    <option value="<?php echo $building['id']; ?>"><?php echo $building['name']; ?></option>
                <?php endwhile; ?>
            </select>
            <input type="text" name="room_number" placeholder="Room Number" required>
            <input type="number" name="capacity" placeholder="Capacity" required>
            <button type="submit">Add Room</button>
        </form>
        
        <h2>Existing Rooms</h2>
        <ul>
            <?php while ($room = mysqli_fetch_assoc($rooms_result)) : ?>
                <li><?php echo $room['building_name']; ?> - Room <?php echo $room['room_number']; ?> (Capacity: <?php echo $room['capacity']; ?>)</li>
            <?php endwhile; ?>
        </ul>
        
        <a href="dashboard.php">Back to Dashboard</a>
    </div>
</body>
</html>