<?php
header('Content-Type: application/json');
require '../config/db.php';

$result = $conn->query("
    SELECT s.id, s.name, s.latitude, s.longitude, 
           r.pm25, r.pm10, r.reading_time
    FROM sensors s
    LEFT JOIN aqi_readings r ON s.id = r.sensor_id
    ORDER BY r.reading_time DESC
");

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>