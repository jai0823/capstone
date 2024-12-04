<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xentro Mall - Tenant Portal</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: url('img/bg.jpg') no-repeat center center fixed;
            background-size: cover;
            overflow-x: hidden;
            color: #333;
        }

        /* Navbar Styles */
        .navbar {
            background: linear-gradient(90deg, #2980B9, #1ABC9C);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            color: black !important;
            font-weight: bold;
            font-size: 1.8em;
            letter-spacing: 2px;
        }

        .navbar-brand:hover {
            color: #444 !important;
        }

        .navbar-nav .nav-link {
            background-color: black;
            padding: 10px 20px;
            border-radius: 5px;
            color: white !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar-nav .nav-link:hover {
            background-color: #444;
            color: #f39c12 !important;
        }

        .container {
            max-width: 1200px;
            margin: 100px auto 20px;
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        /* Header Section */
        .header h1 {
            font-size: 3.5em;
            margin-bottom: 15px;
            font-weight: bold;
            color: #2C3E50;
        }

        .header p {
            font-size: 1.4em;
            margin-bottom: 20px;
            color: #34495E;
        }

        .header button {
            padding: 15px 30px;
            font-size: 1.2em;
            border: none;
            border-radius: 30px;
            background: #F39C12;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .header button:hover {
            background: #F1C40F;
            transform: translateY(-5px);
        }

        /* Features Section */
        .features h2 {
            font-size: 2.5em;
            margin-bottom: 40px;
            color: #2980B9;
            font-weight: bold;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid #27AE60;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .feature-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .feature-item h3 {
            font-size: 1.8em;
            color: #2C3E50;
            margin-bottom: 15px;
        }

        .feature-item p {
            font-size: 1.1em;
            line-height: 1.8;
            color: #7F8C8D;
        }

        /* Call to Action Section */
        .cta {
            padding: 60px;
            background: linear-gradient(135deg, #2980B9, #1ABC9C);
            color: white;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        }

        .cta h2 {
            font-size: 2.8em;
            margin-bottom: 25px;
            font-weight: bold;
        }

        .cta p {
            font-size: 1.4em;
            margin-bottom: 40px;
        }

        .cta button {
            padding: 15px 30px;
            font-size: 1.2em;
            border: none;
            border-radius: 30px;
            background: #F39C12;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .cta button:hover {
            background: #F1C40F;
            transform: translateY(-5px);
        }

        /* Footer Section */
        .footer {
            background: #2C3E50;
            color: white;
            padding: 30px;
            font-size: 1em;
            border-radius: 5px;
        }

        .footer a {
            color: #F39C12;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .feature-item {
                flex: 1 1 100%;
                max-width: 100%;
            }

            .header h1 {
                font-size: 2.5em;
            }

            .features h2 {
                font-size: 2em;
            }

            .cta h2 {
                font-size: 2em;
            }

            .cta p {
                font-size: 1.2em;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar Section (Bootstrap Navbar) -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">Xentro Mall</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <h1>Welcome to Xentro Mall Tenant Portal</h1>
            <p>Your one-stop solution for tenant management, updates, and communication.</p>
            <button onclick="window.location.href='login.php'">Get Started</button>
        </div>

        <!-- Features Section -->
        <div class="features">
            <h2>Why Choose Us?</h2>
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-item">
                        <h3>Profile Management</h3>
                        <p>Update your tenant details anytime, anywhere, with our easy-to-use platform.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-item">
                        <h3>Payment Tracking</h3>
                        <p>Access your rental invoices and payment history with just a few clicks.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-item">
                        <h3>Real-time Updates</h3>
                        <p>Receive important announcements and updates directly from mall management.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-item">
                        <h3>SMS Notifications</h3>
                        <p>Stay informed with SMS reminders for upcoming payments, events, and urgent alerts.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-item">
                        <h3>Tenant Support</h3>
                        <p>Contact our support team to address your concerns promptly and efficiently.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-item">
                        <h3>Event Invitations</h3>
                        <p>Be the first to know about upcoming mall events and marketing opportunities.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action Section -->
        <div class="cta">
            <h2>Ready to Get Started?</h2>
            <p>Join our platform and experience seamless tenant management at your fingertips.</p>
            <button onclick="window.location.href='register.php'">Register Now</button>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer text-center">
        <p>&copy; 2024 Xentro Mall. All Rights Reserved. | <a href="privacy-policy.html">Privacy Policy</a></p>
    </div>

    <!-- Bootstrap JS, Popper.js (for dropdowns and modals) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
