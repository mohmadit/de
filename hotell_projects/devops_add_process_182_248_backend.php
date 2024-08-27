<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "khotel";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Get the form data
$name = $_POST['name'];
$email = $_POST['email'];
$check_in = $_POST['check_in'];
$check_out = $_POST['check_out'];
$persons = $_POST['persons'];
$rooms = $_POST['rooms'];
$room_type = $_POST['room_type'];
$room_number = rand(1, 100); // Randomly assign a room number

// Check if the room is already booked
$query = $conn->prepare("SELECT * FROM reservations WHERE room_number = :room_number");
$query->bindParam(':room_number', $room_number);
$query->execute();

if ($query->rowCount() > 0) {
    echo "Room number $room_number is already booked!";
} else {
    // Insert the booking data into the database
    $insert = $conn->prepare("
        INSERT INTO reservations (name, email, check_in, check_out, persons, rooms, room_type, room_number)
        VALUES (:name, :email, :check_in, :check_out, :persons, :rooms, :room_type, :room_number)
    ");

    $insert->bindParam(':name', $name);
    $insert->bindParam(':email', $email);
    $insert->bindParam(':check_in', $check_in);
    $insert->bindParam(':check_out', $check_out);
    $insert->bindParam(':persons', $persons);
    $insert->bindParam(':rooms', $rooms);
    $insert->bindParam(':room_type', $room_type);
    $insert->bindParam(':room_number', $room_number);

    if ($insert->execute()) {
        echo "Booking successful! Your room number is: $room_number";
    } else {
        echo "An error occurred during the booking process. Please try again.";
    }
}
?>
