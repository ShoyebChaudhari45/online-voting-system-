<?php
$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$port       = 3307;

// Connect without database first
$conn = mysqli_connect($servername, $username, $password, "", $port);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS voting");
mysqli_select_db($conn, "voting");

// Create users table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    voted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Create candidates table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    party VARCHAR(100) NOT NULL,
    votes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Create election_status table
mysqli_query($conn, "CREATE TABLE IF NOT EXISTS election_status (
    id INT AUTO_INCREMENT PRIMARY KEY,
    status VARCHAR(30) DEFAULT 'not_started'
)");

// Insert default election status if not exists
$check = mysqli_query($conn, "SELECT * FROM election_status");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "INSERT INTO election_status (status) VALUES ('not_started')");
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Database Setup</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #0f0c29, #302b63, #24243e);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .box {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 20px;
            padding: 50px 40px;
            text-align: center;
            color: white;
            max-width: 500px;
        }
        .box h1 { font-size: 28px; margin-bottom: 10px; }
        .box .icon { font-size: 64px; margin-bottom: 20px; }
        .box p { opacity: 0.85; line-height: 1.7; }
        .box a {
            display: inline-block;
            margin-top: 25px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .box a:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102,126,234,0.4);
        }
    </style>
</head>
<body>
    <div class="box">
        <div class="icon">✅</div>
        <h1>Database Setup Complete!</h1>
        <p>
            All tables have been created successfully:<br>
            <strong>users</strong> • <strong>candidates</strong> • <strong>election_status</strong>
        </p>
        <a href="index.php">Go to Homepage</a>
    </div>
</body>
</html>
