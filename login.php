<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Xentro Mall Calapan</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover; /* Ensures the background covers the entire page */
        }

        .container {
            display: flex;
            width: 900px;
            height: 500px;
            background-color: #2E8B57; /* Green background */
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        /* Left Section with Circular Logo */
        .left-section {
            width: 50%;
            background: url('img/mall-background.jpg') no-repeat center center;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .logo {
            width: 180px;
            height: 180px;
            border-radius: 50%; /* Makes the logo circular */
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .logo img {
            width: 200%;
            height: 200%;
            border-radius: 50%; /* Ensures the image inside the logo is circular */
        }

        /* Right Section: Login Form */
        .right-section {
            width: 50%;
            padding: 40px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
            color: black; /* Changed to black */
        }

        label {
            font-size: 15px;
            color: white; /* White for form labels */
            margin-bottom: 8px;
            display: block;
        }

        select, input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            font-size: 16px;
            outline: none;
            transition: border-color 0.3s ease;
        }

        select:focus, input[type="text"]:focus, input[type="password"]:focus {
            border-color: white; /* Adjust focus color for visibility */
        }

        button.submit {
            width: 100%;
            padding: 12px;
            background-color: white; /* Button color updated for contrast */
            border: none;
            border-radius: 8px;
            color: #2E8B57; /* Text color matches the green background */
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.submit:hover {
            background-color: #f0f0f0;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password a {
            color: black; /* Changed to black */
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #333; /* Slightly darker hover effect */
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Section with Circular Logo -->
        <div class="left-section">
            <div class="logo">
                <img src="img/logo.jpg" alt="Xentro Mall Logo">
            </div>
        </div>

        <!-- Right Section with Login Form -->
        <div class="right-section">
            <h1>Login</h1>
            <form action="login_process.php" method="POST">
                <label for="user-role">Select Role:</label>
                <select id="user-role" name="user_role">
                    <option value="tenant">Tenant</option>
                    <option value="admin">Admin</option>
                </select>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="submit">Log in</button>
            </form>

            <div class="forgot-password">
                <a href="forgot_pass.php">Forgot password?</a>
            </div>
        </div>
    </div>
</body>
</html>

