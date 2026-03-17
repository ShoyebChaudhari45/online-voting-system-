<?php
include 'db.php';

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id   = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Get user data
$uq = mysqli_query($conn, "SELECT * FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($uq);

// Get election status
$sq = mysqli_query($conn, "SELECT status FROM election_status LIMIT 1");
$status_row = mysqli_fetch_assoc($sq);
$election_status = $status_row ? $status_row['status'] : 'not_started';

// Count stats
$total_candidates = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM candidates"));
$total_voters     = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$total_voted      = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE voted=1"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — VoteSecure</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🗳️ VoteSecure</div>
    <div class="nav-buttons">
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Welcome, <?= htmlspecialchars($user_name) ?>!</h1>
    <p>
        <?php if($user['voted']): ?>
            ✅ You have already cast your vote. Thank you!
        <?php else: ?>
            You have not voted yet. Cast your vote now!
        <?php endif; ?>
    </p>
</div>

<!-- DASHBOARD CONTENT -->
<div class="container">
    <h2>Voter Dashboard</h2>

    <!-- STATS -->
    <div class="admin-grid">
        <div class="admin-stat">
            <div class="stat-number"><?= $total_candidates ?></div>
            <div class="stat-label">Candidates</div>
        </div>
        <div class="admin-stat">
            <div class="stat-number"><?= $total_voters ?></div>
            <div class="stat-label">Registered Voters</div>
        </div>
        <div class="admin-stat">
            <div class="stat-number"><?= $total_voted ?></div>
            <div class="stat-label">Votes Cast</div>
        </div>
        <div class="admin-stat">
            <div class="stat-number">
                <?php
                    if($election_status == 'ongoing') echo '<span class="badge badge-success">ONGOING</span>';
                    elseif($election_status == 'ended') echo '<span class="badge badge-warning">ENDED</span>';
                    elseif($election_status == 'result_declared') echo '<span class="badge badge-success">RESULTS DECLARED</span>';
                    else echo '<span class="badge badge-warning">NOT STARTED</span>';
                ?>
            </div>
            <div class="stat-label">Election Status</div>
        </div>
    </div>

    <!-- ACTION CARDS -->
    <div class="cards">
        <div class="card">
            <div class="card-icon">🗳️</div>
            <h3>Cast Your Vote</h3>
            <p>Vote for your preferred candidate on the EVM page</p>
            <?php if($user['voted']): ?>
                <span class="badge badge-success">Already Voted ✓</span>
            <?php elseif($election_status == 'ongoing'): ?>
                <a href="evm.php" class="card-btn">Vote Now</a>
            <?php elseif($election_status == 'ended'): ?>
                <span class="badge badge-danger">Election Ended</span>
            <?php else: ?>
                <span class="badge badge-warning">Not Started</span>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-icon">📊</div>
            <h3>View Results</h3>
            <p>See the final vote count and winner</p>
            <?php if($election_status == 'result_declared'): ?>
                <a href="result.php" class="card-btn">View Results</a>
            <?php else: ?>
                <span class="badge badge-warning">Results Pending</span>
            <?php endif; ?>
        </div>

        <div class="card">
            <div class="card-icon">ℹ️</div>
            <h3>About Us</h3>
            <p>Learn more about this voting platform</p>
            <a href="about_us.php" class="card-btn">About</a>
        </div>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System
</div>

</body>
</html>
