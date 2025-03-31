<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breazy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data["username"]) || !isset($data["password"]) || !isset($data["role"])) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit();
}

$username = $conn->real_escape_string($data["username"]);
$password = $conn->real_escape_string($data["password"]);
$role = $conn->real_escape_string($data["role"]); // Get the selected role (user/admin)

// Check user credentials based on role
$sql = "SELECT id, username, password, role FROM users WHERE username = '$username' AND role = '$role'";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Invalid username or role"]);
    exit();
}

$user = $result->fetch_assoc();

// Validate password (plaintext)
if ($password !== $user["password"]) {
    echo json_encode(["success" => false, "message" => "Incorrect password"]);
    exit();
}

// Successful login
echo json_encode([
    "success" => true,
    "message" => "Login successful",
    "role" => $user["role"]
]);

$conn->close();
?>
