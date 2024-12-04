<?php
session_start();

// Database connection
function get_db_connection() {
    $servername = "localhost"; // Replace with your server name
    $username = "root";        // Replace with your database username
    $password = "";            // Replace with your database password
    $dbname = "tesys";         // Database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Check if user is logged in and is a tenant
function check_login_status() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'tenant') {
        header("Location: login.php");
        exit();
    }
}

check_login_status(); // Ensure user is logged in

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $documents = [
        'letter_of_intent' => $_FILES['letter_of_intent']['tmp_name'],
        'business_profile' => $_FILES['business_profile']['tmp_name'],
        'business_registration' => $_FILES['business_registration']['tmp_name'],
        'valid_id' => $_FILES['valid_id']['tmp_name'],
        'bir_registration' => $_FILES['bir_registration']['tmp_name'],
        'financial_statement' => $_FILES['financial_statement']['tmp_name']
    ];

    $file_contents = [];
    foreach ($documents as $key => $tmp_path) {
        if (file_exists($tmp_path)) {
            $file_contents[$key] = file_get_contents($tmp_path);
        } else {
            $error_message = "Error uploading the $key file.";
            break;
        }
    }

    if (empty($error_message)) {
        $conn = get_db_connection();

        // Prepare SQL query
        $query = "INSERT INTO tenant_applications 
                  (tenant_name, letter_of_intent, business_profile, business_registration, valid_id, bir_registration, financial_statement, application_sub) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($query);

        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }

        // Bind parameters
        $tenant_name = $_SESSION['user_id']; // Assuming 'user_id' stores tenant name or identifier
        $stmt->bind_param(
            "sssssss", 
            $tenant_name,
            $file_contents['letter_of_intent'],
            $file_contents['business_profile'],
            $file_contents['business_registration'],
            $file_contents['valid_id'],
            $file_contents['bir_registration'],
            $file_contents['financial_statement']
        );

        // Execute the statement
        if ($stmt->execute()) {
            $success_message = "Application submitted successfully!";
        } else {
            $error_message = "Error submitting your application: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submission</title>
</head>
<body>
    <h1>Application Submission Status</h1>
    
    <?php if ($success_message): ?>
        <p style="color:green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <?php if ($error_message): ?>
        <p style="color:red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <a href="application_submission.php">Go Back to Form</a>
</body>
</html>
