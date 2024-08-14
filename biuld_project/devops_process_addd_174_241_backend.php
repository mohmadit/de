<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_booking";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}


$room_number = $_POST['room_number'];
$booking_date = $_POST['booking_date'];

$sql = "SELECT id FROM rooms WHERE room_number = ? AND available = TRUE";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $room_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {

$room = $result->fetch_assoc();
$room_id = $room['id'];


$sql = "INSERT INTO bookings (room_id, booking_date) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $room_id, $booking_date);

if ($stmt->execute()) {

$sql = "UPDATE rooms SET available = FALSE WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();

echo "Booking successful!";
} else {
echo "Error: " . $stmt->error;
}
} else {
echo "Sorry, the room is not available or does not exist.";
}

$stmt->close();
$conn->close();
?>