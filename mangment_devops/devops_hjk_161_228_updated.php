<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    $query = "DELETE FROM releases WHERE id = ?";

    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Release deleted successfully.";
        } else {
            echo "Failed to delete release.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to prepare statement: " . mysqli_error($conn);
    }
}
?>
