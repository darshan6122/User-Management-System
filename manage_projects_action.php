<?php
// Start session
session_start();

// Include database connection
require_once 'db_connection.php';
require_once 'log_function.php';

$adminId = $_SESSION['employee_id'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch data from POST requestadd_projects_action.php
    $project_id = isset($_POST['project_id']) ? $_POST['project_id'] : null;
    $project_name = isset($_POST['project_name']) ? $_POST['project_name'] : '';
    $start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $end_date = isset($_POST['end_date']) ? $_POST['end_date'] : null; // Optional
    $dept_id = isset($_POST['dept_id']) ? $_POST['dept_id'] : null;

    // Input validation
    if (empty($project_name) || empty($start_date) || empty($dept_id)) {
        $_SESSION['error'] = "All required fields must be filled.";
        header('Location: manage_projects.php');
        exit;
    }

    // Sanitize input
    $project_name = $conn->real_escape_string($project_name);
    $start_date = $conn->real_escape_string($start_date);
    $end_date = $end_date ? $conn->real_escape_string($end_date) : null;
    $dept_id = (int)$dept_id;

    if (empty($project_id)) {
        // Add a new project
        $insertQuery = "INSERT INTO project (project_name, start_date, end_date, dept_id) VALUES ('$project_name', '$start_date', " . ($end_date ? "'$end_date'" : "NULL") . ", $dept_id)";

        if ($conn->query($insertQuery) === TRUE) {
            $action = "New Project Added";
            logAction($conn, $action, $adminID, $adminId);
            $_SESSION['success'] = "New project added successfully!";
        } else {
            $_SESSION['error'] = "Error adding project: " . $conn->error;
        }
    } else {
        // Update existing project
        $project_id = (int)$project_id;
        $updateQuery = "UPDATE project SET 
                        project_name = '$project_name', 
                        start_date = '$start_date', 
                        end_date = " . ($end_date ? "'$end_date'" : "NULL") . ", 
                        dept_id = $dept_id 
                        WHERE project_id = $project_id";

        if ($conn->query($updateQuery) === TRUE) {
            $action = "Project Edited";
            logAction($conn, $action, $adminId, $adminId);
            $_SESSION['success'] = "Project updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating project: " . $conn->error;
        }
    }
} else {
    $_SESSION['error'] = "Invalid request method.";
}

// Redirect back to manage_projects.php
header('Location: manage_projects.php');
exit;
?>
