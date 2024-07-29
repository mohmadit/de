<?php
require 'config.php';


$query = "SELECT * FROM projects";
$result = mysqli_query($conn, $query);
$projects = mysqli_fetch_all($result, MYSQLI_ASSOC);

echo json_encode($projects);
?>