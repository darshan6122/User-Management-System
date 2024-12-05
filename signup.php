<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
require('db_connection.php');

// Create a connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch and sanitize inputs
    $ssn = isset($_POST['ssn']) ? $conn->real_escape_string($_POST['ssn']) : '';
    $username = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';
    $first_name = isset($_POST['first-name']) ? $conn->real_escape_string($_POST['first-name']) : '';
    $last_name = isset($_POST['last-name']) ? $conn->real_escape_string($_POST['last-name']) : '';
    $address = isset($_POST['address']) ? $conn->real_escape_string($_POST['address']) : '';
    // $role = isset($_POST['role']) ? $conn->real_escape_string($_POST['role']) : 'User';

    // Validate required fields
    if (empty($ssn) || empty($username) || empty($password) || empty($first_name) || empty($last_name) || empty($address)) {
        die("Error: All fields are required.");
    }

    // Check if the username already exists
    $checkQuery = "SELECT username FROM employee WHERE username = '$username'";
    $result = $conn->query($checkQuery);

    if ($result === false) {
        die("Error checking username: " . $conn->error);
    }

    if ($result->num_rows > 0) {
        die("Error: Username already exists. Please choose a different username.");
    } else {
        // Insert data into the employee table
        $insertQuery = "INSERT INTO employee (SSN, username, password, first_name, last_name, `add`, role)
                        VALUES ('$ssn', '$username', '$password', '$first_name', '$last_name', '$address', 'User')";

        if ($conn->query($insertQuery) === TRUE) {
            echo "Signup successful! <a href='login.html'>Log in here</a>";
        } else {
            die("Error inserting data: " . $conn->error);
        }
    }
} else {
    echo "No data submitted.";
}

// Close the connection
$conn->close();
?>
