<?php
// Start session
session_start();

// Include database connection
require_once 'db_connection.php';
require_once 'log_function.php';

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Determine the action
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'add' || $action === 'edit') {
        // Fetch project details from POST
        $project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : null;
        $project_name = isset($_POST['project_name']) ? trim($_POST['project_name']) : '';
        $start_date = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
        $end_date = isset($_POST['end_date']) ? trim($_POST['end_date']) : null;
        $dept_id = isset($_POST['dept_id']) ? intval($_POST['dept_id']) : null;

        // Validate inputs
        if (empty($project_name) || empty($start_date) || empty($dept_id)) {
            $_SESSION['error'] = 'Project name, start date, and department are required.';
            header('Location: manage_projects.php');
            exit;
        }

        if ($action === 'add') {
            // Insert new project
            $sql = "INSERT INTO project (project_name, start_date, end_date, dept_id) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $project_name, $start_date, $end_date, $dept_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Project added successfully!';
            } else {
                $_SESSION['error'] = 'Error adding project: ' . $stmt->error;
            }
        } elseif ($action === 'edit' && $project_id) {
            // Update existing project
            $sql = "UPDATE project 
                    SET project_name = ?, start_date = ?, end_date = ?, dept_id = ? 
                    WHERE project_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssii", $project_name, $start_date, $end_date, $dept_id, $project_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Project updated successfully!';
            } else {
                $_SESSION['error'] = 'Error updating project: ' . $stmt->error;
            }
        }

        $stmt->close();
    } elseif ($action === 'delete') {
        // Delete a project
        $project_id = isset($_POST['project_id']) ? intval($_POST['project_id']) : null;

        if ($project_id) {
            $sql = "DELETE FROM project WHERE project_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $project_id);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'Project deleted successfully!';
            } else {
                $_SESSION['error'] = 'Error deleting project: ' . $stmt->error;
            }

            $stmt->close();
        } else {
            $_SESSION['error'] = 'Invalid project ID.';
        }
    } else {
        $_SESSION['error'] = 'Invalid action.';
    }
} else {
    $_SESSION['error'] = 'Invalid request method.';
}

// Redirect back to manage_projects.php
header('Location: manage_projects.php');
exit;
?>
