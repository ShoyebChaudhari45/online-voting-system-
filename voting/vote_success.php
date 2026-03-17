<?php
include 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Success — VoteSecure</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🗳️ VoteSecure</div>
    <div class="nav-buttons">
        <a href="dashboard.php">Dashboard</a>
        <a href="result.php">View Results</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Vote Status</h1>
    <p>Your vote has been processed successfully</p>
</div>

<!-- SUCCESS MESSAGE -->
<div class="status-box">
    <div class="status-icon">✅</div>
    <h2>Vote Submitted Successfully!</h2>
    <p>
        Thank you for participating in the election.<br>
        Your vote has been recorded securely and cannot be changed.
    </p>
    <a href="dashboard.php" class="btn btn-small">Go to Dashboard</a>
    <a href="result.php" class="btn btn-small" style="background: linear-gradient(135deg, #48bb78, #38a169); margin-left:8px;">View Results</a>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System
</div>

</body>
</html>
