<?php
// C:\xampp\htdocs\BreazyAQI\insert_data.php
require 'config/db.php';

// Simulate sensor data
$sensor_id = 'PA_001'; 
$pm25 = rand(5, 150); // Random PM2.5 value (5-150 µg/m³)
$pm10 = $pm25 * 1.5;  // PM10 typically higher
$temp = rand(25, 35); // Colombo temperature range
$humidity = rand(60, 90); // Humidity %

$sql = "INSERT INTO aqi_readings (sensor_id, pm25, pm10, temperature, humidity) 
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sdddd", $sensor_id, $pm25, $pm10, $temp, $humidity);
$stmt->execute();

echo "Inserted data for sensor $sensor_id: PM2.5=$pm25, Temp=$temp°C";
?>