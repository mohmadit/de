<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "khotel";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Improved validation check
if (
    !isset($_POST['name']) || 
    !isset($_POST['email']) || 
    !isset($_POST['check_in']) || 
    !isset($_POST['check_out']) || 
    !isset($_POST['persons']) || 
    !isset($_POST['rooms']) || 
    !isset($_POST['room_type'])
) {
    die("Please fill in all required fields.");
}

$name = $_POST['name'];
$email = $_POST['email'];
$checkIn = $_POST['check_in'];
$checkOut = $_POST['check_out'];
$persons = $_POST['persons'];
$rooms = $_POST['rooms'];
$roomType = $_POST['room_type'];

// Check if 'room_type' column exists in the 'rooms' table
$query = "SELECT id FROM rooms WHERE room_type = ? AND available = 1 LIMIT ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $roomType, $rooms);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows >= $rooms) {
    $bookQuery = "INSERT INTO bookings (name, email, check_in, check_out, room_id, persons, rooms) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $bookStmt = $conn->prepare($bookQuery);

    $updateQuery = "UPDATE rooms SET available = 0 WHERE id = ?";
    $updateStmt = $conn->prepare($updateQuery);

    $stmt->bind_result($roomId);
    for ($i = 0; $i < $rooms; $i++) {
        if ($stmt->fetch()) {
            $bookStmt->bind_param('ssssiii', $name, $email, $checkIn, $checkOut, $roomId, $persons, $rooms);
            $bookStmt->execute();

            $updateStmt->bind_param('i', $roomId);
            $updateStmt->execute();
        }
    }

    echo "Reservation successful!";
} else {
    echo "Sorry, not enough rooms available for your selection.";
}

// Close statements and connection only if they are set
if (isset($bookStmt)) {
    $bookStmt->close();
}
if (isset($updateStmt)) {
    $updateStmt->close();
}
$stmt->close();
$conn->close();
?>
