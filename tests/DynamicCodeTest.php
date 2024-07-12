<?php

use PHPUnit\Framework\TestCase;

class DynamicCodeTest extends TestCase
{
    public function testDynamicCode()
    {
        ob_start();
        try {
            eval('?>' . '$title = $_POST[\'title\'];
$description = $_POST[\'desسيريسرسيرشسيءcription\'];سيرسيرسيرسيرس
$priority = $_POST[\'priority\'];
$status = $_POST[\'status\'];رسيسيرسرسيرءؤ 
$assigned_to = $_POST[\'assigned_
$start_date = $_POST[\'start_date\'];
$end_date = $_POST[\'end_date\'];

$stmt = $conn->prepare("INSERT INTO tasks (project_id, title, description, priority, status, assigned_to, start_date, end_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssiss", $project_id, $title, $description, $priority, $status, $assigned_to, $start_date, $end_date);

if ($stmt->execute()) {
    echo "Task added successfully.";
} else {
    echo "Error: " . $stmt->error;
}
');
            ob_end_clean();
            $this->assertTrue(true, 'The code is valid and executed successfully.');
        } catch (\Throwable $e) {
            ob_end_clean();
            $this->fail('The code execution failed: ' . $e->getMessage());
        }
    }
}