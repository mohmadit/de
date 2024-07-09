<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "de";

// إنشاء اتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
die("Failed to connect to database: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$task_id = $_POST['task_id'];
$report_text = $_POST['report_text'];
$sender_name = $_POST['sender_name'];
$description = $_POST['description'];
$recipient_role = $_POST['recipient_role'];
$created_at = $_POST['created_at'];

// إدراج التقرير في جدول reports
$insert_query = "INSERT INTO reports (task_id, report_text, sender_name, description, recipient, created_at) VALUES (?, ?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_query);
$insert_stmt->bind_param("isssss", $task_id, $report_text, $sender_name, $description, $recipient_role, $created_at);

if ($insert_stmt->execute()) {
// إذا تمت إضافة التقرير بنجاح، يمكن إرسال رسالة الإشعار إلى الدور المستلم
$notification_message = "You have received a new report from $sender_name.";
$notification_stmt = $conn->prepare("INSERT INTO notifications (recipient, message) VALUES (?, ?)");
$notification_stmt->bind_param("ss", $recipient_role, $notification_message);

if ($notification_stmt->execute()) {
header("Location: view_reports.php?status=success");
} else {
header("Location: view_reports.php?status=error&message=" . $notification_stmt->error);
}

$notification_stmt->close();
} else {
header("Location: view_reports.php?status=error&message=" . $insert_stmt->error);
}

$insert_stmt->close();
}

$conn->close();
?>
