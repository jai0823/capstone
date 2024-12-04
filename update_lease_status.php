<?php
session_start();
include 'db_config.php';

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Function to update lease status
function updateLeaseStatus($tenant_id, $new_status) {
    global $conn;

    // Validate new_status value
    if (!in_array($new_status, ['Active', 'Expired', 'Pending'])) {
        return false; // Invalid status
    }

    $query = "UPDATE tenants SET lease_status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_status, $tenant_id);

    // Execute the query
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Check if the lease status update form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_lease_status'])) {
    $tenant_id = $_POST['tenant_id'];
    $new_status = $_POST['lease_status'];

    // Call the function to update lease status
    if (updateLeaseStatus($tenant_id, $new_status)) {
        echo "Lease status updated successfully!";
    } else {
        echo "Failed to update lease status.";
    }
}

// Fetch tenant data for the dashboard
$tenants_query = "SELECT id, firstname, lastname, lease_status FROM tenants";
$tenants_result = $conn->query($tenants_query);
$tenants = $tenants_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tenants</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .dashboard-container {
            display: flex;
            flex-direction: row;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
        }

        .sidebar h1 {
            margin-top: 0;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #444;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #555;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        table th {
            background-color: #f4f4f4;
        }

        select, button {
            padding: 5px 10px;
            margin-top: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Media Queries for responsiveness */
        @media (max-width: 768px) {
            .dashboard-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 10px;
            }

            .main-content {
                padding: 10px;
            }

            table th, table td {
                font-size: 14px;
                padding: 8px;
            }

            select, button {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                font-size: 14px;
            }

            h2 {
                font-size: 18px;
            }

            table th, table td {
                font-size: 12px;
                padding: 6px;
            }

            select, button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1>Manage Tenants</h1>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_tenants.php">Manage Tenants</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Tenant List</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Lease Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tenants as $tenant): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($tenant['id']); ?></td>
                        <td><?php echo htmlspecialchars($tenant['firstname'] . ' ' . $tenant['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($tenant['lease_status']); ?></td>
                        <td>
                            <!-- Lease Status Update Form -->
                            <form method="post" action="">
                                <input type="hidden" name="tenant_id" value="<?php echo htmlspecialchars($tenant['id']); ?>">
                                <select name="lease_status" required>
                                    <option value="Active" <?php echo ($tenant['lease_status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="Expired" <?php echo ($tenant['lease_status'] == 'Expired') ? 'selected' : ''; ?>>Expired</option>
                                    <option value="Pending" <?php echo ($tenant['lease_status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                </select>
                                <button type="submit" name="update_lease_status">Update Lease Status</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
