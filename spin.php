<?php
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['user_id'];

if (!canUserSpin($userId)) {
    echo json_encode(['success' => false, 'message' => 'Please wait 30 minutes before spinning again', 'canSpin' => false]);
    exit();
}

$images = ['images/gozoop1.png', 'images/gozoop2.png', 'images/gozoop3.png'];
$result = [];
for ($i = 0; $i < 3; $i++) {
    $result[] = $images[array_rand($images)];
}

$uniqueValues = array_unique($result);
$points = 0;
$message = '';

if (count($uniqueValues) === 1) {
    $points = 500;
    $message = 'Congratulations! You won 500 points!';
} elseif (count($uniqueValues) === 2) {
    $points = 200;
    $message = 'Good job! You won 200 points!';
} else {
    $message = 'Sorry, no points this time. Try again!';
}

updateUserSpin($userId, $points);

$newPoints = getUserPoints($userId);
$spinCount = getSpinCount($userId);
$spinsRemaining = 3 - $spinCount;

echo json_encode([
    'success' => true,
    'result' => $result,
    'points' => $newPoints,
    'message' => $message,
    'spinCount' => $spinCount,
    'spinsRemaining' => $spinsRemaining
]);
