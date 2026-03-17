<?php
include 'db.php';

$error = '';

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];

    if(empty($email) || empty($pass)){
        $error = "Please fill in all fields!";
    } else {
        $email_esc = mysqli_real_escape_string($conn, $email);
        $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email_esc'");
        $user = mysqli_fetch_assoc($q);

        if($user && password_verify($pass, $user['password'])){
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Login — VoteSecure</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🗳️ VoteSecure</div>
    <div class="nav-buttons">
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Voter Login</h1>
    <p>Login to cast your vote securely</p>
</div>

<!-- LOGIN FORM -->
<div class="form-container">
    <h2>Login</h2>

    <?php if($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
        </div>

        <button type="submit" name="login" class="btn">Login</button>
    </form>

    <div class="form-link">
        New voter? <a href="register.php">Register here</a>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System
</div>

</body>
</html>