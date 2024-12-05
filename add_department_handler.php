<?php

// Enable error display for development environment
ini_set('display_errors', 1);

// Initialize session and check admin authorization 
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

// Get admin ID from session for logging purposes
$adminId = $_SESSION['employee_id'];

// Remove duplicate error reporting settings
error_reporting(E_ALL);

// Include required database and logging functionality
require_once 'db_connection.php';
require_once 'log_function.php';

// Process form submission for creating new department
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate form inputs
    // Convert department name to safe string, manager ID to integer, budget to float
    $department_name = isset($_POST['department_name']) 
        ? $conn->real_escape_string($_POST['department_name']) 
        : '';
    $manager_id = isset($_POST['manager_id']) 
        ? intval($_POST['manager_id']) 
        : 0;
    $budget = isset($_POST['budget']) 
        ? floatval($_POST['budget']) 
        : 0;

    // Ensure all required fields have valid values
    if (empty($department_name) || $manager_id <= 0 || $budget <= 0) {
        die("Error: All fields are required, and budget/manager ID must be valid.");
    }

    // Prepare and execute SQL to insert new department
    $stmt = $conn->prepare(
        "INSERT INTO department (department_name, manager_id, budget) 
         VALUES (?, ?, ?)"
    );
    
    if (!$stmt) {
        die("Error preparing statement: " . $conn->error);
    }
    // Bind parameters with appropriate types: s=string, i=integer, d=decimal
    $stmt->bind_param('sid', $department_name, $manager_id, $budget);

    // Execute insert and handle result
    if ($stmt->execute()) {
        // Log successful department creation and redirect
        // Fix variable name typo from $departmentName to $department_name
        $action = "Added a new department: $department_name";
        logAction($conn, $action, null, $adminId);
        header("Location: manage_departments.php?success=1");
        exit;
    } else {
        die("Error inserting data: " . $stmt->error);
    }
} else {
    // Handle non-POST requests
    die("Invalid request method.");
}

?>
