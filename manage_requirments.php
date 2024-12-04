<?php
session_start();
include 'db_config.php';

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch tenant submissions from the database
$query = "
    SELECT 
        tenant_applications.id, 
        tenants.firstname, 
        tenants.lastname, 
        tenant_applications.tenant_name, 
        tenant_applications.letter_of_intent, 
        tenant_applications.business_profile, 
        tenant_applications.business_registration, 
        tenant_applications.valid_id, 
        tenant_applications.bir_registration, 
        tenant_applications.financial_statement, 
        tenant_applications.submitted_at AS created_at,  -- Replace with actual column name
        tenant_applications.status
    FROM 
        tenant_applications
    JOIN 
        tenants 
    ON 
        tenant_applications.tenant_name = tenants.username
    ORDER BY 
        tenant_applications.submitted_at uploads -- Replace with actual column name
";

$result = $conn->query($query);

// Check for query errors
if (!$result) {
    die("Query failed: " . $conn->error);
}

// Fetch results as an associative array
$requirements = [];
if ($result->num_rows > 0) {
    $requirements = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tenant Requirements</title>
    <style>
        /* Add your styles here */
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Tenant Requirements</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Submitted By</th>
                    <th>Letter of Intent</th>
                    <th>Business Profile</th>
                    <th>Business Registration</th>
                    <th>Valid ID</th>
                    <th>BIR Registration</th>
                    <th>Financial Statement</th>
                    <th>Submitted At</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requirements)): ?>
                    <?php foreach ($requirements as $requirement): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($requirement['id']); ?></td>
                        <td><?php echo htmlspecialchars($requirement['firstname'] . ' ' . $requirement['lastname']); ?></td>
                        <td>
                            <?php if ($requirement['letter_of_intent']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($requirement['letter_of_intent']); ?>" alt="Letter of Intent" onclick="openModal(this.src)">
                            <?php else: ?>
                                <p>Missing file</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($requirement['business_profile']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($requirement['business_profile']); ?>" alt="Business Profile" onclick="openModal(this.src)">
                            <?php else: ?>
                                <p>Missing file</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($requirement['business_registration']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($requirement['business_registration']); ?>" alt="Business Registration" onclick="openModal(this.src)">
                            <?php else: ?>
                                <p>Missing file</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($requirement['valid_id']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($requirement['valid_id']); ?>" alt="Valid ID" onclick="openModal(this.src)">
                            <?php else: ?>
                                <p>Missing file</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($requirement['bir_registration']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($requirement['bir_registration']); ?>" alt="BIR Registration" onclick="openModal(this.src)">
                            <?php else: ?>
                                <p>Missing file</p>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($requirement['financial_statement']): ?>
                                <img src="uploads/<?php echo htmlspecialchars($requirement['financial_statement']); ?>" alt="Financial Statement" onclick="openModal(this.src)">
                            <?php else: ?>
                                <p>Missing file</p>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($requirement['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($requirement['status']); ?></td>
                        <td class="actions">
                            <button class="approve" onclick="approveRequirement(<?php echo $requirement['id']; ?>)">Approve</button>
                            <button class="delete" onclick="deleteRequirement(<?php echo $requirement['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">No requirements found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script>
        function openModal(src) {
            alert("Image preview not implemented. Path: " + src);
        }

        function approveRequirement(id) {
            if (confirm('Are you sure you want to approve this requirement?')) {
                window.location.href = `approve_requirement.php?id=${id}`;
            }
        }

        function deleteRequirement(id) {
            if (confirm('Are you sure you want to delete this requirement?')) {
                window.location.href = `delete_requirement.php?id=${id}`;
            }
        }
    </script>
</body>
</html>
