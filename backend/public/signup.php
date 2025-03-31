<?php
// Database connection
$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "";  // Default password for XAMPP
$dbname = "breazy";  // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password before storing it
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $sql = "INSERT INTO users (email, username, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $username, $hashedPassword);

    // Execute the query
    if ($stmt->execute()) {
        echo "User registered successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <style>
        /* CSS to style the form */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #1e2a5a;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 300px;
        }
        h2 {
            margin-bottom: 20px;
            color: #1e2a5a;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #1e2a5a;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #162047;
        }
        p {
            margin-top: 10px;
        }
        a {
            display: inline-block;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #1e2a5a;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 10px;
        }
        a:hover {
            background-color: #162047;
        }
        .error-message {
            color: red;
            font-size: 12px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Signup</h2>
        <form id="signupForm" action="signup.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required id="password">
            <input type="password" name="confirm_password" placeholder="Confirm Password" required id="confirm_password">
            <button type="submit">Signup</button>
            <p class="error-message" id="passwordError">Passwords do not match!</p>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>

    <script>
        // JavaScript to validate if passwords match
        document.getElementById('signupForm').addEventListener('submit', function(event) {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm_password').value;
        
            // Check if password and confirm password match
            if (password !== confirmPassword) {
                // Show error message if passwords do not match
                document.getElementById('passwordError').style.display = 'block';
                event.preventDefault();  // Prevent form submission
            } else {
                // Hide error message if passwords match
                document.getElementById('passwordError').style.display = 'none';
            }
        });
    </script>
</body>
</html>
