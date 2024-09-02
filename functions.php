<?php
session_start();
require_once 'db_connect.php';

function registerUser($email, $password) {
    global $pdo;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    return $stmt->execute([$email, $hashedPassword]);
}

function loginUser($email, $password) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

function getUserPoints($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT points FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

function canUserSpin($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT spin_count, last_spin FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    
    if ($result['spin_count'] < 3) {
        return true;
    }
    
    $timeSinceLastSpin = time() - strtotime($result['last_spin']);
    if ($timeSinceLastSpin >= 1800) { // 30 minutes
        resetSpinCount($userId);
        return true;
    }
    
    return false;
}

function updateUserSpin($userId, $points) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET points = points + ?, spin_count = spin_count + 1, last_spin = CASE WHEN spin_count = 2 THEN CURRENT_TIMESTAMP ELSE last_spin END WHERE id = ?");
    return $stmt->execute([$points, $userId]);
}

function resetSpinCount($userId) {
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET spin_count = 0, last_spin = NULL WHERE id = ?");
    return $stmt->execute([$userId]);
}

function getSpinCount($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT spin_count FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    return $stmt->fetchColumn();
}

function getTimeUntilNextSpin($userId) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT last_spin, spin_count FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $result = $stmt->fetch();
    
    if ($result['spin_count'] < 3 || $result['last_spin'] === null) {
        return 0;
    }
    
    $timeSinceLastSpin = time() - strtotime($result['last_spin']);
    $timeUntilNextSpin = max(0, 1800 - $timeSinceLastSpin);
    
    return $timeUntilNextSpin;
}

function getProducts() {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products");
    $stmt->execute();
    return $stmt->fetchAll();
}

function redeemProduct($userId, $productId) {
    global $pdo;
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("SELECT points_required FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        $pointsRequired = $stmt->fetchColumn();
        
        $stmt = $pdo->prepare("UPDATE users SET points = points - ? WHERE id = ? AND points >= ?");
        $result = $stmt->execute([$pointsRequired, $userId, $pointsRequired]);
        
        if ($result && $stmt->rowCount() > 0) {
            $pdo->commit();
            return true;
        } else {
            $pdo->rollBack();
            return false;
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        return false;
    }
}
?>