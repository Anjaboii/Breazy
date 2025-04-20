<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get the location coordinates from the request
$lat = isset($_GET['lat']) ? $_GET['lat'] : '';
$lon = isset($_GET['lon']) ? $_GET['lon'] : '';

if (empty($lat) || empty($lon)) {
    echo json_encode(['success' => false, 'message' => 'Coordinates required']);
    exit;
}

$token = "4b98b49468bc4a44cc2df7ac4e0007163f430796"; // Replace with your actual token
$apiUrl = "https://api.waqi.info/feed/geo:{$lat};{$lon}/?token={$token}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if ($response) {
    $apiData = json_decode($response, true);
    
    if ($apiData['status'] === 'ok') {
        echo json_encode([
            'success' => true,
            'aqi' => (int)$apiData['data']['aqi'],
            'timestamp' => $apiData['data']['time']['s'],
            'components' => [
                'pm25' => $apiData['data']['iaqi']['pm25']['v'] ?? null,
                'pm10' => $apiData['data']['iaqi']['pm10']['v'] ?? null,
                'o3' => $apiData['data']['iaqi']['o3']['v'] ?? null,
                'no2' => $apiData['data']['iaqi']['no2']['v'] ?? null,
                'so2' => $apiData['data']['iaqi']['so2']['v'] ?? null,
                'co' => $apiData['data']['iaqi']['co']['v'] ?? null
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No data available']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to fetch data']);
}
?>