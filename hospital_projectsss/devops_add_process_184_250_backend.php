<?php
$host = "localhost";
$db = "khotel";
$user = "root";
$pass = "";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}

// Ensure the server request method is defined and the form is submitted via POST
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $check_in = isset($_POST['check_in']) ? $_POST['check_in'] : null;
    $check_out = isset($_POST['check_out']) ? $_POST['check_out'] : null;
    $persons = isset($_POST['persons']) ? $_POST['persons'] : null;
    $rooms = isset($_POST['rooms']) ? $_POST['rooms'] : null;
    $room_type = isset($_POST['room_type']) ? $_POST['room_type'] : null;
    $room_number = rand(1, 100);

    if ($name && $email && $check_in && $check_out && $persons && $rooms && $room_type) {
        $query = $conn->prepare("SELECT * FROM reservations WHERE room_number = :room_number");
        $query->bindParam(':room_number', $room_number);
        $query->execute();

        if ($query->rowCount() > 0) {
            echo "Room number $room_number is already booked!";
        } else {
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
    } else {
        echo "Please fill in all the fields.";
    }
} else {
    echo "No form data submitted.";
}
?>
