<?php
include 'db.php';

$error = '';

// Admin credentials (hardcoded)
$admin_user = 'admin';
$admin_pass = 'admin123';

if(isset($_POST['admin_login'])){
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if($username === $admin_user && $password === $admin_pass){
        $_SESSION['admin'] = true;
        header("Location: admin_dashboard.php");
        exit;
    } else {
        $error = "Invalid admin credentials!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — VoteSecure</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🗳️ VoteSecure</div>
    <div class="nav-buttons">
        <a href="index.php">Home</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Admin Login</h1>
    <p>Login to manage the election</p>
</div>

<!-- ADMIN LOGIN FORM -->
<div class="form-container">
    <h2>🛡️ Admin Access</h2>

    <?php if($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Enter admin username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter admin password" required>
        </div>

        <button type="submit" name="admin_login" class="btn">Login as Admin</button>
    </form>

    <div class="form-link">
        <a href="index.php">← Back to Home</a>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System
</div>

</body>
</html>
