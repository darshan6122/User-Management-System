<?php
require_once 'db_connection.php';

// Get search and page parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 10; // Number of logs per page
$offset = ($page - 1) * $itemsPerPage;

// Fetch audit logs with pagination and optional search
$sql = "
    SELECT 
        al.log_id, 
        al.action, 
        CONCAT(COALESCE(e.first_name, ''), ' ', COALESCE(e.last_name, '')) AS employee_name, 
        al.timestamp 
    FROM audit_log al
    LEFT JOIN employee e ON al.performed_by = e.employee_id
    WHERE al.action LIKE '%$search%' 
       OR CONCAT(COALESCE(e.first_name, ''), ' ', COALESCE(e.last_name, '')) LIKE '%$search%' 
    ORDER BY al.timestamp DESC 
    LIMIT $itemsPerPage OFFSET $offset";
$result = $conn->query($sql);

// Count total logs for pagination
$totalLogsQuery = "
    SELECT COUNT(*) AS total 
    FROM audit_log al
    LEFT JOIN employee e ON al.performed_by = e.employee_id
    WHERE al.action LIKE '%$search%' 
       OR CONCAT(COALESCE(e.first_name, ''), ' ', COALESCE(e.last_name, '')) LIKE '%$search%'";
$totalLogsResult = $conn->query($totalLogsQuery);
$totalLogs = $totalLogsResult->fetch_assoc()['total'];
$totalPages = ceil($totalLogs / $itemsPerPage);

// Generate audit logs rows
$html = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['log_id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['action']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['employee_name'] ?: 'System') . '</td>';
        $html .= '<td>' . htmlspecialchars($row['timestamp']) . '</td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td colspan="4">No logs found.</td></tr>';
}

// Generate pagination links
$pagination = '';
for ($i = 1; $i <= $totalPages; $i++) {
    $activeClass = ($i == $page) ? 'active' : '';
    $pagination .= '<a href="#" class="' . $activeClass . '" data-page="' . $i . '">' . $i . '</a>';
}

// Return JSON response
echo json_encode(['html' => $html, 'pagination' => $pagination]);
?>
