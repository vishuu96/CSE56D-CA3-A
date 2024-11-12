<?php
function sendAlert($vital_id, $message) {
    global $conn;
    $sql = "INSERT INTO alerts (vital_id, message) VALUES ('$vital_id', '$message')";
    if ($conn->query($sql) === TRUE) {
        echo "Alert sent successfully!";
    } else {
        echo "Error sending alert: " . $conn->error;
    }
}
?>
