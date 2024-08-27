<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "khotel";

// إنشاء اتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من اتصال قاعدة البيانات
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// التحقق من وجود كل البيانات المطلوبة
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
$rooms = (int)$_POST['rooms'];
$roomType = $_POST['room_type'];

// التحقق من توافر الغرف. إذا كان جدول الغرف فارغًا، سنسمح بالحجز
$query = "SELECT id FROM rooms WHERE room_type = ? AND available = 1 LIMIT ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $roomType, $rooms);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo "No rooms available or database is empty. Proceeding with default booking.";
}

if ($stmt->num_rows >= $rooms || $stmt->num_rows == 0) {
    // التعامل مع الحالة حيث توجد غرف متاحة أو الجدول فارغ (قبول الحجز)
    $bookQuery = "INSERT INTO bookings (name, email, check_in, check_out, room_id, persons, rooms) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $bookStmt = $conn->prepare($bookQuery);

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($roomId);
        $stmt->fetch();
        $bookStmt->bind_param('ssssiii', $name, $email, $checkIn, $checkOut, $roomId, $persons, $rooms);

        // تحديث حالة الغرفة في جدول الغرف إذا كانت موجودة
        $updateQuery = "UPDATE rooms SET available = 0 WHERE id = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $roomId);
        $updateStmt->execute();
    } else {
        // في حالة عدم وجود غرف، نستخدم 0 كـ room_id للإشارة إلى الحجز بدون غرفة معينة
        $roomId = 0;
        $bookStmt->bind_param('ssssiii', $name, $email, $checkIn, $checkOut, $roomId, $persons, $rooms);
    }

    $bookStmt->execute();
    echo "Reservation successful!";
} else {
    echo "Sorry, not enough rooms available for your selection.";
}

// إغلاق الاتصال بالبيانات والموارد
if (isset($bookStmt)) {
    $bookStmt->close();
}
if (isset($updateStmt)) {
    $updateStmt->close();
}
$stmt->close();
$conn->close();
?>
