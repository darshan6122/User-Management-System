<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once 'db_connection.php';

// Get the employee ID from the session
$employee_id = $_SESSION['employee_id'];

// Fetch employee details
$query = "SELECT e.employee_id, e.username, e.first_name, e.last_name, e.`add` AS address, 
                 ec_email.contact_value AS email, ec_phone.contact_value AS phone 
          FROM employee e
          LEFT JOIN employee_contact ec_email ON e.employee_id = ec_email.employee_id AND ec_email.contact_type = 'Email'
          LEFT JOIN employee_contact ec_phone ON e.employee_id = ec_phone.employee_id AND ec_phone.contact_type = 'Phone'
          WHERE e.employee_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();
$stmt->close();

if (!$employee) {
    die("Employee record not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = isset($_POST['address']) ? $_POST['address'] : $employee['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($address)) {
        $address = "No Address Provided"; // Set a default value for address if it's missing
    }

    // Update employee table
    $updateQuery = "UPDATE employee SET username = ?, first_name = ?, last_name = ?, `add` = ? WHERE employee_id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssssi", $username, $first_name, $last_name, $address, $employee_id);
    $stmt->execute();
    $stmt->close();

    // Update or insert email
    $emailQuery = "INSERT INTO employee_contact (employee_id, contact_type, contact_value) 
                   VALUES (?, 'Email', ?) 
                   ON DUPLICATE KEY UPDATE contact_value = VALUES(contact_value)";
    $stmt = $conn->prepare($emailQuery);
    $stmt->bind_param("is", $employee_id, $email);
    $stmt->execute();
    $stmt->close();

    // Update or insert phone
    $phoneQuery = "INSERT INTO employee_contact (employee_id, contact_type, contact_value) 
                   VALUES (?, 'Phone', ?) 
                   ON DUPLICATE KEY UPDATE contact_value = VALUES(contact_value)";
    $stmt = $conn->prepare($phoneQuery);
    $stmt->bind_param("is", $employee_id, $phone);
    $stmt->execute();
    $stmt->close();

    // Redirect with success message
    header("Location: update_profile.php?success=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/edit_user.css">
    <title>Update Profile</title>
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">
            <div class="form-container">
                <h1>Update Profile</h1>
                <?php if (isset($_GET['success'])): ?>
                    <p class="success-message">Profile updated successfully!</p>
                <?php endif; ?>
                <form method="POST">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($employee['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($employee['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($employee['last_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($employee['address']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($employee['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone:</label>
                        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($employee['phone']); ?>" required>
                    </div>
                    <div class="button-group">
                        <button type="submit" class="save">Save</button>
                        <a href="user_dashboard.php" class="cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
