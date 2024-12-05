<?php
// Start the session
session_start();

// Include database connection and log function
require_once 'db_connection.php';
require_once 'log_function.php';

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskId = $_POST['task_id'];
    $taskName = $_POST['task_name'];
    $status = $_POST['status'];
    $dueDate = $_POST['due_date'];

    // Update the task in the database
    $updateQuery = "UPDATE task SET task_name = ?, status = ?, due_date = ? WHERE task_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sssi', $taskName, $status, $dueDate, $taskId);

    if ($stmt->execute()) {
        // Log the update action
        $userId = $_SESSION['employee_id'];
        $action = "Updated Task: $taskName (ID: $taskId)";
        logAction($conn, $action, $userId,$userId);

        $_SESSION['success_message'] = "Task updated successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to update task: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
    header("Location: view_tasks.php");
    exit;
}
?>
