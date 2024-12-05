<?php
require_once 'db_connection.php';
require_once 'log_function.php';

session_start();
$adminId = $_SESSION['employee_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeId = intval($_POST['employee_id']);
    $performanceScore = intval($_POST['performance_score']);

    if ($employeeId && $performanceScore >= 0 && $performanceScore <= 100) {
        $sql = "UPDATE employee SET performance_score = ? WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $performanceScore, $employeeId);

        if ($stmt->execute()) {
            $action = "User Performance Updated";
            logAction($conn, $action, $employeeId, $adminId);
            header("Location: view_employees.php?status=success");
        } else {
            echo "Error updating performance score.";
        }
    } else {
        echo "Invalid input.";
    }
} else {
    echo "Invalid request.";
}
?>
