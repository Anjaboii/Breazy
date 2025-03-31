<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

// 1️⃣ Database connection
$servername = "localhost";
$username = "root";  // Default XAMPP username
$password = "";      // Default XAMPP password is empty
$dbname = "breazy";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// 2️⃣ Read JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["location"]) || !isset($data["lat"]) || !isset($data["lon"]) || !isset($data["aqi"])) {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
    exit();
}

$location = $conn->real_escape_string($data["location"]);
$lat = $conn->real_escape_string($data["lat"]);
$lon = $conn->real_escape_string($data["lon"]);
$aqi = $conn->real_escape_string($data["aqi"]);

// 3️⃣ Insert data into database
$sql = "INSERT INTO locations (location, latitude, longitude, aqi) VALUES ('$location', '$lat', '$lon', '$aqi')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Location added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
}

// Close the connection
$conn->close();
?>
