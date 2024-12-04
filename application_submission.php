<?php
$host = "localhost"; // Your database host
$username = "root";  // Your database username
$password = "";      // Your database password
$dbname = "tesys"; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define file upload directory
$uploadDir = 'uploads/'; // Make sure this directory exists and is writable

// Function to handle file uploads
function uploadFile($file, $uploadDir) {
    $fileName = basename($file["name"]);
    $targetFile = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    
    // Check if file is a valid type (for example, allow only pdf and images)
    $validTypes = ['jpg', 'jpeg', 'png', 'pdf'];
    if (!in_array($fileType, $validTypes)) {
        return false;
    }
    
    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return $targetFile;
    } else {
        return false;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $tenant_name = $_POST['tenant_name']; // Assuming you will include a field for tenant name in the form

    // Process the files
    $letter_of_intent = uploadFile($_FILES['letter_of_intent'], $uploadDir);
    $business_profile = uploadFile($_FILES['business_profile'], $uploadDir);
    $business_registration = uploadFile($_FILES['business_registration'], $uploadDir);
    $valid_id = uploadFile($_FILES['valid_id'], $uploadDir);
    $bir_registration = uploadFile($_FILES['bir_registration'], $uploadDir);
    $financial_statement = uploadFile($_FILES['financial_statement'], $uploadDir);
    
    if ($letter_of_intent && $business_profile && $business_registration && $valid_id && $bir_registration && $financial_statement) {
        // Insert into the database
        $sql = "INSERT INTO applications (tenant_name, letter_of_intent, business_profile, business_registration, valid_id, bir_registration, financial_statement) 
                VALUES ('$tenant_name', '$letter_of_intent', '$business_profile', '$business_registration', '$valid_id', '$bir_registration', '$financial_statement')";
        
        if ($conn->query($sql) === TRUE) {
            $success_message = "Application submitted successfully!";
        } else {
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $error_message = "File upload failed. Please check your files.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submission</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .main-content {
            width: 70%;
            max-width: 800px;
            margin: 50px auto;
            background-color: rgba(255, 255, 255, 0.9); /* Slightly opaque white */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
        }
        h1 {
            color: #006400;
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 20px; /* Add space between form elements */
        }
        label {
            color: #006400;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        button {
            padding: 12px 25px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #218838;
        }
        .success-message, .error-message {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .success-message {
            color: green;
            font-weight: bold;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h1>Application Submission</h1>
        <?php if (isset($success_message) && $success_message): ?>
            <p class="success-message"><?php echo $success_message; ?></p>
        <?php endif; ?>
        <?php if (isset($error_message) && $error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="process.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="tenant_name">Tenant Name:</label>
                <input type="text" id="tenant_name" name="tenant_name" required>
            </div>

            <div>
                <label for="letter_of_intent">Letter of Intent:</label>
                <input type="file" id="letter_of_intent" name="letter_of_intent" required>
            </div>

            <div>
                <label for="business_profile">Business Profile:</label>
                <input type="file" id="business_profile" name="business_profile" required>
            </div>

            <div>
                <label for="business_registration">Business Registration:</label>
                <input type="file" id="business_registration" name="business_registration" required>
            </div>

            <div>
                <label for="valid_id">Valid ID:</label>
                <input type="file" id="valid_id" name="valid_id" required>
            </div>

            <div>
                <label for="bir_registration">BIR Registration:</label>
                <input type="file" id="bir_registration" name="bir_registration" required>
            </div>

            <div>
                <label for="financial_statement">Financial Statement:</label>
                <input type="file" id="financial_statement" name="financial_statement" required>
            </div>

            <button type="submit">Submit Application</button>
        </form>
    </div>
</body>
</html>
