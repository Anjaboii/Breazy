<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Headers to allow cross-origin requests and define JSON response type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// Database connection settings
$servername = "localhost";
$username = "root";  // XAMPP default username
$password = "";      // XAMPP default password (empty)
$dbname = "breazy";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// Get the POST data from the request body
$data = json_decode(file_get_contents("php://input"), true);

// Check if necessary data is provided
if (!isset($data["email"], $data["username"], $data["password"])) {
    echo json_encode(["success" => false, "message" => "Missing fields: email, username, or password"]);
    exit();
}

// Sanitize inputs to prevent SQL injection
$email = $conn->real_escape_string($data["email"]);
$username = $conn->real_escape_string($data["username"]);
$password = $conn->real_escape_string($data["password"]);

// Insert user into the database
$sql = "INSERT INTO users (email, username, password, created_at) VALUES ('$email', '$username', '$password', NOW())";

// Check if the insert was successful
if ($conn->query($sql) === TRUE) {
    // Respond with a success message
    echo json_encode(["success" => true, "message" => "User registered successfully"]);
} else {
    // Respond with an error message
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

// Close the database connection
$conn->close();
?>
