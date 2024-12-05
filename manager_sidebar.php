<link rel="stylesheet" href="styles/sidebar.css">


<!-- sidebar.php -->
<aside class="sidebar">
    <h2>Manager Panel</h2>
    <ul>
        <li><a href="manager_dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manager_dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
        <li><a href="view_employees.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'view_employees.php' ? 'active' : ''; ?>">View Employees</a></li>
        <li><a href="manage_projects.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'manage_projects.php' ? 'active' : ''; ?>">Manage Projects</a></li>
        <li><a href="view_audit_logs.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'audit_logs.php' ? 'active' : ''; ?>">View Audit Logs</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</aside>
