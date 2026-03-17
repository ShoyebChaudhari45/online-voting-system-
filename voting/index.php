<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="VoteSecure — A secure, transparent, and trusted online voting platform.">
    <title>VoteSecure — Online Voting System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🗳️ <span>VoteSecure</span></div>
    <div class="nav-buttons">
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="register.php">Register</a>
            <a href="login.php" class="nav-btn-primary">Login</a>
        <?php endif; ?>
        <a href="admin_login.php">Admin</a>
        <a href="about_us.php">About</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Your Vote. Your Voice.<br>Your Future.</h1>
    <p>
        A secure and transparent digital voting platform.<br>
        One voter — one vote. Simple, trusted, and tamper-proof.
    </p>
</div>

<!-- CONTENT -->
<div class="container">
    <h2>Get Started</h2>
    <div class="cards">

        <div class="card">
            <div class="card-icon">📝</div>
            <h3>Register</h3>
            <p>Create your voter account to participate in elections</p>
            <a href="register.php" class="card-btn">Register</a>
        </div>

        <div class="card">
            <div class="card-icon">🗳️</div>
            <h3>Cast Vote</h3>
            <p>Login and securely vote for your preferred candidate</p>
            <a href="evm.php" class="card-btn">Vote Now</a>
        </div>

        <div class="card">
            <div class="card-icon">📊</div>
            <h3>View Results</h3>
            <p>See the election results with live vote counts</p>
            <a href="result.php" class="card-btn">Results</a>
        </div>

        <div class="card">
            <div class="card-icon">🛡️</div>
            <h3>Admin Panel</h3>
            <p>Manage candidates, start and end elections</p>
            <a href="admin_login.php" class="card-btn">Admin</a>
        </div>

    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System. All rights reserved.
</div>

</body>
</html>
