<?php
// Start session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once 'db_connection.php';

// Fetch all projects from the database
$projectsQuery = "SELECT project_id, project_name, start_date, end_date, dept_id FROM project";
$projectsResult = $conn->query($projectsQuery);

// Fetch all departments for the dropdown
$departmentsQuery = "SELECT department_id, department_name FROM department";
$departmentsResult = $conn->query($departmentsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Projects</title>
    <!-- Link the external CSS -->
    <link rel="stylesheet" href="styles/manage_users.css">
    <link rel="stylesheet" href="styles/manage_projects.css">
    
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include 'manager_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Manage Projects</h1>
                <!-- <button class="btn add-user" onclick="openAddModal()">Add New Project</button> -->
            </header>
            <div class="table-container">
                <table id="projectTable">
                    <thead>
                        <tr>
                            <th>Project ID</th>
                            <th>Project Name</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Department</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($projectsResult->num_rows > 0): ?>
                            <?php while ($row = $projectsResult->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['project_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dept_id']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="edit btn" onclick="openEditModal(<?php echo $row['project_id']; ?>, '<?php echo htmlspecialchars($row['project_name']); ?>', '<?php echo $row['start_date']; ?>', '<?php echo $row['end_date']; ?>', '<?php echo $row['dept_id']; ?>')">Edit</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No projects found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Adding/Editing Projects -->
    <div id="projectModal" class="modal">
        <h2 id="modalTitle">Edit Project</h2>
        <form id="projectForm" method="POST" action="manage_projects_action.php">
            <input type="hidden" id="projectId" name="project_id">
            <label for="projectName">Project Name:</label>
            <input type="text" id="projectName" name="project_name" required>

            <label for="startDate">Start Date:</label>
            <input type="date" id="startDate" name="start_date" required>

            <label for="endDate">End Date:</label>
            <input type="date" id="endDate" name="end_date">

            <label for="deptId">Department:</label>
            <select id="deptId" name="dept_id" required>
                <option value="" disabled selected>Select a department</option>
                <?php if ($departmentsResult->num_rows > 0): ?>
                    <?php while ($dept = $departmentsResult->fetch_assoc()): ?>
                        <option value="<?php echo $dept['department_id']; ?>">
                            <?php echo htmlspecialchars($dept['department_name']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php else: ?>
                    <option value="" disabled>No departments available</option>
                <?php endif; ?>
            </select>

            <button type="submit" class="btn save">Save</button>
            <button type="button" class="btn close" onclick="closeModal()">Cancel</button>
        </form>
    </div>

    <script>
        // Open modal for adding a project
        function openAddModal() {
            document.getElementById("modalTitle").textContent = "Add New Project";
            document.getElementById("projectForm").reset();
            document.getElementById("projectId").value = "";
            document.getElementById("projectModal").classList.add("active");
        }

        // Open modal for editing a project
        function openEditModal(id, name, start, end, dept) {
            document.getElementById("modalTitle").textContent = "Edit Project";
            document.getElementById("projectId").value = id;
            document.getElementById("projectName").value = name;
            document.getElementById("startDate").value = start;
            document.getElementById("endDate").value = end;
            document.getElementById("deptId").value = dept;
            document.getElementById("projectModal").classList.add("active");
        }

        // Close modal
        function closeModal() {
            document.getElementById("projectModal").classList.remove("active");
        }

        // Confirm deletion of a project
        function deleteProject(id) {
            if (confirm("Are you sure you want to delete this project?")) {
                window.location.href = `delete_project.php?id=${id}`;
            }
        }

        // Ensure modal is hidden on reload
        document.addEventListener("DOMContentLoaded", function () {
            closeModal();
        });
    </script>
</body>
</html>
