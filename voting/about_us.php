<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us — VoteSecure</title>
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
    <h1>About Us</h1>
    <p>Learn about VoteSecure — Online Voting System</p>
</div>

<!-- CONTENT -->
<div class="container" style="max-width:800px;">
    <div class="about-section">
        <h2>🏛️ Who We Are</h2>
        <p>
            We are Computer Science students who developed the VoteSecure Online Voting System
            as an academic project to demonstrate secure, transparent, and tamper-proof digital voting.
            Our goal is to show how technology can make democracy more accessible.
        </p>
    </div>

    <div class="about-section">
        <h2>🎯 Our Mission</h2>
        <p>
            Our mission is to make the voting process simple, reliable, and fair using modern web technologies.
            VoteSecure ensures one voter — one vote, with real-time results and admin oversight.
        </p>
    </div>

    <div class="about-section">
        <h2>⚡ Key Features</h2>
        <p>
            ✅ Secure voter registration with password hashing<br>
            ✅ One person — one vote enforcement<br>
            ✅ Real-time vote counting and results<br>
            ✅ Admin panel with election controls<br>
            ✅ Beautiful, responsive modern UI<br>
            ✅ Candidate management with maximum 4 candidates
        </p>
    </div>

    <!-- RATING SECTION -->
    <div class="rating-section">
        <h2>⭐ Rate Our Project</h2>
        <div class="stars" id="stars">
            <span data-value="1">★</span>
            <span data-value="2">★</span>
            <span data-value="3">★</span>
            <span data-value="4">★</span>
            <span data-value="5">★</span>
        </div>
        <div class="rating-text" id="ratingText">Click on stars to rate</div>
    </div>
</div>

<!-- FOOTER -->
<div class="footer">
    © 2026 VoteSecure — Online Voting System
</div>

<!-- STAR RATING JS -->
<script>
    const stars = document.querySelectorAll(".stars span");
    const text = document.getElementById("ratingText");

    stars.forEach(star => {
        star.addEventListener("click", () => {
            const value = star.getAttribute("data-value");
            stars.forEach(s => s.classList.remove("active"));
            for(let i = 0; i < value; i++){
                stars[i].classList.add("active");
            }
            text.innerText = "You rated this project " + value + " star(s). Thank you! 🎉";
        });

        star.addEventListener("mouseenter", () => {
            const value = star.getAttribute("data-value");
            stars.forEach((s, idx) => {
                if(idx < value) s.style.color = '#f6e05e';
                else s.style.color = '';
            });
        });

        star.addEventListener("mouseleave", () => {
            stars.forEach(s => s.style.color = '');
        });
    });
</script>

</body>
</html>
