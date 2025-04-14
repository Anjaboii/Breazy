<?php
header("Content-Type: application/json");

// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"));
$username = $data->username ?? '';
$password = $data->password ?? '';

// Simple validation
if (empty($username) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Both username and password are required"]);
    exit;
}

// Connect to DB
$conn = new mysqli("localhost", "root", "", "breazy");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Check credentials (plain text comparison)
$stmt = $conn->prepare("SELECT * FROM admins WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Login successful
    session_start();
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username'] = $username;
    
    echo json_encode([
        "success" => true, 
        "message" => "Login successful",
        "redirect" => "http://localhost/BreazyAQI/backend/admin/admin" // Update this path
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
}

$stmt->close();
$conn->close();
?>