<?php
require 'C:\xampp\htdocs\BreazyAQI\config\db.php';

header('Content-Type: application/json');

try {
    // Colombo bounding box coordinates
    $nwlng = 79.75; // Northwest longitude
    $nwlat = 7.05;  // Northwest latitude
    $selng = 79.95;  // Southeast longitude
    $selat = 6.80;   // Southeast latitude

    // PurpleAir API URL with Colombo bounds
    $url = PURPLEAIR_API_URL . "?fields=pm2.5,latitude,longitude,name,last_seen" .
           "&location_type=0" .
           "&nwlng=$nwlng&nwlat=$nwlat&selng=$selng&selat=$selat";

    $options = [
        'http' => [
            'header' => "X-API-Key: " . PURPLEAIR_API_KEY,
            'timeout' => 10 // 10 second timeout
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response === FALSE) {
        throw new Exception("Failed to fetch data from PurpleAir API");
    }

    $data = json_decode($response, true);

    if (!isset($data['data'])) {
        throw new Exception("Invalid data format from PurpleAir API");
    }

    // Return only the data array
    echo json_encode($data['data']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>