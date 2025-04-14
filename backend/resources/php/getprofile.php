<?php
session_start();
header("Content-Type: application/json");

// Debug: check if session is even active
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "error" => "No user is logged in",
        "session" => $_SESSION
    ]);
    exit;
}

$userId = $_SESSION['user_id'];

$host = 'localhost';
$db = 'aqi';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->prepare("SELECT name, email, role FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    if ($user) {
        echo json_encode($user);
    } else {
        echo json_encode(["error" => "User not found", "user_id" => $userId]);
    }
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
