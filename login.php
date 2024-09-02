<?php
require_once 'functions.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    if (loginUser($email, $password)) {
        header("Location: game.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Login - Spin to Win</title>
    <style>
                body.auth-page {
            background: linear-gradient(135deg, #3e0705 0%, #5e1412 100%); /* Dark red gradient background */
            color: #f0e68c; /* Light yellow text color */
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .auth-container {
            background-color: #f0e68c;
            border-radius: 10px;
            padding: 30px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .auth-container .logo {
            display: block;
            margin: 0 auto 20px;
            width: 80px; /* Adjust size as needed */
        }

        .auth-container h1 {
            margin-bottom: 20px;
            color: #3e0705; /* Match the gradient color */
        }

        .auth-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            background: #cac69b;
        }

        .auth-button {
            background-color: #3e0705; /* Match the gradient color */
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        .auth-button:hover {
            background-color: #5e1412; /* Slightly lighter on hover */
        }

        .auth-link {
            display: block;
            margin-top: 20px;
            color: #3e0705; /* Match the gradient color */
            text-decoration: none;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

    </style>
</head>
<body class="auth-page"> <!-- Add 'auth-page' class to body -->
    <div class="auth-container">
        <img src="images/gozoop1.png" alt="GoZoop Logo" class="logo">
        <h1>Login</h1>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?> <!-- Display error message -->
        <form method="POST">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Password">
            <button type="submit" class="auth-button">Login</button>
        </form>
        <a href="register.php" class="auth-link">Don't have an account? Register</a> <!-- Registration link -->
    </div>
</body>
</html>
