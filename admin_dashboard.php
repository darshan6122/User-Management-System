<?php
// Start session and check admin authorization
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

// Include database connection
require_once 'db_connection.php';

// SQL queries to fetch analytics data
// Count total users in employee table
$totalUsersQuery = "SELECT COUNT(*) AS total_users FROM employee";
// Count total departments
$totalDepartmentsQuery = "SELECT COUNT(*) AS total_departments FROM department"; 
// Count active users
$activeUsersQuery = "SELECT COUNT(*) AS active_users FROM employee WHERE Status = 'Active'";
// Count inactive users 
$inactiveUsersQuery = "SELECT COUNT(*) AS inactive_users FROM employee WHERE Status = 'Inactive'";
// Get department budgets for pie chart
$departmentBudgetsQuery = "SELECT department_name, budget FROM department";
// Get user role distribution for column chart
$userRolesQuery = "SELECT role, COUNT(*) as count FROM employee GROUP BY role";

// Execute queries and fetch results
$totalUsers = $conn->query($totalUsersQuery)->fetch_assoc()['total_users'];
$totalDepartments = $conn->query($totalDepartmentsQuery)->fetch_assoc()['total_departments'];
$activeUsers = $conn->query($activeUsersQuery)->fetch_assoc()['active_users'];
$inactiveUsers = $conn->query($inactiveUsersQuery)->fetch_assoc()['inactive_users'];

$departmentBudgetsResult = $conn->query($departmentBudgetsQuery);
$userRolesResult = $conn->query($userRolesQuery);

// Prepare data arrays for Google Charts
$departmentBudgets = [];
while ($row = $departmentBudgetsResult->fetch_assoc()) {
    // Cast budget to float to ensure proper numeric handling
    $departmentBudgets[] = [$row['department_name'], (float)$row['budget']];
}

$userRoles = [];
while ($row = $userRolesResult->fetch_assoc()) {
    // Cast count to integer for role distribution data
    $userRoles[] = [$row['role'], (int)$row['count']];
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Include CSS files -->
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/dashboard.css">
    <!-- Load Google Charts library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        // Initialize Google Charts
        google.charts.load('current', {
            packages: ['corechart']
        });

        // Draw Department Budget Chart
        google.charts.setOnLoadCallback(drawBudgetChart);

        function drawBudgetChart() {
            var data = google.visualization.arrayToDataTable([
                ['Department', 'Budget'],
                <?php
                // Output department budget data
                foreach ($departmentBudgets as $item) {
                    echo "['" . $item[0] . "', " . $item[1] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'Department Budgets',
                pieHole: 0.3, // Creates a donut chart
                chartArea: {
                    width: '90%',
                    height: '70%'
                },
                colors: ['#1abc9c', '#3498db', '#9b59b6', '#e74c3c', '#34495e'],
            };

            var chart = new google.visualization.PieChart(document.getElementById('budgetChart'));
            chart.draw(data, options);
        }

        // Draw User Role Distribution Chart
        google.charts.setOnLoadCallback(drawRoleChart);

        function drawRoleChart() {
            var data = google.visualization.arrayToDataTable([
                ['Role', 'Count'],
                <?php
                // Output user role distribution data
                foreach ($userRoles as $item) {
                    echo "['" . $item[0] . "', " . $item[1] . "],";
                }
                ?>
            ]);

            var options = {
                title: 'User Role Distribution',
                chartArea: {
                    width: '70%',
                    height: '70%'
                },
                colors: ['#1abc9c', '#e74c3c', '#3498db'],
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('roleChart'));
            chart.draw(data, options);
        }

        
        // // Draw Active vs. Inactive Users Chart
        google.charts.setOnLoadCallback(drawUserStatusChart);
        function drawUserStatusChart() {
            var data = google.visualization.arrayToDataTable([
                ['Status', 'Count'],
                ['Active', <?php echo $activeUsers; ?>],
                ['Inactive', <?php echo $inactiveUsers; ?>]
            ]);

            var options = {
                title: 'User Status',
                pieHole: 0.4,
                chartArea: { width: '90%', height: '70%' },
                colors: ['#1abc9c', '#e74c3c'],
            };

            var chart = new google.visualization.PieChart(document.getElementById('userStatusChart'));
            chart.draw(data, options);
        }
    </script>
</head>

<body>
    <div class="container">
        <!-- Sidebar navigation -->
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <ul>
                <li><a href="admin_dashboard.php" class="active">Dashboard</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_departments.php">Manage Departments</a></li>
                <li><a href="audit_logs.php">View Audit Logs</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </aside>
        <!-- Main content area -->
        <main class="main-content">
            <!-- Welcome header -->
            <header>
                <h1 style="background: linear-gradient(135deg, #8e24aa, #6a1b9a); color: white; padding: 10px; border-radius: 8px; text-align: center;">
                    Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!
                </h1>
            </header>

            <!-- Dashboard statistics cards -->
            <section class="dashboard-cards">
                <div onclick="window.location.href='manage_users.php'" style="cursor: pointer;" class="card">
                    <h3>Total Users</h3>
                    <p><?php echo htmlspecialchars($totalUsers); ?></p>
                </div>
                <div onclick="window.location.href='manage_departments.php'" style="cursor: pointer;" class="card">
                    <h3>Total Departments</h3>
                    <p><?php echo htmlspecialchars($totalDepartments); ?></p>
                </div>
                <div onclick="window.location.href='manage_users.php'" style="cursor: pointer;" class="card">
                    <h3>Active Users</h3>
                    <p><?php echo htmlspecialchars($activeUsers); ?></p>
                </div>
                <div onclick="window.location.href='manage_users.php'" style="cursor: pointer;" class="card">
                    <h3>Inactive Users</h3>
                    <p><?php echo htmlspecialchars($inactiveUsers); ?></p>
                </div>
            </section>
            <!-- Analytics charts section -->
            <section class="charts">
                <h2>Analytics</h2>
                <div class="chart-wrapper">
                    <div id="budgetChart" style="width: 100%; height: 400px;"></div>
                </div>
                <div class="chart-wrapper">
                    <div id="roleChart" style="width: 100%; height: 400px;"></div>
                </div>
                <div class="chart-wrapper">
                    <div id="userStatusChart" style="width: 100%; height: 400px;"></div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
