<?php
require_once 'db_connection.php';

// Get search and page parameters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 10; // Number of employees per page
$offset = ($page - 1) * $itemsPerPage;

// Fetch employees with pagination and optional search
$sql = "SELECT employee_id, username, first_name, last_name, role, Status, performance_score 
        FROM employee 
        WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR username LIKE '%$search%' 
        LIMIT $itemsPerPage OFFSET $offset";
$result = $conn->query($sql);

// Count total employees for pagination
$totalEmployeesQuery = "SELECT COUNT(*) AS total FROM employee 
                        WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR username LIKE '%$search%'";
$totalEmployeesResult = $conn->query($totalEmployeesQuery);
$totalEmployees = $totalEmployeesResult->fetch_assoc()['total'];
$totalPages = ceil($totalEmployees / $itemsPerPage);

// Generate employee rows
$html = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['employee_id']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['username']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['first_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['last_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['role']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['Status']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['performance_score']) . '</td>';
        $html .= '<td><button class="btn edit" onclick="openModal(' . $row['employee_id'] . ', ' . $row['performance_score'] . ')">Edit</button></td>';
        $html .= '</tr>';
    }
} else {
    $html .= '<tr><td colspan="8">No employees found.</td></tr>';
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
