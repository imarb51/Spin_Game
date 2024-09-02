<?php
require_once 'functions.php';
if (isset($_SESSION['user_id'])) {
    header("Location: game.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spin to Win</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
            body {
        background: linear-gradient(135deg, #3e0705 0%, #5e1412 100%); /* Dark red gradient background */
        color: #f0e68c; /* Light yellow text color */
        font-family: 'Arial', sans-serif;
        font-weight: bold;
            height: 100vh; /* Full height of the viewport */
    margin: 0; /* Remove default margin */
    display: flex;
    align-items: center; /* Vertically center content */
    justify-content: center; /* Horizontally center content */
    }

    .banner {
        background: linear-gradient(135deg, #f0e68c 0%, #eedc82 100%); /* Soft gradient for the banner */
        color: #3e0705; /* Dark red text color */
        padding: 20px 40px;
        border-radius: 25px; /* Rounded corners */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Shadow for depth */
        display: inline-block;
        margin-bottom: 20px;
        font-size: 2.5rem; /* Larger font size */
        transition: transform 0.3s ease-in-out;
    }

    .banner:hover {
        transform: scale(1.05); /* Slight zoom on hover */
    }

    .custom-btn {
        background: linear-gradient(135deg, #3e0705 0%, #5e1412 100%); /* Gradient for the buttons */
        color: #f0e68c; /* Light yellow text */
        border-radius: 25px; /* More rounded corners */
        padding: 15px 30px; /* Increased padding */
        margin: 0 15px;
        text-decoration: none;
        font-size: 1.2rem; /* Increased font size */
        font-weight: bold;
        transition: background 0.3s, transform 0.3s ease-in-out;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15); /* Soft shadow for buttons */
    }

    .custom-btn:hover {
        background: linear-gradient(135deg, #5e1412 0%, #3e0705 100%); /* Reverse gradient on hover */
        color: #ffffff; /* White text on hover */
        transform: translateY(-5px); /* Slight lift on hover */
    }

    .custom-btn:active {
        transform: translateY(0); /* Remove lift on click */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Reduce shadow on click */
    }

    </style>
</head>
<body>
    <div class="container text-center">
        <div class="banner mt-5">
            <h1>Welcome to Spin to Win</h1>
        </div>
        <div class="mt-5">
            <a href="login.php" class="btn custom-btn">Login</a>
            <a href="register.php" class="btn custom-btn">Register</a>
        </div>
    </div>
</body>
</html>
