<?php
session_start();
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>