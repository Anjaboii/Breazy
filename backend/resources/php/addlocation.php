<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data["lat"]) && isset($data["lon"]) && isset($data["aqi"])) {
    $file = "locations.json";

    if(file_exists($file)) {
        $locations = json_decode(file_get_contents($file), true);
    } else {
        $locations = [];
    }

    $newLocation = [
        "lat" => $data["lat"],
        "lon" => $data["lon"],
        "aqi" => $data["aqi"]
    ];

    $locations[] = $newLocation;
    file_put_contents($file, json_encode($locations));

    echo json_encode(["message" => "Location added successfully!"]);
} else {
    echo json_encode(["message" => "Invalid input!"]);
}
?>
