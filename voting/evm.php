<?php
include 'db.php';

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if user already voted
$uq = mysqli_query($conn, "SELECT voted FROM users WHERE id=$user_id");
$user = mysqli_fetch_assoc($uq);

// Get election status
$sq = mysqli_query($conn, "SELECT status FROM election_status LIMIT 1");
$status_row = mysqli_fetch_assoc($sq);
$election_status = $status_row ? $status_row['status'] : 'not_started';

$error = '';
$voted_already = ($user['voted'] == 1);

// Handle vote submission
if(isset($_POST['vote']) && !$voted_already && $election_status == 'ongoing'){
    $candidate_id = intval($_POST['candidate_id']);

    // Verify candidate exists
    $cq = mysqli_query($conn, "SELECT id FROM candidates WHERE id=$candidate_id");
    if(mysqli_num_rows($cq) > 0){
        // Update candidate votes
        mysqli_query($conn, "UPDATE candidates SET votes = votes + 1 WHERE id=$candidate_id");
        // Mark user as voted
        mysqli_query($conn, "UPDATE users SET voted = 1 WHERE id=$user_id");

        header("Location: vote_success.php");
        exit;
    } else {
        $error = "Invalid candidate!";
    }
}

// Get candidates
$candidates = mysqli_query($conn, "SELECT * FROM candidates ORDER BY id ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVM Voting — VoteSecure</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🗳️ VoteSecure</div>
    <div class="nav-buttons">
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>EVM Voting Machine</h1>
    <p>Select your candidate and cast your vote securely</p>
</div>

<!-- VOTING AREA -->
<div class="container" style="max-width:650px;">
    <h2>Vote Your Candidate</h2>

    <?php if($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if($election_status != 'ongoing'): ?>
        <div class="alert alert-warning">
            <?php if($election_status == 'ended'): ?>
                ⚠️ The election has ended. Voting is no longer available.
            <?php else: ?>
                ⚠️ The election has not started yet. Please wait for the admin to start the election.
            <?php endif; ?>
        </div>
    <?php elseif($voted_already): ?>
        <div class="alert alert-info">
            ✅ You have already cast your vote. You cannot vote again.
        </div>
    <?php else: ?>
        <?php if(mysqli_num_rows($candidates) == 0): ?>
            <div class="alert alert-warning">No candidates have been added yet.</div>
        <?php else: ?>
            <?php $i = 1; ?>
            <?php while($c = mysqli_fetch_assoc($candidates)): ?>
                <div class="candidate-row">
                    <div class="candidate-info">
                        <div class="candidate-number"><?= $i ?></div>
                        <div>
                            <div class="candidate-name"><?= htmlspecialchars($c['name']) ?></div>
                            <div class="candidate-party"><?= htmlspecialchars($c['party']) ?></div>
                        </div>
                    </div>
                    <form method="POST" action="" style="margin:0;">
                        <input type="hidden" name="candidate_id" value="<?= $c['id'] ?>">
                        <button type="submit" name="vote" class="vote-btn" onclick="return confirm('Are you sure you want to vote for <?= htmlspecialchars($c['name'], ENT_QUOTES) ?>? This action cannot be undone.')">VOTE</button>
                    </form>
                </div>
            <?php $i++; endwhile; ?>
        <?php endif; ?>
    <?php endif; ?>

    <div class="text-center mt-20">
        <a href="dashboard.php" class="btn btn-small" style="text-decoration:none;">← Back to Dashboard</a>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System
</div>

</body>
</html>
