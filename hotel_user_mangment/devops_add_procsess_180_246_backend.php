<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_booking";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



$name = $_POST['name'];
$email = $_POST['email'];
$checkIn = $_POST['check_in'];
$checkOut = $_POST['check_out'];
$persons = $_POST['persons'];
$rooms = $_POST['rooms'];
$roomType = $_POST['room_type'];

$query = "SELECT id FROM rooms WHERE room_type = ? AND available = 1 LIMIT ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $roomType, $rooms);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows >= $rooms) {
    $stmt->bind_result($roomId);
    while ($stmt->fetch()) {
        $bookQuery = "INSERT INTO bookings (name, email, check_in, check_out, room_id, persons, rooms) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $bookStmt = $conn->prepare($bookQuery);
        $bookStmt->bind_param('ssssiii', $name, $email, $checkIn, $checkOut, $roomId, $persons, $rooms);
        $bookStmt->execute();

        $updateQuery = "UPDATE rooms SET available = 0 WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $roomId);
        $updateStmt->execute();
    }
    echo "Reservation successful!";
} else {
    echo "Sorry, not enough rooms available for your selection.";
}

$stmt->close();
$conn->close();
?>
