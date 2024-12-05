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

require_once 'db_connection.php';

// Pagination setup
$itemsPerPage = 20; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

// Handle search functionality
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';

// Fetch departments with pagination and optional search
$departmentsQuery = "
    SELECT 
        d.department_id, 
        d.department_name, 
        d.budget, 
        CONCAT(e.first_name, ' ', e.last_name) AS manager_name 
    FROM department d 
    LEFT JOIN employee e ON d.manager_id = e.employee_id
    WHERE d.department_name LIKE '%$search%' 
    LIMIT $itemsPerPage OFFSET $offset";

$departmentsResult = $conn->query($departmentsQuery);

if ($departmentsResult === false) {
    die("Error: " . $conn->error);
}

// Get total number of departments for pagination
$totalDepartmentsQuery = "SELECT COUNT(*) AS total FROM department WHERE department_name LIKE '%$search%'";
$totalDepartmentsResult = $conn->query($totalDepartmentsQuery);

if ($totalDepartmentsResult === false) {
    die("Error: " . $conn->error);
}

$totalDepartments = $totalDepartmentsResult->fetch_assoc()['total'];
$totalPages = ceil($totalDepartments / $itemsPerPage);

// Fetch managers for the dropdown
$managersQuery = "SELECT employee_id, CONCAT(first_name, ' ', last_name) AS full_name FROM employee WHERE role = 'Manager'";
$managersResult = $conn->query($managersQuery);

if ($managersResult === false) {
    die("Error: " . $conn->error);
}

$managers = [];
while ($row = $managersResult->fetch_assoc()) {
    $managers[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Departments</title>
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/manage_users.css">
    <link rel="stylesheet" href="styles/modal.css">
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_departments.php" class="active">Manage Departments</a></li>
                <li><a href="audit_logs.php">View Audit Logs</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>
        <main class="main-content">
            <header>
                <h1>Manage Departments</h1>
                <form method="GET" class="search-form">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search departments...">
                    <button type="submit" class="btn">Search</button>
                </form>
                <button class="btn add-btn" onclick="openModal()">Add Department</button>
            </header>
            <section>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Manager</th>
                            <th>Budget</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($departmentsResult->num_rows > 0): ?>
                            <?php while ($row = $departmentsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['department_id']; ?></td>
                                    <td><?php echo $row['department_name']; ?></td>
                                    <td><?php echo $row['manager_name'] ?: 'No Manager'; ?></td>
                                    <td><?php echo number_format($row['budget'], 2); ?></td>
                                    <td>
                                        <a href="edit_department.php?id=<?php echo $row['department_id']; ?>" class="btn edit">Edit</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No departments found.</td>
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

    <!-- Add Department Modal -->
    <div id="addDepartmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                Add Department
            </div>
            <div class="modal-body">
                <form method="POST" action="add_department_handler.php">
                    <label for="department_name">Department Name:</label>
                    <input type="text" id="department_name" name="department_name" required>

                    <label for="manager_id">Manager:</label>
                    <select id="manager_id" name="manager_id" required>
                        <option value="" disabled selected>Select a Manager</option>
                        <?php foreach ($managers as $manager): ?>
                            <option value="<?php echo $manager['employee_id']; ?>">
                                <?php echo $manager['full_name'], " (ID: ", $manager['employee_id'], ")"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="budget">Budget:</label>
                    <input type="number" id="budget" name="budget" step="0.01" required>

                    <div class="modal-footer">
                        <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="save-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            closeModal();
        });

        function openModal() {
            document.getElementById('addDepartmentModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('addDepartmentModal').style.display = 'none';
        }
    </script>
</body>
</html>
