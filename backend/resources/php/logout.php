<?php
session_start(); // Start the session
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the dashboard page after logout
header("Location: http://localhost/BreazyAQI/backend/public/Pdashboard"); 
exit();
?>