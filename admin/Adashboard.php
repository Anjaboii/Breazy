<?php
require '../config/db.php';

// Fetch latest readings
$readings = $conn->query("
    SELECT r.*, s.name 
    FROM aqi_readings r
    JOIN sensors s ON r.sensor_id = s.id
    ORDER BY r.reading_time DESC 
    LIMIT 10
")->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>BreazyAQI Dashboard</title>
    <link rel="stylesheet" href="C:\xampp\htdocs\BreazyAQI\assets\css\admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar">
                <h4>BreazyAQI</h4>
                <ul>
                    <li class="active"><a href="Adashboard.php">Dashboard</a></li>
                    <li><a href="manage_sensors.php">Sensors</a></li>
                    <li><a href="Alogout.php">Logout</a></li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10">
                <h2>Air Quality Overview</h2>
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sensor</th>
                                    <th>PM2.5</th>
                                    <th>PM10</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($readings as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['name']) ?></td>
                                    <td class="<?= $row['pm25'] > 35 ? 'text-danger' : '' ?>">
                                        <?= $row['pm25'] ?>
                                    </td>
                                    <td><?= $row['pm10'] ?></td>
                                    <td><?= $row['reading_time'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="../assets/js/app.js"></script>
</body>
</html>