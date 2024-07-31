<?php
require 'config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$sql = "SELECT * FROM tasks WHERE id = $id";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
} else {
    echo "0 results";
}
$conn->close();

echo json_encode($data);
?>
