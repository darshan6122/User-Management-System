<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once 'db_connection.php';

// Fetch user details
$userId = $_SESSION['employee_id']; // Assuming 'user_id' is stored in the session
$userQuery = "
    SELECT e.first_name, e.last_name, e.role, e.performance_score, e.Status, 
           d.department_name, ec.contact_value AS email
    FROM employee e
    LEFT JOIN department d ON e.employee_id = d.manager_id
    LEFT JOIN employee_contact ec ON e.employee_id = ec.employee_id AND ec.contact_type = 'Email'
    WHERE e.employee_id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$userResult = $stmt->get_result();
$userData = $userResult->fetch_assoc();

// Fetch user's task data
$tasksQuery = "
    SELECT status, COUNT(*) AS count
    FROM task 
    WHERE project_id IN (SELECT project_id FROM project_assignment WHERE employee_id = ?)
    GROUP BY status";
$stmt = $conn->prepare($tasksQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$tasksResult = $stmt->get_result();
$tasksData = [];
while ($row = $tasksResult->fetch_assoc()) {
    $tasksData[$row['status']] = $row['count'];
}

// Fetch user's recent activities
$recentActivityQuery = "
    SELECT action, timestamp 
    FROM audit_log 
    WHERE employee_id = ? 
    ORDER BY timestamp DESC 
    LIMIT 5";
$stmt = $conn->prepare($recentActivityQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$activityResult = $stmt->get_result();

// Fetch user's projects
$projectsQuery = "
    SELECT p.project_name, p.start_date, p.end_date, d.department_name 
    FROM project p 
    LEFT JOIN department d ON p.dept_id = d.department_id
    WHERE p.project_id IN (SELECT project_id FROM project_assignment WHERE employee_id = ?)";
$stmt = $conn->prepare($projectsQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$projectsResult = $stmt->get_result();

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="stylesheet" href="styles/sidebar.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include 'user_sidebar.php'; ?>


            <!-- Main Content -->
            <main class="col-md-10 px-4">
                <header class="py-3">
                    <h2>Welcome, <?php echo htmlspecialchars($userData['first_name']); ?>!</h2>
                    <p>
                        Role: <strong><?php echo htmlspecialchars($userData['role']); ?></strong> |
                        Department: <strong><?php echo htmlspecialchars($userData['department_name'] ?? 'N/A'); ?></strong> |
                        Status: <strong><?php echo htmlspecialchars($userData['Status']); ?></strong>
                    </p>
                </header>

                <!-- Dynamic Cards -->
                <div class="row text-center mb-4">
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5>Performance Score</h5>
                                <h3><?php echo htmlspecialchars($userData['performance_score']); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5>Completed Tasks</h5>
                                <h3><?php echo htmlspecialchars($tasksData['Completed'] ?? 0); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5>In Progress Tasks</h5>
                                <h3><?php echo htmlspecialchars($tasksData['In Progress'] ?? 0); ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5>Pending Tasks</h5>
                                <h3><?php echo htmlspecialchars($tasksData['Pending'] ?? 0); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Task Analytics -->
                <section class="mb-4">
                    <h3>Your Task Analytics</h3>
                    <div style="max-width: 400px; margin: auto;">
                        <canvas id="taskAnalyticsChart" width="400" height="400"></canvas>
                    </div>
                </section>


                <!-- Recent Activities -->
                <section class="mb-4">
                    <h3>Recent Activities</h3>
                    <ul class="list-group">
                        <?php while ($activity = $activityResult->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong><?php echo htmlspecialchars($activity['action']); ?></strong>
                                <span class="text-muted"> - <?php echo date("M d, Y, h:i A", strtotime($activity['timestamp'])); ?></span>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </section>

                <!-- Projects -->
                <section>
                    <h3>Your Projects</h3>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Project Name</th>
                                <th>Department</th>
                                <th>Start Date</th>
                                <th>End Date</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($project = $projectsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($project['project_name']); ?></td>
                                    <td><?php echo htmlspecialchars($project['department_name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($project['start_date']); ?></td>
                                    <td><?php echo htmlspecialchars($project['end_date']); ?></td>

                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </section>
            </main>
        </div>
    </div>

    <script>
        // Task Analytics Chart
        const ctx = document.getElementById('taskAnalyticsChart').getContext('2d');
        const taskAnalyticsChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'In Progress', 'Pending'],
                datasets: [{
                    data: [
                        <?php echo $tasksData['Completed'] ?? 0; ?>,
                        <?php echo $tasksData['In Progress'] ?? 0; ?>,
                        <?php echo $tasksData['Pending'] ?? 0; ?>
                    ],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>

</html>