<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


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


$adminId = $_SESSION['employee_id'];


require_once 'db_connection.php';
require_once 'log_function.php';

if (isset($_GET['id'])) {
    $userId = (int)$_GET['id'];

    // Check if the user exists
    $checkQuery = "SELECT * FROM employee WHERE employee_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Proceed to delete the user
        $deleteQuery = "UPDATE employee SET Status = 'Inactive' WHERE employee_id = ?;";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param('i', $userId);

        if ($deleteStmt->execute()) {
            $action = "Deactivated user ID: $userId";
            logAction($conn, $action, $userId, $adminId);
            header("Location: manage_users.php?message=User deactivated successfully.");
        } else {
            echo "Error deleting user: " . $conn->error;
        }
    } else {
        echo "User not found.";
    }
} else {
    echo "Invalid request.";
}
?>