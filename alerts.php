<?php
include 'db.php';
include 'header.php';

$sql = "SELECT * FROM alerts ORDER BY timestamp DESC";
$result = $conn->query($sql);

echo "<div class='alerts-container'>";
echo "<h2>Alert Log</h2>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='alert'>";
        echo "<div class='icon'>🔔</div>"; // Optionally, you can add an icon
        echo "Alert ID: " . $row['id'] . " | Message: " . $row['message'] . " | Status: " . $row['status'] . " | Date: " . $row['timestamp'];
        echo "</div>";
    }
} else {
    echo "<div class='alert alert-info'>No alerts recorded.</div>";
}

echo "</div>";

include 'footer.php';
?>
