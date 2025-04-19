<?php
// Connect to the database
require '../resources/php/db.php'; // Adjust path if needed

// Query to get sensors data from the database
$sql = "SELECT id, name, latitude, longitude, description, is_active FROM sensors";
$result = $conn->query($sql);

// Initialize an array to hold the sensors
$sensors = [];

if ($result->num_rows > 0) {
    // Fetch each sensor and add it to the $sensors array
    while ($row = $result->fetch_assoc()) {
        $sensor = [
            'id' => $row['id'],
            'name' => $row['name'],
            'latitude' => $row['latitude'],
            'longitude' => $row['longitude'],
            'description' => $row['description'],
            'is_active' => (int) $row['is_active']
        ];
        $sensors[] = $sensor;
    }
}

// Return the sensor data as JSON
echo json_encode($sensors);

// Close the database connection
$conn->close();
?>
