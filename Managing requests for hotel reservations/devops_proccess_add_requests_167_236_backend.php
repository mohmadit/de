<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_booking";

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// استلام البيانات من النموذج
$room_number = $_POST['room_number'];
$booking_date = $_POST['booking_date'];

// البحث عن الغرفة
$sql = "SELECT id FROM rooms WHERE room_number = ? AND available = TRUE";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $room_number);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // الحصول على معرف الغرفة
    $room = $result->fetch_assoc();
    $room_id = $room['id'];

    // إدخال الحجز
    $sql = "INSERT INTO bookings (room_id, booking_date) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $room_id, $booking_date);
    
    if ($stmt->execute()) {
        // تحديث حالة الغرفة لتصبح غير متاحة
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

// إغلاق الاتصال
$stmt->close();
$conn->close();
?>
