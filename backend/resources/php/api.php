<?php
// Set the headers for the response (allowing cross-origin requests and ensuring the content is JSON)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// 1️⃣ Database connection
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password is empty
$dbname = "breazy";  // Your database name

// Establish connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed: " . $conn->connect_error]));
}

// 2️⃣ Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate the incoming data
if (!isset($data["location"]) || !isset($data["lat"]) || !isset($data["lon"]) || !isset($data["aqi"])) {
    echo json_encode(["success" => false, "message" => "Invalid input. Please ensure all fields are provided."]);
    exit();
}

$location = $conn->real_escape_string($data["location"]);
$lat = $conn->real_escape_string($data["lat"]);
$lon = $conn->real_escape_string($data["lon"]);
$aqi = $conn->real_escape_string($data["aqi"]);

// Check if the AQI is a valid number
if (!is_numeric($aqi)) {
    echo json_encode(["success" => false, "message" => "AQI must be a valid number."]);
    exit();
}

// 3️⃣ Insert data into the database
$sql = "INSERT INTO locations (location, latitude, longitude, aqi) VALUES ('$location', '$lat', '$lon', '$aqi')";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Location added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

// Close the connection
$conn->close();
?>