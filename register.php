<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .registration-container {
            background-color: rgba(144, 238, 144, 0.85); /* Light green overlay */
            width: 400px;
            margin: 100px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .registration-container h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #000;
        }
        .role-buttons button {
            width: 45%;
            padding: 10px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .role-buttons .tenant-btn {
            background-color: #4caf50;
            color: white;
        }
        .role-buttons .tenant-btn:hover {
            background-color: #3e8e41;
        }
        .role-buttons .admin-btn {
            background-color: #2196f3;
            color: white;
        }
        .role-buttons .admin-btn:hover {
            background-color: #1976d2;
        }
        .registration-container input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .submit-btn {
            background-color: #008cba;
            color: white;
            border: none;
            width: 95%;
            padding: 10px;
            font-size: 18px;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #005f73;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h1>Registration</h1>
        <form action="register_process.php" method="POST">
            <!-- Role Selection Dropdown -->
            <label for="user_role">Role:</label>
            <select name="user_role" id="user_role" required>
                <option value="tenants">Tenants</option>
               
            </select>

            <!-- Form Fields -->
            <input type="text" name="username" placeholder="Username" required>
            <input type="text" name="firstname" placeholder="Firstname" required>
            <input type="text" name="lastname" placeholder="Lastname" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Phone Number" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>

    <script>
        // Validate if the role is selected before form submission
        document.querySelector("form").addEventListener("submit", function(event) {
            var role = document.getElementById('user_role').value;
            if (!role) {
                alert("Please select a role (Tenant).");
                event.preventDefault(); // Prevent form submission
            }
        });
    </script>
</body>
</html>
