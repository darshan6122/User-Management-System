<?php
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Fetch and sanitize inputs
    $projectName = $conn->real_escape_string($_POST['project_name']);
    $startDate = $conn->real_escape_string($_POST['start_date']);
    $endDate = isset($_POST['end_date']) && !empty($_POST['end_date']) ? $conn->real_escape_string($_POST['end_date']) : NULL;
    $deptId = (int) $_POST['dept_id'];

    // Validate required fields
    if (empty($projectName) || empty($startDate) || empty($deptId)) {
        echo "<script>alert('All required fields must be filled out.'); window.location.href = 'manage_projects.php';</script>";
        exit();
    }

    // Insert into the database
    $query = "INSERT INTO project (project_name, start_date, end_date, dept_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param('sssi', $projectName, $startDate, $endDate, $deptId);
        if ($stmt->execute()) {
            echo "<script>alert('Project added successfully!'); window.location.href = 'manage_projects.php';</script>";
        } else {
            echo "<script>alert('Error adding project. Please try again.'); window.location.href = 'manage_projects.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Database error. Please try again later.'); window.location.href = 'manage_projects.php';</script>";
    }

    // Close connection
    $conn->close();
} else {
    // Redirect if accessed without POST method
    header('Location: manage_projects.php');
    exit();
}
?>
