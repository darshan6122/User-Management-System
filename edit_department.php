<?php

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

$adminId = $_SESSION['employee_id'];


require_once 'db_connection.php';
require_once 'log_function.php';

if (isset($_GET['id'])) {
    $departmentId = $_GET['id'];

    // Fetch department details
    $query = "SELECT * FROM department WHERE department_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $department = $result->fetch_assoc();

    if (!$department) {
        echo "Department not found.";
        exit;
    }
} else {
    echo "Invalid department ID.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department_name = $_POST['department_name'];
    $manager_id = $_POST['manager_id'];
    $budget = $_POST['budget'];

    $updateQuery = "UPDATE department SET department_name = ?, manager_id = ?, budget = ? WHERE department_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('sidi', $department_name, $manager_id, $budget, $departmentId);

    if ($stmt->execute()) {
        $action = "Updated department details: $department_name";
        echo "<script>alert('Department updated successfully.');";
        logAction($conn, $action, null, $adminId);
        echo "window.location.href='manage_departments.php';</script>";
    } else {
        echo "Error updating department: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Department</title>
    <link rel="stylesheet" href="styles/edit_user.css">
</head>

<body>
    <div class="form-container">
        <h1>Edit Department</h1>
        <form method="POST">
            <div class="form-group">
                <label for="department_name">Department Name:</label>
                <input type="text" id="department_name" name="department_name" value="<?php echo htmlspecialchars($department['department_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="manager_id">Manager ID:</label>
                <input type="number" id="manager_id" name="manager_id" value="<?php echo htmlspecialchars($department['manager_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="budget">Budget:</label>
                <input type="number" id="budget" name="budget" step="0.01" value="<?php echo htmlspecialchars($department['budget']); ?>" required>
            </div>
            <div class="button-group">
                <button type="submit" class="save">Save Changes</button>
                <a href="manage_departments.php" class="cancel">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>