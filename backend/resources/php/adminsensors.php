<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php';

// Handle Add Sensor
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add_sensor') {

        $name = $_POST['name'] ?? '';
        $latitude = $_POST['latitude'] ?? '';
        $longitude = $_POST['longitude'] ?? '';
        $desc = $_POST['description'] ?? '';
        $is_active = 1;

        if ($name && $latitude && $longitude) {
            $stmt = $conn->prepare("INSERT INTO sensors (name, latitude, longitude, description, is_active) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $name, $latitude, $longitude, $desc, $is_active);

            if ($stmt->execute()) {
                echo "Sensor added successfully!";
            } else {
                echo "Error inserting: " . $stmt->error;
            }

        } else {
            echo "Please fill all required fields.";
        }
    } else {
        echo "Invalid action.";
    }

// Handle Delete Sensor
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    $stmt = $conn->prepare("DELETE FROM sensors WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../../admin/sensors.php"); // Go back after delete
        exit;
    } else {
        echo "Error deleting: " . $stmt->error;
    }

// Handle Toggle Sensor Status
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['toggle_id']) && isset($_GET['current'])) {
    $id = intval($_GET['toggle_id']);
    $current = intval($_GET['current']);
    $newStatus = $current ? 0 : 1; // toggle 1 to 0 or 0 to 1

    $stmt = $conn->prepare("UPDATE sensors SET is_active = ? WHERE id = ?");
    $stmt->bind_param("ii", $newStatus, $id);

    if ($stmt->execute()) {
        header("Location: ../../admin/sensors.php"); // Go back after toggle
        exit;
    } else {
        echo "Error toggling status: " . $stmt->error;
    }

} else {
    echo "Invalid request.";
}
?>
