<?php
session_start();

include('db_config.php');

// Execute the query and store the result in $result
$sql = "SELECT * FROM tenants";
$result = mysqli_query($conn, $sql);  // Assuming $conn is your database connection variable

$tenants = [];
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tenants[] = $row;
    }
} else {
    $tenants = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Manager - Xentro Mall Calapan</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: Arial, sans-serif;
        }

        /* Background */
        body {
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.5);
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
            text-align: center;
            position: relative;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: start;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .sidebar ul li a:hover {
            background-color: #4CAF50;
            transform: translateX(5px);
        }

        .sidebar ul li a i {
            margin-right: 15px;
        }

        /* Dropdown styles for the settings menu */
        .sidebar ul li .dropdown-content {
            display: none;
            position: absolute;
            background-color: rgba(0, 0, 0, 0.9);
            min-width: 200px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .sidebar ul li:hover .dropdown-content {
            display: block;
        }

        .sidebar ul li .dropdown-content a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li .dropdown-content a:hover {
            background-color: #4CAF50;
        }

        /* Main content */
        .main-content {
            margin-left: 250px;
            padding: 20px;
            color: #333;
        }

        .main-content h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Table Design */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        /* Button styles */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .main-content {
            animation: fadeIn 1s ease-in-out;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Admin Manager</h2>
        <ul>
            <li><a href="javascript:void(0)" onclick="loadContent('tenantInfo')"><i>👤</i> Tenant Information</a></li>
            <li><a href="javascript:void(0)" onclick="loadContent('notifications')"><i>🔔</i> Notifications</a></li>
            <li><a href="javascript:void(0)" onclick="loadContent('updateTenantInfo')"><i>✏️</i> View/Update Tenant Info</a></li>
            <li><a href="javascript:void(0)" onclick="loadContent('leaseAgreements')"><i>📄</i> Manage Lease Agreements</a></li>
            <li><a href="javascript:void(0)" onclick="loadContent('announcements')"><i>📢</i> Send Announcements</a></li>
            <li><a href="javascript:void(0)" onclick="loadContent('reminders')"><i>⏰</i> Reminders to Tenants</a></li>
            <li>
                <a href="javascript:void(0)" onclick="loadContent('settings')"><i>🔧</i> Settings</a>
                <div class="dropdown-content">
                    <a href="javascript:void(0)" onclick="loadContent('generalSettings')">General Settings</a>
                    <a href="javascript:void(0)" onclick="loadContent('accountSettings')">Account Settings</a>
                    <a href="javascript:void(0)" onclick="loadContent('privacySettings')">Privacy Settings</a>
                </div>
            </li>
            <a href="logout.php" class="btn">Logout</a>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h1>Welcome Admin</h1>
        <div id="contentArea">
            <p>Select an option from the sidebar to manage your admin tasks.</p>
        </div>
    </div>

    <!-- JavaScript to load content -->
    <script>
        function loadContent(page) {
            let content = '';
            switch(page) {
                case 'tenantInfo':
                    content = '<h2>Tenant Information</h2><table><tr><th>ID</th><th>Name</th><th>Email</th><th>Lease Status</th><th>Action</th></tr>';
                    <?php foreach($tenants as $tenant): ?>
                    content += `<tr><td><?php echo $tenant['id']; ?></td><td><?php echo $tenant['name']; ?></td><td><?php echo $tenant['email']; ?></td><td><?php echo $tenant['lease_status']; ?></td><td><a href="edit.php?id=<?php echo $tenant['id']; ?>" class="btn">Edit</a></td></tr>`;
                    <?php endforeach; ?>
                    content += '</table>';
                    break;
                case 'settings':
                    content = '<h2>Settings</h2><p>Select a category to manage your settings.</p>';
                    break;
                case 'generalSettings':
                    content = `
                        <h2>General Settings</h2>
                        <form>
                            <label for="siteName">Site Name:</label><br>
                            <input type="text" id="siteName" name="siteName" value="Xentro Mall"><br><br>
                            <label for="siteTheme">Theme:</label><br>
                            <select id="siteTheme" name="siteTheme">
                                <option value="light">Light</option>
                                <option value="dark">Dark</option>
                            </select><br><br>
                            <button class="btn" type="submit">Save Settings</button>
                        </form>`;
                    break;
                case 'accountSettings':
                    content = `
                        <h2>Account Settings</h2>
                        <form>
                            <label for="username">Username:</label><br>
                            <input type="text" id="username" name="username" value="admin"><br><br>
                            <label for="email">Email:</label><br>
                            <input type="email" id="email" name="email" value="admin@example.com"><br><br>
                            <label for="password">Password:</label><br>
                            <input type="password" id="password" name="password"><br><br>
                            <button class="btn" type="submit">Update Account</button>
                        </form>`;
                    break;
                case 'privacySettings':
                    content = `
                        <h2>Privacy Settings</h2>
                        <form>
                            <label for="dataSharing">Data Sharing:</label><br>
                            <input type="checkbox" id="dataSharing" name="dataSharing" checked> Allow data sharing<br><br>
                            <label for="notifications">Email Notifications:</label><br>
                            <input type="checkbox" id="notifications" name="notifications" checked> Receive email notifications<br><br>
                            <button class="btn" type="submit">Save Privacy Settings</button>
                        </form>`;
                    break;
                default:
                    content = '<p>Welcome to the admin dashboard.</p>';
            }
            document.getElementById('contentArea').innerHTML = content;
        }
    </script>
</body>
</html>
