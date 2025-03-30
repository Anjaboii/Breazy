<?php
require '../config/db.php';
header('Content-Type: application/json');

$locations = $conn->query("SELECT * FROM custom_locations");
echo json_encode($locations->fetch_all(MYSQLI_ASSOC));