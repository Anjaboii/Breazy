<?php
// Step 1: Database connection parameters
$servername = "localhost";
$username = "root";  // Your MySQL username (usually "root")
$password = "";      // Your MySQL password (leave empty for local setups)
$dbname = "breazy";  // Name of your database

// Create connection to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 2: Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Step 3: Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match!");
    }

    // Step 4: Check if email already exists in the database
    $email_check_query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($email_check_query);

    if ($result->num_rows > 0) {
        die("Email is already registered.");
    }

    // Step 5: Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Step 6: Insert the user data into the database
    $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$hashed_password', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "New user registered successfully!";
        header("Location: Pdashboard");  // Redirect to a dashboard or login page
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Step 7: Close the database connection
$conn->close();
?>
