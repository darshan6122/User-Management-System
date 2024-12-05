<?php
// Start session and check manager authorization
session_start();

// Check if the user is logged in and is a manager
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] !== 'Manager') {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once 'db_connection.php';

// Manager's ID
$managerId = $_SESSION['employee_id'];

// SQL queries to fetch analytics data
$totalEmployeesQuery = "SELECT COUNT(*) AS total_employees FROM employee_supervisor WHERE supervisor_id = $managerId";
$activeEmployeesQuery = "
    SELECT COUNT(*) AS active_employees 
    FROM employee_supervisor 
    INNER JOIN employee ON employee_supervisor.employee_id = employee.employee_id
    WHERE supervisor_id = $managerId AND employee.Status = 'Active'
";
$assignedProjectsQuery = "SELECT COUNT(*) AS total_projects FROM project WHERE dept_id IN (SELECT dept_id FROM employee_role WHERE employee_id = $managerId)";
$tasksQuery = "
    SELECT 
        SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_tasks,
        SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) AS in_progress_tasks,
        SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_tasks
    FROM task
    WHERE project_id IN (SELECT project_id FROM project WHERE dept_id IN (SELECT dept_id FROM employee_role WHERE employee_id = $managerId))
";

// Execute queries and fetch results
$totalEmployees = $conn->query($totalEmployeesQuery)->fetch_assoc()['total_employees'];
$activeEmployees = $conn->query($activeEmployeesQuery)->fetch_assoc()['active_employees'];
$totalProjects = $conn->query($assignedProjectsQuery)->fetch_assoc()['total_projects'];
$tasksResult = $conn->query($tasksQuery)->fetch_assoc();

$completedTasks = $tasksResult['completed_tasks'];
$inProgressTasks = $tasksResult['in_progress_tasks'];
$pendingTasks = $tasksResult['pending_tasks'];

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <!-- Include CSS files -->
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/dashboard.css">
    <!-- Load Google Charts library -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        google.charts.load('current', { packages: ['corechart'] });

        // Employee Status Chart
        google.charts.setOnLoadCallback(drawEmployeeStatusChart);
        function drawEmployeeStatusChart() {
            var data = google.visualization.arrayToDataTable([
                ['Status', 'Count'],
                ['Active', <?php echo $activeEmployees; ?>],
                ['Inactive', <?php echo $totalEmployees - $activeEmployees; ?>]
            ]);

            var options = {
                title: 'Employee Status',
                pieHole: 0.4,
                chartArea: { width: '90%', height: '70%' },
                colors: ['#1abc9c', '#e74c3c'],
            };

            var chart = new google.visualization.PieChart(document.getElementById('employeeStatusChart'));
            chart.draw(data, options);
        }

        // Task Status Chart
        google.charts.setOnLoadCallback(drawTaskStatusChart);
        function drawTaskStatusChart() {
            var data = google.visualization.arrayToDataTable([
                ['Status', 'Count'],
                ['Completed', <?php echo $completedTasks; ?>],
                ['In Progress', <?php echo $inProgressTasks; ?>],
                ['Pending', <?php echo $pendingTasks; ?>]
            ]);

            var options = {
                title: 'Task Status',
                pieHole: 0.4,
                chartArea: { width: '90%', height: '70%' },
                colors: ['#3498db', '#e67e22', '#e74c3c'],
            };

            var chart = new google.visualization.PieChart(document.getElementById('taskStatusChart'));
            chart.draw(data, options);
        }
    </script>
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include 'manager_sidebar.php'; ?>

        <!-- Main content area -->
        <main class="main-content">
            <!-- Welcome header -->
            <header>
                <h1>Welcome, Manager <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
            </header>

            <!-- Dashboard statistics cards -->
            <section class="dashboard-cards">
                <div class="card">
                    <h3>Total Employees</h3>
                    <p><?php echo htmlspecialchars($totalEmployees); ?></p>
                </div>
                <div class="card">
                    <h3>Active Employees</h3>
                    <p><?php echo htmlspecialchars($activeEmployees); ?></p>
                </div>
                <div class="card">
                    <h3>Total Projects</h3>
                    <p><?php echo htmlspecialchars($totalProjects); ?></p>
                </div>
                <div class="card">
                    <h3>Total Tasks</h3>
                    <p><?php echo htmlspecialchars($completedTasks + $inProgressTasks + $pendingTasks); ?></p>
                </div>
            </section>

            <!-- Analytics charts section -->
            <section class="charts">
                <h2>Analytics</h2>
                <div class="chart-wrapper">
                    <div id="employeeStatusChart" style="width: 100%; height: 400px;"></div>
                </div>
                <div class="chart-wrapper">
                    <div id="taskStatusChart" style="width: 100%; height: 400px;"></div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>
