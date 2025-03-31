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

// Fetch locations
$sql = "SELECT * FROM locations";
$result = $conn->query($sql);

$locations = [];

while ($row = $result->fetch_assoc()) {
    $locations[] = $row;
}

echo json_encode($locations);

$conn->close();
?>
