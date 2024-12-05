<link rel="stylesheet" href="styles/sidebar.css">

<?php
// Get the current page name
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<aside class="sidebar">
    <h2>User Panel</h2>
    <ul>
        <li><a href="user_dashboard.php" class="<?php echo $currentPage == 'user_dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
        <li><a href="view_tasks.php" class="<?php echo $currentPage == 'view_tasks.php' ? 'active' : ''; ?>">View Tasks</a></li>
        <li><a href="update_profile.php" class="<?php echo $currentPage == 'update_profile.php' ? 'active' : ''; ?>">Update Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</aside>
