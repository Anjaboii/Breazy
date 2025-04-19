<?php
require 'db.php';


header('Content-Type: application/json');

$result = $conn->query("SELECT id, name, latitude, longitude, description, is_active FROM sensors WHERE is_active = 1");

$sensors = [];

while ($row = $result->fetch_assoc()) {
    $sensors[] = $row;
}

echo json_encode($sensors);
?>
