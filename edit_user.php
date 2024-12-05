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

// Capture admin ID from session
$adminId = $_SESSION['employee_id'];

require_once 'db_connection.php';
require_once 'log_function.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch user details
    $query = "SELECT * FROM employee WHERE employee_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        echo "User not found.";
        exit;
    }
} else {
    echo "Invalid user ID.";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $updateQuery = "UPDATE employee SET `username` = ?, `first_name` = ?, `last_name` = ?, `add` = ?, `role` = ?, `Status` = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('ssssssi', $username, $first_name, $last_name, $address, $role, $status, $userId);

    if ($stmt->execute()) {
        echo "<script>alert('User updated successfully!');</script>";
        $action = "Updated user details: $username";
        logAction($conn, $action, $userId, $adminId);
        header("Location: manage_users.php");
        exit;
    } else {
        echo "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/edit_user.css">
    <title>Edit User</title>
</head>
<body>
    <div class="container">
        <main class="main-content">
            <header>
                <h1>Edit User</h1>
            </header>
            <div class="form-container">
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['add']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select id="role" name="role">
                            <option value="Admin" <?php echo ($user['role'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="Manager" <?php echo ($user['role'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                            <option value="User" <?php echo ($user['role'] == 'User') ? 'selected' : ''; ?>>User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select id="status" name="status">
                            <option value="Active" <?php echo ($user['Status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                            <option value="Inactive" <?php echo ($user['Status'] == 'Inactive') ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn save">Save Changes</button>
                        <a href="manage_users.php" class="btn cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
