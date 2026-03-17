<?php
include 'db.php';

// Check admin session
if(!isset($_SESSION['admin'])){
    header("Location: admin_login.php");
    exit;
}

$success = '';
$error = '';

// Handle Add Candidate
if(isset($_POST['add_candidate'])){
    $cname  = trim($_POST['candidate_name']);
    $cparty = trim($_POST['candidate_party']);

    if(empty($cname) || empty($cparty)){
        $error = "Please fill in both candidate name and party!";
    } else {
        // Check max 4 candidates
        $count_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM candidates");
        $count   = mysqli_fetch_assoc($count_q)['cnt'];

        if($count >= 4){
            $error = "Maximum 4 candidates allowed! Please delete one first.";
        } else {
            $cname_esc  = mysqli_real_escape_string($conn, $cname);
            $cparty_esc = mysqli_real_escape_string($conn, $cparty);

            $q = mysqli_query($conn, "INSERT INTO candidates(name, party) VALUES('$cname_esc','$cparty_esc')");
            if($q){
                $success = "Candidate '$cname' added successfully!";
            } else {
                $error = "Failed to add candidate.";
            }
        }
    }
}

// Handle Delete Candidate
if(isset($_GET['delete_candidate'])){
    $del_id = intval($_GET['delete_candidate']);
    mysqli_query($conn, "DELETE FROM candidates WHERE id=$del_id");
    header("Location: admin_dashboard.php");
    exit;
}

// Handle Start Election
if(isset($_POST['start_election'])){
    $count_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM candidates");
    $count   = mysqli_fetch_assoc($count_q)['cnt'];
    if($count < 2){
        $error = "You need at least 2 candidates to start the election!";
    } else {
        mysqli_query($conn, "UPDATE election_status SET status='ongoing'");
        $success = "Election started! Voters can now cast their votes.";
    }
}

// Handle End Election
if(isset($_POST['end_election'])){
    mysqli_query($conn, "UPDATE election_status SET status='ended'");
    $success = "Election ended! You can now declare results when ready.";
}

// Handle Declare Results
if(isset($_POST['declare_results'])){
    mysqli_query($conn, "UPDATE election_status SET status='result_declared'");
    $success = "Results declared! Voters can now see the election results.";
}

// Handle Reset Election (only after results are declared)
if(isset($_POST['reset_election'])){
    mysqli_query($conn, "UPDATE election_status SET status='not_started'");
    mysqli_query($conn, "UPDATE candidates SET votes=0");
    mysqli_query($conn, "UPDATE users SET voted=0");
    mysqli_query($conn, "DELETE FROM candidates");
    $success = "Election reset! All votes and candidates have been cleared.";
}

// Get election status
$sq = mysqli_query($conn, "SELECT status FROM election_status LIMIT 1");
$status_row = mysqli_fetch_assoc($sq);
$election_status = $status_row ? $status_row['status'] : 'not_started';

// Get candidates
$candidates = mysqli_query($conn, "SELECT * FROM candidates ORDER BY id ASC");
$candidate_count = mysqli_num_rows($candidates);

// Stats
$total_voters = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users"));
$total_voted  = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM users WHERE voted=1"));
$total_votes_q = mysqli_query($conn, "SELECT SUM(votes) as total FROM candidates");
$total_votes   = mysqli_fetch_assoc($total_votes_q)['total'] ?: 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — VoteSecure</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">🗳️ <span>VoteSecure</span> — Admin</div>
    <div class="nav-buttons">
        <a href="result.php">View Results</a>
        <a href="admin_logout.php" style="background:linear-gradient(135deg,#e53e3e,#c53030); color:white; border:none;">Logout</a>
    </div>
</div>

<!-- HERO -->
<div class="hero" style="padding:50px 20px 30px;">
    <h1>Admin Dashboard</h1>
    <p>Manage candidates, control elections, and view results</p>
</div>

<!-- DASHBOARD CONTENT -->
<div class="container" style="max-width:900px;">

    <?php if($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if($error): ?>
        <div class="alert alert-error"><?= $error ?></div>
    <?php endif; ?>

    <!-- STATS -->
    <div class="admin-grid" style="grid-template-columns: repeat(4, 1fr);">
        <div class="admin-stat">
            <div class="stat-number"><?= $candidate_count ?></div>
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

    <!-- ELECTION CONTROLS -->
    <h2 style="margin-top:30px;">⚙️ Election Controls</h2>
    <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap; margin-bottom:30px;">
        <?php if($election_status == 'not_started'): ?>
            <form method="POST" style="margin:0;">
                <button type="submit" name="start_election" class="btn btn-success btn-small">▶ Start Election</button>
            </form>
        <?php elseif($election_status == 'ongoing'): ?>
            <form method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to end the election?')">
                <button type="submit" name="end_election" class="btn btn-danger btn-small">⏹ End Election</button>
            </form>
        <?php elseif($election_status == 'ended'): ?>
            <form method="POST" style="margin:0;" onsubmit="return confirm('Declare results? Voters will be able to see the election results.')">
                <button type="submit" name="declare_results" class="btn btn-success btn-small">📢 Declare Results</button>
            </form>
        <?php endif; ?>
        <?php if($election_status == 'result_declared'): ?>
            <form method="POST" style="margin:0;" onsubmit="return confirm('This will delete ALL candidates, votes, and reset everything. Continue?')">
                <button type="submit" name="reset_election" class="btn btn-danger btn-small">🔄 Reset Election</button>
            </form>
            <a href="result.php" class="btn btn-small" style="text-decoration:none;">📊 View Final Results</a>
        <?php endif; ?>
    </div>

    <!-- ADD CANDIDATE FORM -->
    <?php if($election_status == 'not_started'): ?>
        <h2>➕ Add Candidate</h2>
        <?php if($candidate_count >= 4): ?>
            <div class="alert alert-warning">Maximum 4 candidates reached. Delete a candidate to add a new one.</div>
        <?php else: ?>
            <form method="POST" action="" style="max-width:500px; margin:0 auto 25px;">
                <div style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
                    <div class="form-group" style="flex:1; margin-bottom:0; min-width:150px;">
                        <label>Candidate Name</label>
                        <input type="text" name="candidate_name" placeholder="Enter name" required>
                    </div>
                    <div class="form-group" style="flex:1; margin-bottom:0; min-width:150px;">
                        <label>Party Name</label>
                        <input type="text" name="candidate_party" placeholder="Enter party" required>
                    </div>
                    <button type="submit" name="add_candidate" class="btn btn-success btn-small" style="margin-bottom:0; height:46px;">Add</button>
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <!-- CANDIDATES TABLE -->
    <h2>📋 Candidates List</h2>
    <?php if($candidate_count == 0): ?>
        <div class="alert alert-info">No candidates added yet.</div>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Candidate Name</th>
                    <th>Party</th>
                    <th>Votes</th>
                    <?php if($election_status == 'not_started'): ?><th>Action</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; while($c = mysqli_fetch_assoc($candidates)): ?>
                <tr>
                    <td><?= $i ?></td>
                    <td><strong><?= htmlspecialchars($c['name']) ?></strong></td>
                    <td><?= htmlspecialchars($c['party']) ?></td>
                    <td><?= $c['votes'] ?></td>
                    <?php if($election_status == 'not_started'): ?>
                    <td>
                        <a href="admin_dashboard.php?delete_candidate=<?= $c['id'] ?>" onclick="return confirm('Delete this candidate?')" style="color:#fc5c65; text-decoration:none; font-weight:700;">✕ Delete</a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php $i++; endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System
</div>

</body>
</html>
