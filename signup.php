<?php
session_start();
include 'db.php';  // Include database connection

// Initialize variables for username, password, role, and error message
$username = '';
$password = '';
$role = 'patient';  // Default role
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the username, password, and role from the form
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validate input (you can add more validation as needed)
    if (empty($username) || empty($password)) {
        $error = "Username and Password are required.";
    } else {
        // Check if username already exists
        $sql_check = "SELECT * FROM users WHERE username = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows > 0) {
            $error = "Username already exists. Please choose another.";
        } else {
            // Hash the password for security
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into the database
            $sql_insert = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $username, $hashed_password, $role);
            
            if ($stmt_insert->execute()) {
                // Redirect to login page after successful signup
                header("Location: login.php");
                exit;
            } else {
                $error = "Error registering user. Please try again.";
            }
        }
        
        $stmt_check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - Healthcare Monitoring System</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to the external CSS file -->
</head>
<body>
    <div class="container">
        <h2>Signup</h2>

        <?php if (!empty($error)) : ?>
            <div class="alert">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="signup.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required value="<?php echo htmlspecialchars($username); ?>"><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>

            <label for="role">Role:</label>
            <select id="role" name="role">
                <option value="patient" <?php echo ($role == 'patient') ? 'selected' : ''; ?>>Patient</option>
                <option value="doctor" <?php echo ($role == 'doctor') ? 'selected' : ''; ?>>Doctor</option>
            </select><br>

            <input type="submit" value="Signup">
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <footer>
        <p>&copy; 2024 Healthcare Monitoring System</p>
    </footer>
</body>
</html>
