<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to login page
    header("Location: login.html");
    exit();
}

echo "<h2>Welcome, " . $_SESSION['username'] . "!</h2>";
echo "<p>Email: " . $_SESSION['email'] . "</p>";
echo "<a href='logout.php'>Logout</a>";
?>
