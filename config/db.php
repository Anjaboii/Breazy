<?php
// Database config (keep your existing MySQL setup)
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "Breazy";

// PurpleAir API config
define('PURPLEAIR_API_KEY', 'F1490A8B-0CA4-11F0-81BE-42010A80001F');
define('PURPLEAIR_API_URL', 'https://api.purpleair.com/v1/sensors');

// Create database connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) die("DB Connection failed: " . $conn->connect_error);
?>