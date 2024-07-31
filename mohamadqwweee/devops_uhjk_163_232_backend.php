<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (isset($_POST['task_id']) && isset($_POST['code'])) {
$task_id = $_POST['task_id'];
$code = htmlspecialchars($_POST['code'], ENT_QUOTES, 'UTF-8');

$stmt = $conn->prepare("INSERT INTO codes (task_id, code) VALUES (?, ?)");
if ($stmt) {
$stmt->bind_param("is", $task_id, $code);

if ($stmt->execute()) {
$new_id = $stmt->insert_id;
$created_at = date('Y-m-d H:i:s');
$updated_at = $created_at;
echo "<tr id='code_$new_id'>
<td>$new_id</td>
<td>$code</td>
<td>$created_at</td>
<td>$updated_at</td>
<td><button class='btn btn-danger' onclick='deleteCode($new_id)'>Delete</button></td>
</tr>";
} else {
echo "Error adding code: " . $stmt->error;
}
$stmt->close();
} else {
echo "Error preparing statement: " . $conn->error;
}
} else {
echo "Missing task_id or code in POST data";
}
} else {
echo "Invalid request method";
}

$conn->close();
?>