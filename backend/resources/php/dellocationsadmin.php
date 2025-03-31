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

// Check the request method
$method = $_SERVER["REQUEST_METHOD"];

if ($method === "GET") {
    // Fetch locations
    $sql = "SELECT id, location, latitude, longitude, aqi, created_at FROM locations ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $locations = [];

    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }

    echo json_encode($locations);
} elseif ($method === "DELETE") {
    // Delete a location
    $id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

    if ($id > 0) {
        $sql = "DELETE FROM locations WHERE id = $id";
        
        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "Location deleted successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error deleting location: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Invalid location ID"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}

$conn->close();
?>