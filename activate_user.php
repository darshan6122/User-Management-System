<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Start session and check if user has admin privileges
session_start();
// Check if the user is logged in and is a manager
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}
if ($_SESSION['role'] !== 'Admin') {
    header('Location: login.php');
    exit;
}

// Get admin ID from session
$adminId = $_SESSION['employee_id'];


// Include required files
require_once 'db_connection.php';
require_once 'log_function.php';

// Check if user ID is provided in URL
if (isset($_GET['id'])) {
    // Sanitize user ID input
    $userId = (int)$_GET['id'];

    // Check if the user exists in database
    $checkQuery = "SELECT * FROM employee WHERE employee_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update user status to Active
        $updateQuery = "UPDATE employee SET Status = 'Active' WHERE employee_id = ?;"; 
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->bind_param('i', $userId);

        if ($updateStmt->execute()) {
            // Log the activation action
            $action = "Activated user ID: $userId";
            logAction($conn, $action, $userId, $adminId);
            
            // Redirect with success message
            header("Location: manage_users.php?message=User activated successfully.");
            exit; // Add exit after redirect
        } else {
            echo "Error activating user: " . $conn->error; // Updated error message
        }
        
        // Close prepared statement
        $updateStmt->close();
    } else {
        echo "User not found.";
    }
    
    // Close prepared statement and result
    $stmt->close();
    $result->close();
} else {
    echo "Invalid request.";
}

// Close database connection
$conn->close();
?>
