<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'healthcare_system';

// Create connection
$conn = new mysqli($host, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$sql_create_db = "CREATE DATABASE IF NOT EXISTS $database";
if ($conn->query($sql_create_db) === TRUE) {
    // Database created or already exists
} else {
    echo "Error creating database: " . $conn->error;
}

// Select the database
$conn->select_db($database);

// Create the 'users' table if it doesn't exist (with role column)
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,  -- Adjusted length for password hashing
    role VARCHAR(20) NOT NULL        -- New column for user role (doctor, patient)
)";
if ($conn->query($sql_users) === TRUE) {
    // Table 'users' created or already exists
} else {
    echo "Error creating users table: " . $conn->error;
}

// Insert a test user if the table is empty
$sql_insert_user = "INSERT IGNORE INTO users (username, password, role) VALUES ('testuser', '" . password_hash('password123', PASSWORD_DEFAULT) . "', 'doctor')";
if ($conn->query($sql_insert_user) === TRUE) {
    // Test user added or already exists
} else {
    echo "Error inserting test user: " . $conn->error;
}

// Create the 'vitals' table if it doesn't exist
$sql_vitals = "CREATE TABLE IF NOT EXISTS vitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    temperature FLOAT,
    heart_rate INT,
    blood_pressure VARCHAR(20),
    recorded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT vitals_ibfk_1 FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($conn->query($sql_vitals) === TRUE) {
    // Table 'vitals' created or already exists
} else {
    echo "Error creating vitals table: " . $conn->error;
}

?>
