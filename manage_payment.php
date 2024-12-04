<?php
session_start();
include 'db_config.php';

// Check if the user is logged in and if they are an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle delete payment request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Prepare the delete query
    $query = "DELETE FROM payments WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Payment deleted successfully!";
    } else {
        $_SESSION['error_message'] = "Failed to delete payment. Please try again.";
    }
    // Redirect to the same page to refresh the list
    header("Location: manage_payments.php");
    exit();
}

// Fetch Payment Records with Tenant Names
$query = "
    SELECT 
        payments.id AS payment_id, 
        tenants.id AS tenant_id,
        CONCAT(tenants.firstname, ' ', tenants.lastname) AS name,
        payments.amount_paid, 
        payments.payment_date, 
        payments.payment_status
    FROM payments
    JOIN tenants ON payments.tenant_id = tenants.id
";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $payments = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $payments = []; // No records found
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Payments</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7f6;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: #fff;
            padding: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar h1 {
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            margin: 15px 0;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #555;
            padding: 5px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 12px;
            font-size: 14px;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            color: #fff;
            transition: background-color 0.3s;
        }

        .btn-edit {
            background-color: #007bff;
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-add {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .btn-add:hover {
            background-color: #218838;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                padding: 10px;
            }

            .main-content {
                padding: 10px;
            }

            table {
                font-size: 14px;
            }

            .btn {
                font-size: 12px;
                padding: 6px 8px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h1>Manage Payments</h1>
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="manage_tenants.php">Manage Tenants</a>
            <a href="manage_payments.php" style="background-color: #555;">Manage Payments</a>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Payment Details</h2>
            <a href="add_payment.php" class="btn-add">Add Payment</a>

            <!-- Success/Error message -->
            <?php if (isset($_SESSION['success_message'])) : ?>
                <div style="color: green;"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error_message'])) : ?>
                <div style="color: red;"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
            <?php endif; ?>

            <table>
                <thead>
                    <tr>
                        <th>Tenant ID</th>
                        <th>Name</th>
                        <th>Payment Amount</th>
                        <th>Payment Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($payments)) : ?>
                        <?php foreach ($payments as $payment) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['tenant_id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['name']); ?></td>
                                <td>$<?php echo htmlspecialchars($payment['amount_paid']); ?></td>
                                <td><?php echo htmlspecialchars($payment['payment_date']); ?></td>
                                <td><?php echo htmlspecialchars($payment['payment_status']); ?></td>
                                <td class="action-buttons">
                                    <a href="edit_payment.php?id=<?php echo htmlspecialchars($payment['payment_id']); ?>" class="btn btn-edit">Edit</a>
                                    <a href="manage_payments.php?delete_id=<?php echo htmlspecialchars($payment['payment_id']); ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this payment?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6">No payment records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
