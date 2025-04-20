<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get the location ID from the request
$locationId = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($locationId)) {
    echo json_encode(['success' => false, 'message' => 'Location ID is required']);
    exit;
}

// Connect to your database
$conn = new mysqli("localhost", "username", "password", "database_name");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Query the latest AQI data for this location
$stmt = $conn->prepare("SELECT aqi, timestamp FROM aqi_readings WHERE location_id = ? ORDER BY timestamp DESC LIMIT 1");
$stmt->bind_param("s", $locationId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'success' => true,
        'aqi' => (int)$row['aqi'],
        'timestamp' => $row['timestamp']
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'No data found for this location']);
}

$stmt->close();
$conn->close();
?>