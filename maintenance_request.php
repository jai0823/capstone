<?php
// Start the session to store success message
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'tesys');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenant_name = $_POST['tenant_name'];
    $unit_number = $_POST['unit_number'];
    $issue_description = $_POST['issue_description'];
    $category = $_POST['category'];
    $urgency = $_POST['urgency'];

    // Handle file uploads
    $uploadedPhotos = [];
    if (!empty($_FILES['photos']['name'][0])) {
        foreach ($_FILES['photos']['name'] as $key => $filename) {
            $tempPath = $_FILES['photos']['tmp_name'][$key];
            $newFileName = 'uploads/' . uniqid() . '_' . basename($filename);
            if (move_uploaded_file($tempPath, $newFileName)) {
                $uploadedPhotos[] = $newFileName;
            }
        }
    }
    $photos = implode(',', $uploadedPhotos);

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO maintenance_requests (tenant_name, unit_number, request_description, category, urgency, photos) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param('ssssss', $tenant_name, $unit_number, $issue_description, $category, $urgency, $photos);

    if ($stmt->execute()) {
        // Set success message in session
        $_SESSION['success_message'] = "Request submitted successfully!";
        header('Location: maintenance_request.php'); // Redirect to the same page to show the message
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Maintenance Request</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f8f9fa; }
        .container { max-width: 600px; margin: 50px auto; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { text-align: center; }
        form input, form textarea, form select, form button { width: 100%; margin-bottom: 10px; padding: 10px; border-radius: 5px; border: 1px solid #ccc; }
        button { background: #4CAF50; color: white; border: none; cursor: pointer; }
        button:hover { background: #45a049; }
        .success-message { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Submit Maintenance Request</h1>
        
        <?php
        // Check if the success message exists and display it
        if (isset($_SESSION['success_message'])) {
            echo '<div class="success-message">' . $_SESSION['success_message'] . '</div>';
            // Clear the success message after displaying it
            unset($_SESSION['success_message']);
        }
        ?>
        
        <!-- Form Action updated -->
        <form method="post" action="maintenance_request.php" enctype="multipart/form-data">
            <input type="text" name="tenant_name" placeholder="Tenant Name" required>
            <input type="text" name="unit_number" placeholder="Unit Number" required>
            <textarea name="issue_description" placeholder="Describe the issue..." required></textarea>
            <select name="category" required>
                <option value="">Select Category</option>
                <option value="Electrical">Electrical</option>
                <option value="Plumbing">Plumbing</option>
                <option value="Air Conditioning">Air Conditioning</option>
            </select>
            <select name="urgency" required>
                <option value="">Select Urgency</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
            <input type="file" name="photos[]" multiple>
            <button type="submit">Submit Request</button>
        </form>
    </div>
</body>
</html>
