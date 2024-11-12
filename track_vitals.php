<?php
session_start();
include 'db.php';
include 'header.php';
include 'alert.php';

$user_id = $_SESSION['user_id'] ?? 1; 
if (!$conn) {
    die("Database connection not established.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $temperature = $_POST['temperature'];
    $heart_rate = $_POST['heart_rate'];
    $blood_pressure = $_POST['blood_pressure'];

  
    $stmt = $conn->prepare("INSERT INTO vitals (user_id, temperature, heart_rate, blood_pressure) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Statement preparation failed: " . $conn->error);
    }

    $stmt->bind_param("idss", $user_id, $temperature, $heart_rate, $blood_pressure);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Vital signs recorded successfully.</div>";

      
        $vital_id = $stmt->insert_id;

        if ($temperature >= 36 && $temperature < 38 && $heart_rate >= 60 && $heart_rate <= 100) {
            sendAlert($vital_id, "You are normal: {$temperature}°C, Heart Rate: {$heart_rate} bpm.");
        } elseif ($temperature >= 38 && $heart_rate > 100) {
            sendAlert($vital_id, "GO AND GET CHECKED BY DOCTOR: You are having a fever and high heart rate: {$temperature}°C, Heart Rate: {$heart_rate} bpm.");
        } elseif ($temperature < 36 && $heart_rate < 60) {
            sendAlert($vital_id, "GO AND GET CHECKED BY DOCTOR: You are experiencing cold symptoms and low heart rate: {$temperature}°C, Heart Rate: {$heart_rate} bpm.");
        } elseif ($temperature >= 38 && $heart_rate < 60) {
            sendAlert($vital_id, "GO AND GET CHECKED BY DOCTOR: You have a fever and low heart rate: {$temperature}°C, Heart Rate: {$heart_rate} bpm.");
        } elseif ($temperature < 36 && $heart_rate > 100) {
            sendAlert($vital_id, "GO AND GET CHECKED BY DOCTOR: You are experiencing cold symptoms and high heart rate: {$temperature}°C, Heart Rate: {$heart_rate} bpm.");
        }
    } else {
        echo "<div class='alert alert-info'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}
?>

<div class="container">
    <h2>Track Vital Signs</h2>
   
    <form method="POST">
        <label for="temperature">Temperature:</label>
        <input type="text" name="temperature" id="temperature" required><br>

        <label for="heart_rate">Heart Rate:</label>
        <input type="text" name="heart_rate" id="heart_rate" required><br>

        <label for="blood_pressure">Blood Pressure:</label>
        <input type="text" name="blood_pressure" id="blood_pressure" required><br>

        <input type="submit" value="Submit">
    </form>
</div>

<?php include 'footer.php'; ?>
