<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "breazy";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed"]));
}

// Fetch all AQI sensor data
$sql = "SELECT id, location, latitude, longitude, aqi, created_at FROM locations ORDER BY created_at DESC";
$result = $conn->query($sql);

$sensors = [];
while ($row = $result->fetch_assoc()) {
    $sensors[] = $row;
}

echo json_encode(["success" => true, "data" => $sensors]);

$conn->close();
?>
