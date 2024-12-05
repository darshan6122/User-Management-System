<?php
// Start the session and check for admin authentication
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

// Include database connection
require_once 'db_connection.php';

// Pagination setup - configure number of items per page and calculate offset
$itemsPerPage = 20;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; // Ensure page is at least 1
$offset = ($page - 1) * $itemsPerPage;

// Handle search functionality - escape search input to prevent SQL injection
$search = isset($_GET['search']) ? $conn->real_escape_string(trim($_GET['search'])) : '';

// Prepare the base query for audit logs with pagination and search
// Uses LEFT JOIN to include logs even if employee no longer exists
$auditLogsQuery = "
    SELECT 
        al.log_id, 
        al.action, 
        CONCAT(COALESCE(e.first_name, ''), ' ', COALESCE(e.last_name, '')) AS employee_name, 
        al.timestamp 
    FROM audit_log al
    LEFT JOIN employee e ON al.employee_id = e.employee_id
    WHERE al.action LIKE ? 
       OR CONCAT(COALESCE(e.first_name, ''), ' ', COALESCE(e.last_name, '')) LIKE ? 
    ORDER BY al.timestamp DESC
    LIMIT ? OFFSET ?";

// Use prepared statement to prevent SQL injection
$stmt = $conn->prepare($auditLogsQuery);
$searchParam = "%$search%";
$stmt->bind_param("ssii", $searchParam, $searchParam, $itemsPerPage, $offset);
$stmt->execute();
$auditLogsResult = $stmt->get_result();

if ($auditLogsResult === false) {
    die("Error executing query: " . $conn->error);
}

// Get total number of logs for pagination
$totalLogsQuery = "
    SELECT COUNT(*) AS total 
    FROM audit_log al
    LEFT JOIN employee e ON al.employee_id = e.employee_id
    WHERE al.action LIKE ? 
       OR CONCAT(COALESCE(e.first_name, ''), ' ', COALESCE(e.last_name, '')) LIKE ?";

$stmtTotal = $conn->prepare($totalLogsQuery);
$stmtTotal->bind_param("ss", $searchParam, $searchParam);
$stmtTotal->execute();
$totalLogsResult = $stmtTotal->get_result();

if ($totalLogsResult === false) {
    die("Error getting total count: " . $conn->error);
}

$totalLogs = $totalLogsResult->fetch_assoc()['total'];
$totalPages = ceil($totalLogs / $itemsPerPage);

// Close prepared statements and database connection
$stmt->close();
$stmtTotal->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/manage_users.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar navigation -->
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php">Dashboard</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_departments.php">Manage Departments</a></li>
                <li><a href="audit_logs.php" class="active">View Audit Logs</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>
        
        <!-- Main content area -->
        <main class="main-content">
            <header>
                <h1>Audit Logs</h1>
                <!-- Search form -->
                <form method="GET" class="search-form">
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search logs...">
                    <button type="submit" class="btn">Search</button>
                </form>
            </header>
            <section>
                <!-- Audit logs table -->
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Action</th>
                            <th>Employee</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($auditLogsResult->num_rows > 0): ?>
                            <?php while ($row = $auditLogsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['log_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['action']); ?></td>
                                    <td><?php echo htmlspecialchars($row['employee_name'] ?: 'System'); ?></td>
                                    <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No logs found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <!-- Pagination controls -->
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo ($page - 1); ?>&search=<?php echo urlencode($search); ?>">Previous</a>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
                           class="<?php echo $i == $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo ($page + 1); ?>&search=<?php echo urlencode($search); ?>">Next</a>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
