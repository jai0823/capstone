<?php
session_start();

// Database connection function
function get_db_connection() {
    include 'db_config.php';
    return $conn;
}

// Check if user is logged in and is a tenant
function check_login_status() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'tenant') {
        header("Location: login.php"); // Redirect to login if not a tenant
        exit();
    }
}

// Fetch tenant data from the database
function get_tenant_data($tenant_id) {
    $conn = get_db_connection();
    $query = "SELECT firstname, lastname, email, phone FROM tenants WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Fetch payment data (if needed)
function get_payments($tenant_id) {
    $conn = get_db_connection();
    $query = "SELECT * FROM payments WHERE tenant_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC); // Return all payments for the tenant
}

// Fetch maintenance requests (if needed)
function get_maintenance_requests($id) {
    $conn = get_db_connection();
    $query = "SELECT * FROM maintenance_requests WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC); // Return all maintenance requests for the tenant
}

check_login_status(); // Ensure user is logged in and a tenant

$id = $_SESSION['user_id'];
$tenant = get_tenant_data($id);
$payments = get_payments($id); // You can use this data wherever needed
$maintenance_requests = get_maintenance_requests($id); // You can use this data wherever needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"> <!-- Google Font -->
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #2b5876, #4e4376); /* Gradient background */
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .dashboard-container {
            display: flex;
            width: 100%;
            height: 100vh;
            max-height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            width: 270px;
            background-color: #333; /* Dark sidebar */
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #fff;
            box-shadow: 4px 0 12px rgba(0, 0, 0, 0.1);
        }
        .sidebar img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .sidebar h2 {
            font-size: 22px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
        }
        .sidebar button {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            background-color: #00796b;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
            display: flex;
            align-items: center;
        }
        .sidebar button:hover {
            background-color: #004d40;
            transform: translateY(-5px); /* Button hover effect */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Add shadow */
        }
        .sidebar button .material-icons {
            margin-right: 10px;
        }
        .sidebar .logout {
            margin-top: auto;
            background-color: #d32f2f;
        }
        .sidebar .logout:hover {
            background-color: #b71c1c;
            transform: translateY(-5px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .main-content {
            flex: 1;
            padding: 30px;
            background-color: #f4f6f7;
            overflow-y: auto;
            box-shadow: inset 0 0 30px rgba(0, 0, 0, 0.1);
        }
        .main-content h1 {
            font-size: 36px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .main-content p {
            font-size: 18px;
            color: #666;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        /* For animated sliding effect from the left */
        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            opacity: 0; /* Initially hidden */
            transform: translateX(-100%); /* Start from left off-screen */
            animation: slideInLeft 0.8s ease-out forwards; /* Animation for sliding in */
        }

        @keyframes slideInLeft {
            0% {
                opacity: 0;
                transform: translateX(-100%); /* Start off-screen on the left */
            }
            100% {
                opacity: 1;
                transform: translateX(0); /* End at original position */
            }
        }

        /* Green Color Styling */
        .card h3 {
            font-size: 24px;
            color: #388e3c; /* Green color */
            margin-bottom: 15px;
            font-weight: 600;
        }

        .card p {
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <img src="img/profile.jpg" alt="profile">
            <h2>Welcome, <?php echo htmlspecialchars($tenant['firstname'] . ' ' . $tenant['lastname']); ?></h2>
            <button onclick="location.href='application_submission.php'">
                <span class="material-icons">assignment</span>Application Submission
            </button>
            <button onclick="location.href='payment.php'">
                <span class="material-icons">payment</span>Payment
            </button>
            <button onclick="location.href='notifications.php'">
                <span class="material-icons">notifications</span>Notification
            </button>
            <button onclick="location.href='maintenance_request.php'">
                <span class="material-icons">build</span>Maintenance Requests
            </button>
            <button onclick="location.href='Renewal Request Page.php'">
                <span class="material-icons">autorenew</span>Renewal
            </button>
            <button onclick="location.href='message_chat.php'">
                <span class="material-icons">autorenew</span>chat
            </button>
            <button class="logout" onclick="location.href='logout.php'">
                <span class="material-icons">exit_to_app</span>Logout
            </button>
        </div>
       
        </div>
    </div>
</body>
</html>
