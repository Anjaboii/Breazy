<?php
// C:\xampp\htdocs\BreazyAQI\admin\Adashboard.php
require '../config/db.php';

// Fetch latest 10 readings
$result = $conn->query("
    SELECT a.*, s.name as sensor_name 
    FROM aqi_readings a
    JOIN sensors s ON a.sensor_id = s.id
    ORDER BY a.reading_time DESC 
    LIMIT 10
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>BreazyAQI Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1 class="text-center mb-4">Air Quality Dashboard</h1>
        
        <!-- Data Table -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5>Latest Readings</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Sensor</th>
                            <th>PM2.5</th>
                            <th>PM10</th>
                            <th>Temp (°C)</th>
                            <th>Humidity</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['sensor_name']) ?></td>
                            <td class="<?= $row['pm25'] > 50 ? 'text-danger fw-bold' : '' ?>">
                                <?= $row['pm25'] ?>
                            </td>
                            <td><?= $row['pm10'] ?></td>
                            <td><?= $row['temperature'] ?></td>
                            <td><?= $row['humidity'] ?>%</td>
                            <td><?= $row['reading_time'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PM2.5 Chart -->
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>PM2.5 Trends</h5>
            </div>
            <div class="card-body">
                <canvas id="pmChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Chart.js implementation
        const ctx = document.getElementById('pmChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_column($result->fetch_all(MYSQLI_ASSOC), 'reading_time')) ?>,
                datasets: [{
                    label: 'PM2.5 (µg/m³)',
                    data: <?= json_encode(array_column($result->fetch_all(MYSQLI_ASSOC), 'pm25')) ?>,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    tension: 0.1
                }]
            }
        });
    </script>
</body>
</html>