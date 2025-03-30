<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_location'])) {
        $stmt = $conn->prepare("INSERT INTO custom_locations (name, latitude, longitude) VALUES (?, ?, ?)");
        $stmt->bind_param("sdd", $_POST['name'], $_POST['lat'], $_POST['lng']);
        $stmt->execute();
    }
    
    if (isset($_POST['delete_location'])) {
        $conn->query("DELETE FROM custom_locations WHERE id = {$_POST['id']}");
    }
}

$locations = $conn->query("SELECT * FROM custom_locations");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>
    <div class="admin-container">
        <header>
            <h1>Admin Dashboard</h1>
            <a href="logout.php" class="logout">Logout</a>
        </header>
        
        <div id="map-picker" style="height: 400px;"></div>
        
        <form method="POST" class="location-form">
            <input type="text" name="name" placeholder="Location Name" required>
            <input type="hidden" name="lat" id="lat">
            <input type="hidden" name="lng" id="lng">
            <button type="submit" name="add_location">Add Location</button>
        </form>
        
        <h2>Current Locations</h2>
        <table>
            <?php while ($loc = $locations->fetch_assoc()): ?>
            <tr>
                <td><?= $loc['name'] ?></td>
                <td><?= $loc['latitude'] ?>, <?= $loc['longitude'] ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Delete this location?')">
                        <input type="hidden" name="id" value="<?= $loc['id'] ?>">
                        <button type="submit" name="delete_location" class="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const map = L.map('map-picker').setView([6.9271, 79.8612], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
        
        let marker;
        map.on('click', function(e) {
            if (marker) map.removeLayer(marker);
            marker = L.marker(e.latlng).addTo(map)
                .bindPopup("Selected Location").openPopup();
            document.getElementById('lat').value = e.latlng.lat.toFixed(6);
            document.getElementById('lng').value = e.latlng.lng.toFixed(6);
        });
    </script>
</body>
</html>