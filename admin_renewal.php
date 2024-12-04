<?php
session_start();
include 'db_config.php';

// Fetch lease renewal requests
$query = "
    SELECT 
        tenants.firstname, 
        tenants.lastname, 
        tenants.id, 
        renewal_requests.request_date, 
        renewal_requests.status 
    FROM renewal_requests
    JOIN tenants ON renewal_requests.id = tenants.id
";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $requests = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $requests = []; // No data found
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Review Lease Renewal Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #212529;
        }
        header {
            background-color: #007bff;
            padding: 1rem 2rem;
            color: white;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        main {
            padding: 2rem;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 1rem;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        th {
            background-color: #007bff;
            color: white;
            font-weight: 500;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .action-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 0.5rem;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
        .no-data {
            margin-top: 2rem;
            text-align: center;
            color: #6c757d;
            font-size: 1.2rem;
        }
        .btn {
            padding: 0.5rem 1rem;
            margin: 0.5rem;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<header>
    <h1>Lease Renewal Requests</h1>
</header>

<main>
    <!-- Lease renewal requests table -->
    <table>
        <thead>
            <tr>
                <th>Tenant Name</th>
                <th>Unit Number</th>
                <th>Requested Renewal Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($requests)) : ?>
                <?php foreach ($requests as $request) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['firstname'] . ' ' . $request['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($request['unit_number']); ?></td>
                        <td><?php echo htmlspecialchars($request['requested_renewal_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                        <td class="action-links">
                            <a href="#" class="btn">Approve</a>
                            <a href="#" class="btn" style="background-color: #dc3545;">Reject</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="no-data">No renewal requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

</body>
</html>
