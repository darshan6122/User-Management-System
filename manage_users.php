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

require_once 'db_connection.php'; // Database connection file

// Handle form submission for adding a new user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $role = $_POST['role'];

    $insertQuery = "INSERT INTO employee (username, password, first_name, last_name, add, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param('ssssss', $username, $password, $first_name, $last_name, $address, $role);

    if ($stmt->execute()) {
        $successMessage = "User added successfully.";
    } else {
        $errorMessage = "Error adding user: " . $conn->error;
    }
}

// Pagination setup
$itemsPerPage = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Handle search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch users with pagination and optional search
$usersQuery = "SELECT employee_id, username, first_name, last_name, role, Status 
               FROM employee 
               WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR username LIKE '%$search%' 
               LIMIT $itemsPerPage OFFSET $offset";
$usersResult = $conn->query($usersQuery);

// Count total users for pagination
$totalUsersQuery = "SELECT COUNT(*) AS total FROM employee 
                    WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR username LIKE '%$search%'";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_assoc()['total'];
$totalPages = ceil($totalUsers / $itemsPerPage);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="styles/sidebar.css">
        <link rel="stylesheet" href="styles/manage_users.css">
        <link rel="stylesheet" href="styles/modal.css">
        <title>Manage Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="container">
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="manage_users.php" class="active">Manage Users</a></li>
            <li><a href="manage_departments.php">Manage Departments</a></li>
            <li><a href="audit_logs.php">View Audit Logs</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>
        <main class="main-content">
            <header>
                <h1>Manage Users</h1>
                <form method="GET" class="search-form">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search users...">
                    <button type="submit" class="btn">Search</button>
                </form>
                <button onclick="openModal()" class="btn">Add User</button>
            </header>
            <?php if (isset($successMessage)): ?>
                <p class="success-message"><?php echo $successMessage; ?></p>
            <?php elseif (isset($errorMessage)): ?>
                <p class="error-message"><?php echo $errorMessage; ?></p>
            <?php endif; ?>
            <section>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($usersResult->num_rows > 0): ?>
                            <?php while ($row = $usersResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['employee_id']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['first_name']; ?></td>
                                    <td><?php echo $row['last_name']; ?></td>
                                    <td><?php echo $row['role']; ?></td>
                                    <td><?php echo $row['Status']; ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?php echo $row['employee_id']; ?>" class="btn edit">Edit</a>
                                        <a href="activate_user.php?id=<?php echo $row['employee_id']; ?>" class="btn activate" onclick="return confirm('Are you sure you want to Activate this user?')">Activate</a>
                                        <a href="deactivate_user.php?id=<?php echo $row['employee_id']; ?>" class="btn deactivate" onclick="return confirm('Are you sure you want to Deactivate this user?')">Deactivate</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Add User</div>
            <div class="modal-body">
                <form id="addUserForm" method="POST" action="add_user_handler.php">
                    <label for="ssn">SSN:</label>
                    <input type="number" name="ssn" id="ssn" required>

                    <label for="username">Username:</label>
                    <input type="text" name="username" id="username" required>

                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" required>

                    <label for="first_name">First Name:</label>
                    <input type="text" name="first_name" id="first_name" required>

                    <label for="last_name">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" required>

                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address" required>

                    <label for="dob">Date of Birth:</label>
                    <input type="date" name="dob" id="dob" required>

                    <label for="role">Role:</label>
                    <select name="role" id="role" required>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="User" selected>User</option>
                    </select>

                    <label for="status">Status:</label>
                    <select name="status" id="status" required>
                        <option value="Active" selected>Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <button class="save-btn" type="submit" form="addUserForm">Save</button>
                <button class="cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Ensure the modal is hidden by default
        document.addEventListener('DOMContentLoaded', function() {
            closeModal(); // Ensure modal is hidden after the page loads
        });

        function openModal() {
            document.getElementById('addUserModal').style.display = 'flex'; // Show modal
        }

        function closeModal() {
            document.getElementById('addUserModal').style.display = 'none'; // Hide modal
        }
    </script>

</body>

</html>