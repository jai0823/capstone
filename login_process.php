<?php
session_start();
include 'db_config.php'; // Include database connection

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_role = $_POST['user_role']; // Get user role from form
    
    // Prepare SQL query based on user role (validate user role)
    if ($user_role === 'tenant') {
        $table = 'tenants';
    } elseif ($user_role === 'admin') {
        $table = 'admins';
    } else {
        die("Invalid user role.");
    }

    // Query to fetch user data
    $query = "SELECT * FROM $table WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username); // Bind username parameter
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 0) {
        die("User not found.");
    }

    // Fetch user data
    $user = $result->fetch_assoc();

    // Verify password
    if (!password_verify($password, $user['password'])) {
        die("Incorrect password.");
    }

    // Regenerate session ID to prevent session fixation attacks
    session_regenerate_id(true);

    // Store user info in session
    $_SESSION['user_id'] = $user['id']; // Store user ID in session
    $_SESSION['username'] = $user['username']; // Store username in session
    $_SESSION['user_role'] = $user_role; // Store user role in session

    // Redirect based on user role
    if ($user_role === 'admin') {
        // Admin redirect to admin dashboard
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($user_role === 'tenant') {
        // Tenant redirect to tenant dashboard
        header("Location: tenant_dashboard.php");
        exit();
    }
} else {
    // If form isn't submitted, redirect to login page
    header("Location: login.php");
    exit();
}
?>
