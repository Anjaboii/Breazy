<?php
require '../config/db.php';
$locations = $conn->query("SELECT * FROM sensors WHERE is_active = 1")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Load Leaflet CSS first -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>
    
    <!-- Then your custom CSS -->
    <link rel="stylesheet" href="assets/css/public.css">
</head>
<body>
    <div class="header">
        <h1>Air Quality Monitoring</h1>
    </div>
    
    <div id="map" style="height: 500px;"></div>
    
    <div class="aqi-list">
        <h2>Latest Readings</h2>
        <div id="readings-container"></div>
    </div>

    <!-- Load Leaflet JS before your custom scripts -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<!-- Then your custom JS -->
<script src="assets/js/public.js"></script>
</body>
</html>