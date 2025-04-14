<?php
header("Content-Type: application/json");

// Enable CORS if needed
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Get POST data
$data = json_decode(file_get_contents("php://input"));
$email = $data->username ?? ''; // Email is coming through the "username" field
$password = $data->password ?? '';

// Simple validation
if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Both email and password are required"]);
    exit;
}

// Connect to DB
$conn = new mysqli("localhost", "root", "", "breazy");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

// Check credentials (plain text comparison)
$stmt = $conn->prepare("SELECT * FROM admins WHERE Email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // Login successful
    session_start();
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_email'] = $email;

    echo json_encode([
        "success" => true,
        "message" => "Login successful",
        "redirect" => "http://localhost/BreazyAQI/backend/admin/admin"
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid email or password"]);
}

$stmt->close();
$conn->close();
?>
