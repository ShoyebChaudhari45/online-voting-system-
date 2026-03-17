<?php
include 'db.php';

// Get election status
$sq = mysqli_query($conn, "SELECT status FROM election_status LIMIT 1");
$status_row = mysqli_fetch_assoc($sq);
$election_status = $status_row ? $status_row['status'] : 'not_started';

// Get candidates ordered by votes (descending)
$candidates = mysqli_query($conn, "SELECT * FROM candidates ORDER BY votes DESC");
$total_votes_q = mysqli_query($conn, "SELECT SUM(votes) as total FROM candidates");
$total_row = mysqli_fetch_assoc($total_votes_q);
$total_votes = $total_row['total'] ? $total_row['total'] : 0;

// Get winner
$winner_q = mysqli_query($conn, "SELECT * FROM candidates ORDER BY votes DESC LIMIT 1");
$winner = mysqli_fetch_assoc($winner_q);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Election Results — VoteSecure</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🗳️ VoteSecure</div>
    <div class="nav-buttons">
        <a href="index.php">Home</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
        <?php endif; ?>
    </div>
</div>

<!-- HERO -->
<div class="hero">
    <h1>Election Results</h1>
    <p>
        <?php if($election_status == 'result_declared'): ?>
            Final election results are in!
        <?php elseif($election_status == 'ended' || $election_status == 'ongoing'): ?>
            Results will be declared soon
        <?php else: ?>
            Election has not started yet
        <?php endif; ?>
    </p>
</div>

<!-- RESULTS CONTENT -->
<div class="container" style="max-width:750px;">

    <?php if($election_status == 'result_declared'): ?>
        <h2>Vote Count</h2>

        <?php if(mysqli_num_rows($candidates) == 0): ?>
            <div class="alert alert-warning">No candidates found.</div>
        <?php else: ?>

            <!-- WINNER ANNOUNCEMENT -->
            <?php if($winner && $winner['votes'] > 0): ?>
                <div class="alert alert-success" style="font-size:18px; padding:20px;">
                    🏆 <strong>Winner: <?= htmlspecialchars($winner['name']) ?></strong> (<?= htmlspecialchars($winner['party']) ?>) — <?= $winner['votes'] ?> votes
                </div>
            <?php endif; ?>

            <!-- RESULT ROWS -->
            <?php
            $rank = 1;
            mysqli_data_seek($candidates, 0);
            while($c = mysqli_fetch_assoc($candidates)):
                $percentage = ($total_votes > 0) ? round(($c['votes'] / $total_votes) * 100, 1) : 0;
                $is_winner = ($winner && $c['id'] == $winner['id'] && $c['votes'] > 0);
            ?>
                <div class="result-row <?= $is_winner ? 'winner-row' : '' ?>">
                    <div class="result-rank"><?= $rank ?></div>
                    <div class="result-details">
                        <div class="result-name">
                            <?= htmlspecialchars($c['name']) ?>
                            <?php if($is_winner): ?>
                                <span class="winner-badge">🏆 WINNER</span>
                            <?php endif; ?>
                        </div>
                        <div class="result-party"><?= htmlspecialchars($c['party']) ?></div>
                    </div>
                    <div class="result-bar-container">
                        <div class="result-bar" style="width: <?= max($percentage, 5) ?>%;">
                            <?= $percentage ?>%
                        </div>
                    </div>
                    <div class="result-votes"><?= $c['votes'] ?></div>
                </div>
            <?php $rank++; endwhile; ?>

            <!-- TOTAL -->
            <div style="text-align:center; margin-top:25px; padding-top:20px; border-top:1px solid rgba(255,255,255,0.1);">
                <span style="color:var(--text-muted); font-size:14px;">Total Votes Cast:</span>
                <span style="font-size:28px; font-weight:800; background:linear-gradient(135deg,var(--primary),var(--accent)); -webkit-background-clip:text; -webkit-text-fill-color:transparent; margin-left:10px;"><?= $total_votes ?></span>
            </div>

        <?php endif; ?>

    <?php elseif($election_status == 'not_started'): ?>
        <div class="alert alert-warning" style="text-align:center; font-size:16px; padding:30px;">
            🗳️ The election has not started yet. Results will appear here once the election is completed and results are declared.
        </div>

    <?php else: ?>
        <!-- ongoing or ended — results not declared yet -->
        <div style="text-align:center; padding:50px 20px;">
            <div style="font-size:64px; margin-bottom:20px;">⏳</div>
            <h2 style="margin-bottom:10px;">Results Will Be Declared Soon</h2>
            <p style="color:var(--text-muted); font-size:16px; max-width:400px; margin:0 auto;">
                The election results are being finalized. Please check back later once the admin has declared the results.
            </p>
        </div>

    <?php endif; ?>

    <div class="text-center mt-20">
        <a href="<?= isset($_SESSION['user_id']) ? 'dashboard.php' : 'index.php' ?>" class="btn btn-small" style="text-decoration:none;">← Back</a>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System
</div>

</body>
</html>
