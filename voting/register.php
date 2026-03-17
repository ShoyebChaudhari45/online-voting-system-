<?php
include 'db.php';

$error = '';
$success = '';

if(isset($_POST['register'])){
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass  = $_POST['password'];
    $cpass = $_POST['confirm_password'];

    // Validation
    if(empty($name) || empty($email) || empty($pass)){
        $error = "All fields are required!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Please enter a valid email address!";
    } elseif(strlen($pass) < 4){
        $error = "Password must be at least 4 characters!";
    } elseif($pass !== $cpass){
        $error = "Passwords do not match!";
    } else {
        // Check duplicate email
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='".mysqli_real_escape_string($conn, $email)."'");
        if(mysqli_num_rows($check) > 0){
            $error = "This email is already registered!";
        } else {
            $hashed = password_hash($pass, PASSWORD_DEFAULT);
            $name_esc  = mysqli_real_escape_string($conn, $name);
            $email_esc = mysqli_real_escape_string($conn, $email);

            $q = mysqli_query($conn, "INSERT INTO users(name, email, password) VALUES('$name_esc','$email_esc','$hashed')");
            if($q){
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voter Registration — VoteSecure</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🗳️ VoteSecure</div>
    <div class="nav-buttons">
        <a href="index.php">Home</a>
        <a href="login.php" class="nav-btn-primary">Login</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Voter Registration</h1>
    <p>Create your voter account to participate in elections</p>
</div>

<!-- REGISTRATION FORM -->
<div class="form-container">
    <h2>Register</h2>

    <?php if($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>
    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Full Name</label>
            <input type="text" name="name" placeholder="Enter your full name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" placeholder="Enter your email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Create a password" required>
        </div>

        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" placeholder="Re-enter password" required>
        </div>

        <button type="submit" name="register" class="btn">Create Account</button>
    </form>

    <div class="form-link">
        Already registered? <a href="login.php">Login here</a>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System
</div>

</body>
</html>