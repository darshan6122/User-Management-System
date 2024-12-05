<?php

require_once 'utils.php'; // Adjust the path as needed

// Now you can use the function
$userIP = getUserIP();


function logAction($conn, $action, $employeeId = null, $performedBy = null) {
    $ipAddress = getUserIP(); // Capture IP address of the user
    $query = "INSERT INTO audit_log (action, employee_id, performed_by, ip_address, timestamp) 
              VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        die("Error preparing log query: " . $conn->error);
    }

    $stmt->bind_param('siis', $action, $employeeId, $performedBy, $ipAddress);

    if ($stmt->execute() === false) {
        die("Error executing log query: " . $stmt->error);
    }

    $stmt->close();
}
?>
