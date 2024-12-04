<?php
// manage_requests.php
// Sample database connection
$conn = new mysqli("localhost", "root", "", "tesys");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize a success message variable
$message = "";

// Function to delete a request
function deleteRequest($id) {
    global $conn;
    // Use a prepared statement for secure deletion
    $stmt = $conn->prepare("DELETE FROM maintenance_requests WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Function to update the request status
function updateRequestStatus($id, $status) {
    global $conn;
    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE maintenance_requests SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}

// Handle form submissions (Delete or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        deleteRequest($id);  // Call delete function
        $message = "Delete successfully!"; // Set success message
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $status = $_POST['status'];
        updateRequestStatus($id, $status);  // Call update function
        $message = "Update successfully!";  // Set success message
    }
}

// Fetch all requests
$result = $conn->query("SELECT * FROM maintenance_requests");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Requests</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-danger {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-primary {
            background-color: #007bff;
            color: #fff;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Requests</h1>

        <!-- Display success message -->
        <?php if ($message): ?>
            <div class="message"><?= $message; ?></div>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tenant Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id']; ?></td>
                    <td><?= htmlspecialchars($row['tenant_name']); ?></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <select name="status">
                                <option value="Pending" <?= $row['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Approved" <?= $row['status'] === 'Approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="Rejected" <?= $row['status'] === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <button type="submit" name="update" class="btn btn-primary">Update</button>
                        </form>
                    </td>
                    <td>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $row['id']; ?>">
                            <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
