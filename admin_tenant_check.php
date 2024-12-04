<?php
require_once 'db_config.php'; // Database connection

// Search functionality
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Query to fetch all tenants
$sql = "SELECT * FROM tenants";
if (!empty($search)) {
    $sql .= " WHERE username LIKE '%" . $conn->real_escape_string($search) . "%'";
}

$result = $conn->query($sql);

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM tenants WHERE id = " . (int)$delete_id;
    
    if ($conn->query($delete_sql) === TRUE) {
        echo "Record deleted successfully.";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    
    // Redirect back to the page after deletion
    header("Location: admin_tenant_check.php");
    exit();
}

// Handle clear search
if (isset($_GET['clear_search'])) {
    // Redirect to the same page without search
    header("Location: admin_tenant_check.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Tenants</title>
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('img/bg.jpg'); /* Replace with your desired background image URL */
            background-size: cover;
            background-position: center;
            margin: 0;
            padding: 0;
            color: #fff;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            margin-bottom: 30px;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 10px;
            width: 70%;
            border: 2px solid #fff;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            font-size: 16px;
        }

        button {
            padding: 10px 20px;
            background-color: #f1c40f;
            border: none;
            border-radius: 5px;
            color: #333;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #e67e22;
        }

        .clear-btn {
            background-color: #3498db;
            color: #fff;
        }

        .clear-btn:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: fadeIn 1s ease-out;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: #fff;
        }

        td {
            background-color: #34495e;
        }

        .delete-btn {
            color: #e74c3c;
            cursor: pointer;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .delete-btn:hover {
            color: #c0392b;
        }

        /* Animation */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Registered Tenants</h1>
        <div class="form-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by username" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
            <!-- Clear Search Button -->
            <form method="GET" action="">
                <button type="submit" name="clear_search" class="clear-btn">Clear Search</button>
            </form>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td>
                                <a href="admin_tenant_check.php?delete_id=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this tenant?')">X</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No tenants found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
