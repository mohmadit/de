<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    if (file_exists($file)) {
        http_response_code(200);
        echo "File exists";
    } else {
        http_response_code(404);
        echo "File does not exist";
    }
} else {
    http_response_code(400);
    echo "File parameter missing";
}
?>