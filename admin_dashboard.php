<?php
session_start();
include 'db_config.php';

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch admin details for personalization
$admin_id = $_SESSION['user_id'];
$query = "SELECT firstname, lastname FROM admins WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();

// Fetch tenant list from the database
$tenants_query = "SELECT id, firstname, lastname, email, phone FROM tenants ORDER BY lastname ASC";
$tenants_result = $conn->query($tenants_query);
$tenants = $tenants_result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .dashboard-container {
            display: flex;
            height: 100vh;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 20%;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        /* Header Section Styling */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            background-color: rgba(0, 0, 0, 0.7);
        }
        .header h1 {
            font-size: 36px; /* Increased size for "TMS" */
            font-weight: bold;
            margin: 0;
            color: #fff;
        }
        .profile-container {
            display: flex;
            align-items: center;
        }
        .profile-container .material-icons {
            font-size: 30px;
            margin-right: 10px;
        }
        .profile-name {
            font-size: 18px;
            color: #fff;
            font-weight: 400;
        }

        @keyframes moveText {
            0% {
                left: 100%;
            }
            100% {
                left: -100%;
            }
        }

        /* Sidebar Links */
        .sidebar a, .dropdown-btn {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            margin-bottom: 15px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
            font-weight: 500;
        }

        /* Hover Effect for Sidebar Links */
        .sidebar a:hover, .dropdown-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.05);
        }

        /* Sidebar Dropdown */
        .dropdown {
            position: relative;
        }
        .dropdown-content {
            display: none;
            flex-direction: column;
            background-color: rgba(0, 0, 0, 0.9);
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }
        .dropdown:hover .dropdown-content {
            display: flex;
        }
        .dropdown-content a {
            padding: 8px;
            color: white;
            text-decoration: none;
            margin-bottom: 5px;
            border-radius: 5px;
            font-size: 14px;
        }
        .dropdown-content a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Sidebar Icon Styling */
        .sidebar .material-icons {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Main Content Styling */
        .main-content {
            flex: 1;
            background-color: #e0f7e0; /* Light Green */
            padding: 20px;
            overflow-y: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h1 {
            color: #333;
            font-size: 28px;
        }

        /* Button Styles for Links */
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            font-size: 16px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            cursor: pointer;
        }

        /* Hover Effect for Buttons */
        button:hover {
            background-color: #45a049;
            transform: scale(1.05);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 14px;
        }
        table th {
            background-color: #4CAF50;
            color: white;
        }

        /* Content Display */
        .content {
            display: none;
        }
        #tenant-list {
            display: none;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="header">
                <h1>TMS</h1>
                <div class="profile-container">
                    <span class="material-icons">account_circle</span>
                    <div class="profile-name">
                        <?php echo htmlspecialchars($admin['firstname']) . " " . htmlspecialchars($admin['lastname']); ?>
                    </div>
                </div>
            </div>
            <a href="admin_tenant_check.php"><span class="material-icons">account_circle</span>Account Check</a>
            <div class="dropdown">
    <button class="dropdown-btn">
        <span class="material-icons">home</span>Tenant Info ▼
    </button>
    <div class="dropdown-content">
        <a href="#" onclick="showTenantList()">Tenant List</a>
        <a href="edit_tenant.php">Edit Page</a> <!-- Link to Edit Page -->
        <a href="manage_requirments.php">manage requirments</a>
    
    </div>
</div>
<a href="maintenance.php"><span class="material-icons">build</span>Maintenance</a>
<div class="dropdown">
    <button class="dropdown-btn">
        <span class="material-icons">folder_special</span> Manage Lease ▼
    </button>
    <div class="dropdown-content">
        <a href="manage_requests.php">Manage Requests</a>
        <a href="manage_space.php"><span class="material-icons">home</span>Manage Space</a> <!-- Newly added link -->
        <a href="update_lease_status.php">Lease Status</a>


        <a href="generate_report.php">Generate Reports</a>
        <a href="manage_payment.php">Manage Payment</a>
    </div>
</div>
<a href="send_announcement.php"><span class="material-icons">announcement</span>Send Announcement</a>
<a href="admin_renewal.php"><span class="material-icons">update</span>Process Renewals</a>
<a href="chat.php"><span class="material-icons">chat</span>Messaging</a>
<a href="admin_register.php"><span class="material-icons">chat</span>Registration</a>
<a href="logout.php"><span class="material-icons">logout</span>Logout</a>

        </div>
        

        <!-- Main Content -->
        <div class="main-content">
            <div id="tenant-list" class="content">
                <h2>Tenant List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tenants as $tenant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($tenant['id']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['firstname']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['lastname']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['email']); ?></td>
                            <td><?php echo htmlspecialchars($tenant['phone']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showTenantList() {
            document.querySelectorAll('.content').forEach(content => content.style.display = 'none');
            document.getElementById('tenant-list').style.display = 'block';
        }
    </script>
</body>
</html>
