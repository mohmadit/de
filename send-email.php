<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'config.php'; // ملف إعدادات الاتصال بقاعدة البيانات

$mail = new PHPMailer(true);

try {
    // الاتصال بقاعدة البيانات
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // جلب عناوين البريد الإلكتروني لفريق التطوير
    $result = $conn->query("SELECT email FROM users WHERE role = 'Development Team'");
    $emails = [];
    while ($row = $result->fetch_assoc()) {
        $emails[] = $row['email'];
    }

    $conn->close();

    // إعدادات السيرفر
    $mail->isSMTP();
    $mail->Host = 'smtp.example.com'; // استبدل بـ SMTP مزود الخدمة الخاص بك
    $mail->SMTPAuth = true;
    $mail->Username = getenv('EMAIL');
    $mail->Password = getenv('EMAIL_PASSWORD');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // إعداد المستلمين
    $mail->setFrom(getenv('EMAIL'), 'CI/CD Bot');
    foreach ($emails as $email) {
        $mail->addAddress($email);
    }

    // المحتوى
    $mail->isHTML(true);
    $mail->Subject = 'CI/CD Pipeline Result';
    $mail->Body = 'The CI/CD pipeline has completed. Please check the results.';

    // إرفاق ملف نتائج الاختبارات
    if (file_exists('./test-results')) {
        $mail->addAttachment('./test-results');
    }

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
