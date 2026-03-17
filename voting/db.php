<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$servername = "127.0.0.1";
$username   = "root";
$password   = "";
$database   = "voting";
$port       = 3307;

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($servername, $username, $password, $database, $port);

if (!$conn) {
    // Try without database (for setup_db.php first run)
    $conn = @mysqli_connect($servername, $username, $password, "", $port);
    if (!$conn) {
        echo '<!DOCTYPE html><html><head><title>DB Error</title>
        <style>body{font-family:sans-serif;background:#0a0f1e;color:#fff;display:flex;justify-content:center;align-items:center;min-height:100vh;margin:0;}
        .box{background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:16px;padding:40px;max-width:500px;text-align:center;}
        h2{color:#f87171;} code{background:rgba(255,255,255,0.1);padding:2px 8px;border-radius:4px;} a{color:#818cf8;}
        </style></head><body><div class="box">
        <h2>Database Connection Failed</h2>
        <p>Could not connect to MySQL. Please check:</p>
        <p>1. XAMPP MySQL is <strong>running</strong></p>
        <p>2. If root has a password, edit <code>db.php</code> line 8</p>
        <p>3. Run <a href="setup_db.php">setup_db.php</a> first</p>
        <p style="margin-top:20px;color:#9ca3af;font-size:13px;">Error: '.mysqli_connect_error().'</p>
        </div></body></html>';
        exit;
    }
}
?>