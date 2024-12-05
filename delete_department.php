<?php
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

require_once 'db_connection.php';

if (isset($_GET['id'])) {
    $departmentId = $_GET['id'];

    $query = "DELETE FROM department WHERE department_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $departmentId);

    if ($stmt->execute()) {
        header('Location: manage_departments.php');
        exit;
    } else {
        echo "Error deleting department: " . $conn->error;
    }
} else {
    echo "Invalid department ID.";
}
