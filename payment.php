<?php
// Ensure session is started only once
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the necessary function file
include 'function.php'; // Assuming this file contains check_login_status() and get_tenant_data()

// Check if the user is logged in and is a tenant
check_login_status();

// Fetch tenant data
$tenant_id = $_SESSION['user_id'];
$tenant = get_tenant_data($tenant_id);

// Process payment if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_amount = $_POST['amount']; // Amount to be paid

    // PayMongo API keys (replace these with your actual keys)
    $public_key = 'your_actual_public_key'; // Replace with actual public key
    $secret_key = 'your_actual_secret_key'; // Replace with actual secret key

    // cURL call to create a payment intent
    $ch = curl_init('https://api.paymongo.com/v1/payment_intents');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode($secret_key . ":")
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'data' => [
            'attributes' => [
                'amount' => $payment_amount * 100, // Amount in cents (e.g., 1000 = 10.00 PHP)
                'currency' => 'PHP',
                'payment_method' => 'gcash', // Specify GCash as payment method
            ]
        ]
    ]));

    // Execute the cURL request and capture the response
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the response data
    $response_data = json_decode($response, true);

    // Check if the payment intent was created successfully
    if (isset($response_data['data'])) {
        // Redirect the user to the GCash payment page
        $payment_url = $response_data['data']['attributes']['checkout_url'];
        header("Location: $payment_url");
        exit();
    } else {
        // Show an error if the payment intent could not be created
        echo 'Error: ' . $response_data['errors'][0]['detail'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Payment</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .payment-box {
            background-color: #ffffff; /* White background */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            padding: 40px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-sizing: border-box;
        }
        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-weight: 600;
        }
        label {
            font-size: 18px;
            margin-bottom: 10px;
            display: block;
            color: #555;
        }
        input[type="number"] {
            padding: 12px;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="number"]:focus {
            border-color: #4CAF50; /* Green color on focus */
            outline: none;
        }
        button {
            padding: 15px 30px;
            background-color: #4CAF50; /* Green background */
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049; /* Darker green on hover */
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #999;
        }
        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="payment-box">
            <h1>Payment</h1>
            <form method="POST">
                <label for="amount">Enter Amount (in PHP):</label>
                <input type="number" name="amount" id="amount" required min="1" placeholder="Enter payment amount">
                <button type="submit">Pay via GCash</button>
            </form>
            <div class="footer">
                <p>Need help? <a href="#">Contact Support</a></p>
            </div>
        </div>
    </div>

</body>
</html>
