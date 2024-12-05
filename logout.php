<?php
require_once 'db_connection.php';
require_once 'log_function.php';

session_start(); // Start the session

$adminId = $_SESSION['employee_id'];
$action = "Logged Out";
logAction($conn, $action, $adminId, $adminId);

// Unset all session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: login.html");
exit;
