<?php
include 'functions.php';

// Set a 30-minute session timer
$session_duration = 1800; // 30 minutes in seconds

// Initialize session start time if not set
if (!isset($_SESSION['session_start_time'])) {
    $_SESSION['session_start_time'] = time();
}

// Get user ID
$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Initialize variables
$points = 0;
$canSpin = false;
$spinCount = 0;
$timeUntilNextSpin = $session_duration; // Default to 30 minutes

// If logged in, retrieve the user's points, spin availability, and spin count
if ($userId !== null) {
    $points = getUserPoints($userId);
    $spinCount = getSpinCount($userId);

    if ($spinCount > 0) {
        // Calculate the remaining time based on when the user last spun
        $timeElapsedSinceLastSpin = time() - $_SESSION['session_start_time'];
        $timeUntilNextSpin = max(0, $session_duration - $timeElapsedSinceLastSpin);

        if ($timeUntilNextSpin === 0) {
            $canSpin = true; // Timer has expired, allow spinning
        } else {
            $canSpin = ($spinCount < 3); // Allow spin if less than 3 spins in the session
        }
    } else {
        // If the user hasn't spun yet, allow them to spin
        $canSpin = true;
    }
} else {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spin to Win Game</title>
    <style>
        body.game-page {
            background-color: #1d1f21;
            font-family: 'Arial', sans-serif;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .game-container {
            margin: 50px auto;
            max-width: 450px;
            background: linear-gradient(135deg, #6b6e70, #2f3032);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        h1 {
            font-size: 2rem;
            color: #ffd700;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2rem;
            color: #dcdcdc;
            margin-bottom: 30px;
        }

        #slot-machine-container {
            position: relative;
            background-size: cover;
            width: 100%;
            height: 400px;
            margin: 0 auto;
            padding-top: 80px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
        }

        #slot-machine {
            display: flex;
            justify-content: center;
            position: relative;
            top: 90px;
        }

        .reel {
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 15px;
            background-color: #fff;
            border: 4px solid #000;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .reel img {
            width: 120%;
            height: auto;
            transform: translateY(0);
            transition: transform 0.5s ease-in-out;
        }

        .reel.spinning img {
            animation: spin 0.5s infinite;
        }

        @keyframes spin {
            0% {
                transform: translateY(-100%);
            }
            100% {
                transform: translateY(100%);
            }
        }

        #spin-button {
            padding: 15px 25px;
            background-color: #ff4500;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            position: relative;
            top: 140px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
        }

        #spin-button:hover {
            background-color: #e03d00;
        }

        #spin-button:disabled {
            background-color: #aaa;
            cursor: not-allowed;
        }

        .logout-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px;
            background-color: #5b3e2b;
            color: #fff;
            border-radius: 50%;
            text-decoration: none;
            font-weight: bold;
        }

        .redeem-link {
            display: inline-block;
            margin-top: 20px;
            padding: 15px 30px;
            background-color: #ffd700;
            color: #4b0a0a;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            transition: background-color 0.3s ease;
        }

        .redeem-link:hover {
            background-color: #ffeb3b;
        }

        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            color: #000;
            padding: 20px;
            border-radius: 5px;
            z-index: 1000;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }

        .popup-content h2 {
            margin: 0;
            margin-bottom: 10px;
        }

        .popup-content p {
            margin: 0;
        }

        #result-ok {
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #ff4500;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        #result-ok:hover {
            background-color: #e03d00;
        }

        .timer-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .timer-box {
            background-color: #5b3e2b;
            color: #ffd700;
            padding: 10px;
            margin: 0 5px;
            border-radius: 5px;
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body class="game-page">
    <a href="logout.php" class="logout-button">Logout</a>
    <div class="game-container">
        <h1>Spin to Win</h1>
        <p>Your Points: <span id="points"><?php echo $points; ?></span></p>
        <div id="slot-machine-container">
            <div id="slot-machine">
                <div class="reel" id="reel1"><img src="images/gozoop1.png" alt="Logo"></div>
                <div class="reel" id="reel2"><img src="images/gozoop1.png" alt="Logo"></div>
                <div class="reel" id="reel3"><img src="images/gozoop1.png" alt="Logo"></div>
            </div>
            <!-- Enable button if user can spin -->
            <button id="spin-button" <?php echo $canSpin ? '' : 'disabled'; ?>>Spin Now</button>
        </div>
        <div class="timer-container">
            <div class="timer-box" id="minutes">30</div>
            <div class="timer-box" id="seconds">00</div>
        </div>
        <a href="redeem.php" class="redeem-link">Redeem Points</a>
    </div>
    <div id="result-popup" class="popup">
        <div class="popup-content">
            <h2 id="result-title"></h2>
            <p id="result-message"></p>
            <button id="result-ok">OK</button>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const spinButton = document.getElementById('spin-button');
        const reels = [
            document.getElementById('reel1'),
            document.getElementById('reel2'),
            document.getElementById('reel3')
        ];
        let timeUntilNextSpin = <?php echo $timeUntilNextSpin; ?>;
        let timerInterval;
        let spinCount = <?php echo $spinCount; ?>;

        function updateTimer() {
            const minutes = Math.floor(timeUntilNextSpin / 60);
            const seconds = timeUntilNextSpin % 60;

            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');

            if (timeUntilNextSpin > 0) {
                timeUntilNextSpin--;
            } else {
                clearInterval(timerInterval);
                spinButton.disabled = false;
                spinCount = 0;
            }
        }

        if (timeUntilNextSpin > 0 && spinCount > 0) {
            timerInterval = setInterval(updateTimer, 1000);
            updateTimer();
            spinButton.disabled = true;
        }

        spinButton.addEventListener('click', function() {
            spinButton.disabled = true;

            // Show spinning animation
            reels.forEach((reel, index) => {
                reel.classList.add('spinning');
                setTimeout(() => {
                    reel.classList.remove('spinning');
                }, 2000 + index * 500); // staggered stopping
            });

            // Simulate the result after the animation ends
            setTimeout(function() {
                fetch('spin.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Display the result on the reels
                            reels.forEach((reel, index) => {
                                reel.innerHTML = `<img src="${data.result[index]}" alt="Logo">`;
                            });

                            // Update the points
                            document.getElementById('points').textContent = data.points;

                            // Show the result popup
                            document.getElementById('result-title').textContent = 'Result';
                            document.getElementById('result-message').textContent = data.message;
                            document.getElementById('result-popup').style.display = 'block';

                            // Handle spin count and timer
                            spinCount = data.spinCount;
                            if (spinCount < 3) {
                                spinButton.disabled = false;
                            } else {
                                timeUntilNextSpin = 1800; // 30 minutes
                                timerInterval = setInterval(updateTimer, 1000);
                                updateTimer();
                            }
                        } else {
                            // Handle error (e.g., session expired)
                            alert(data.message);
                            if (!data.canSpin) {
                                window.location.reload(); // Reload the page to update the UI
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred. Please try again.');
                        spinButton.disabled = false;
                    });
            }, 2500); // wait for animation to complete
        });

        // Close the popup
        document.getElementById('result-ok').addEventListener('click', function() {
            document.getElementById('result-popup').style.display = 'none';
        });

        // Logout button functionality
        document.querySelector('.logout-button').addEventListener('click', function(e) {
            e.preventDefault();
            fetch('logout.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = 'login.php';
                    } else {
                        alert('Logout failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred during logout. Please try again.');
                });
        });
    });
    </script>
</body>
</html>
