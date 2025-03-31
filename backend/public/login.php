<?php
// Database connection
$servername = "localhost";
$username = "root";  // Default username for XAMPP
$password = "";  // Default password for XAMPP
$dbname = "breazy";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start the session
session_start();

// Process the login form only when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if 'email' and 'password' are present in $_POST
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Check if the email exists in the database
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);  // Use the email from the form
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User found, now check if the password is correct
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Successful login, set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['username'] = $user['username'];

                // Redirect to dashboard or home page
                header("Location: dashboard.php");
                exit();
            } else {
                // Incorrect password
                $error_message = "Invalid password!";
            }
        } else {
            // No user found with that email
            $error_message = "No account found with this email!";
        }

        $stmt->close();
    } else {
        $error_message = "Email and password are required!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email Address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <?php
            if (isset($error_message)) {
                echo "<p class='error-message'>$error_message</p>";
            }
            ?>
        </form>
        <p>Don't have an account? <a href="signup.php">Signup here</a></p>
    </div>
</body>
</html>
