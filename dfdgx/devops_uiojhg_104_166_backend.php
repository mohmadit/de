<?php

if ($stmt->execute()) {
echo "Code updated successfully";
} else {
echo "Error updating code: " . $conn->error;
}

$stmt->close();
}
?>