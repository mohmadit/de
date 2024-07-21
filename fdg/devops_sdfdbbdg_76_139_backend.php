<?php
require 'config.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$code_id = $_POST['id'];
$code = $_POST['code'];

$stmt = $conn->prepare("UPDATE codes SET code = ? WHERE id = ?");
$stmt->bind_param("si", $code, $code_id);

if ($stmt->execute()) {
echo "Code updated successfully";
} else {
echo "Error updating code: " . $conn->error;
}

$stmt->close();
}
?>