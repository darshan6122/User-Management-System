<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('db_connection.php');
require_once ('log_function.php');

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start(); // Start the session

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate inputs
    if (empty($username) || empty($password)) {
        die("Error: Both username and password are required.");
    }

    // Fetch user from the database
    $query = "SELECT employee_id, username, password, role FROM employee WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result === false) {
        die("Error querying database: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['employee_id'] = $user['employee_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            $adminId = $_SESSION['employee_id'];
            $action = "Logged In";
            logAction($conn, $action, $adminId, $adminId);

            // Redirect based on role
            switch ($user['role']) {
                case 'Admin':
                    header("Location: admin_dashboard.php");
                    break;
                case 'Manager':
                    header("Location: manager_dashboard.php");
                    break;
                case 'User':
                    header("Location: user_dashboard.php");
                    break;
                default:
                    die("Error: Invalid role.");
            }
            exit;
        } else {
            die("Error: Incorrect password.");
        }
    } else {
        die("Error: Username not found.");
    }
} else {
    header("Location: login.html");
    echo "No data submitted.";
}

// Close the connection
$conn->close();
?>
