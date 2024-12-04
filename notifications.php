<?php
// Connection to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tesys";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all announcements
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
    <style>
        /* Global Styles */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f9fbfd;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            background-color: #1e3a8a;
            color: white;
            width: 280px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 30px 15px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
        }

        .sidebar h2 {
            margin-bottom: 40px;
            font-size: 24px;
            text-align: center;
            font-weight: bold;
        }

        .menu a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: white;
            margin: 10px 0;
            padding: 12px 20px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .menu a:hover {
            background-color: #2563eb;
        }

        .menu i {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Main Content */
        .main {
            margin-left: 280px;
            padding: 40px;
            width: calc(100% - 280px);
            transition: margin-left 0.3s, width 0.3s;
        }

        .header h1 {
            margin-bottom: 30px;
            font-size: 28px;
            color: #1e293b;
        }

        .notifications {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .notification-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: calc(33.33% - 20px);
            min-width: 300px;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .notification-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .notification-card h2 {
            font-size: 20px;
            margin: 0 0 10px;
            color: #2563eb;
        }

        .notification-card p {
            font-size: 16px;
            color: #475569;
            margin: 0 0 10px;
        }

        .notification-card small {
            font-size: 14px;
            color: #94a3b8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 220px;
                padding: 20px 10px;
            }

            .main {
                margin-left: 220px;
                width: calc(100% - 220px);
            }

            .notification-card {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                position: static;
                width: 100%;
                height: auto;
                padding: 15px;
                box-shadow: none;
            }

            .main {
                margin-left: 0;
                width: 100%;
            }

            .menu a {
                justify-content: center;
            }

            .menu i {
                margin-right: 0;
            }

            .notifications {
                flex-direction: column;
                gap: 15px;
            }

            .notification-card {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Tenant Dashboard</h2>
        <div class="menu">
            <a href="#"><i class="material-icons">dashboard</i> Overview</a>
            <a href="#"><i class="material-icons">announcement</i> Announcements</a>
            <a href="#"><i class="material-icons">notifications</i> Notifications</a>
            <a href="#"><i class="material-icons">settings</i> Settings</a>
            <a href="#"><i class="material-icons">logout</i> Logout</a>
        </div>
    </div>

    <div class="main">
        <div class="header">
            <h1>Announcements</h1>
        </div>

        <div class="notifications">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="notification-card">
                        <h2>Announcement #<?= $row['id'] ?></h2>
                        <p><?= htmlspecialchars($row['announcement']) ?></p>
                        <small>Posted on <?= $row['created_at'] ?></small>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No announcements yet.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
