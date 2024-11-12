<?php
session_start();
include 'db.php';
include 'header.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Fetch the logged-in user's details
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$sql = "SELECT * FROM vitals WHERE user_id = ? ORDER BY timestamp DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

echo "<h2>Welcome, $username</h2>";
echo "<h3>Your Latest Vital Signs</h3>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "Temperature: " . $row['temperature'] . "°C | Heart Rate: " . $row['heart_rate'] . " bpm | Blood Pressure: " . $row['blood_pressure'];
        echo "</div>";
    }
} else {
    echo "No vital signs recorded yet.";
}

include 'footer.php';
?>
