<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session and check if user is admin
session_start();

// Check if the user is logged in and is a manager
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

// Include required files
require_once 'db_connection.php';
require_once 'log_function.php';

// Get admin ID from session for logging
$adminId = $_SESSION['employee_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form inputs
    // Using real_escape_string for additional SQL injection protection
    // trim() removes whitespace from text fields
    $ssn = isset($_POST['ssn']) ? $conn->real_escape_string($_POST['ssn']) : null;
    $username = isset($_POST['username']) ? $conn->real_escape_string(trim($_POST['username'])) : null;
    // Hash password using secure algorithm
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
    $first_name = isset($_POST['first_name']) ? $conn->real_escape_string(trim($_POST['first_name'])) : null;
    $last_name = isset($_POST['last_name']) ? $conn->real_escape_string(trim($_POST['last_name'])) : null;
    $address = isset($_POST['address']) ? $conn->real_escape_string(trim($_POST['address'])) : null;
    $dob = isset($_POST['dob']) ? $conn->real_escape_string($_POST['dob']) : null;
    // Set default values for role and status if not provided
    $role = isset($_POST['role']) ? $conn->real_escape_string($_POST['role']) : 'User';
    $status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : 'Active';

    // Validate that all required fields are filled
    if (empty($ssn) || empty($username) || empty($password) || empty($first_name) || empty($last_name) || empty($address) || empty($dob)) {
        $_SESSION['error'] = 'All fields are required.';
        header('Location: manage_users.php');
        exit;
    }

    // Prevent duplicate usernames
    $checkQuery = "SELECT username FROM employee WHERE username = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Username already exists. Please choose a different username.';
        header('Location: manage_users.php');
        exit;
    }

    // Insert new employee record using prepared statement to prevent SQL injection
    $insertQuery = "INSERT INTO employee (`SSN`, `username`, `password`, `first_name`, `last_name`, `add`, `DOB`, `role`, `Status`) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('issssssss', $ssn, $username, $password, $first_name, $last_name, $address, $dob, $role, $status);

    if ($stmt->execute()) {
        // Get ID of newly created employee for logging
        $newEmployeeId = $conn->insert_id;

        // Log the user creation action
        $action = "Added a new user: $username";
        logAction($conn, $action, $newEmployeeId, $adminId);

        $_SESSION['success'] = 'User added successfully.';
    } else {
        $_SESSION['error'] = 'Failed to add user: ' . $stmt->error;
    }

    // Clean up database resources
    $stmt->close();
    $conn->close();

    // Redirect back to user management page
    header('Location: manage_users.php');
    exit;
} else {
    // Handle non-POST requests
    $_SESSION['error'] = 'Invalid request method.';
    header('Location: manage_users.php');
    exit;
}
