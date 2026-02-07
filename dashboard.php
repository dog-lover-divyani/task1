<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM users WHERE id='$userId'");
$user = $result->fetch_assoc();
?>

<h2>Welcome back, <?php echo $user['full_name']; ?> 👋</h2>
<p>Profile Completion: <?php echo $user['profile_completion']; ?>%</p>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>CareerVault Dashboard</title>
  <link rel="stylesheet" href="dashboard.css" />
</head>
<body>

<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2 class="logo">CareerVault</h2>

    <nav>
      <a class="active">🏠 Dashboard</a>
      <a>👤 My Profile</a>
      <a>📄 My Resume</a>
      <a>💼 Jobs</a>
      <a>❤️ Saved</a>
      <a>📁 Applications</a>
      <a>⚙️ Settings</a>
    </nav>

    <div class="user-box">
      <img src="https://i.pravatar.cc/100" />
      <p class="name">Divy</p>
      <p class="email">divy@example.com</p>
      <button class="logout">Logout</button>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main">
  
    <!-- TOP BAR -->
    <div class="topbar">
      <h3>Dashboard</h3>
      <input type="text" placeholder="Search here..." />
      <div class="user">
        🔔 <img src="https://i.pravatar.cc/40" /> Divy ⌄
      </div>
    </div>

    <!-- WELCOME -->
    <section class="welcome">
      <div>
        <h1>Welcome back, Divy 👋</h1>
        <p>Your career dashboard at a glance</p>
      </div>
      <button class="primary">Complete Profile</button>
    </section>

    <!-- STATS -->
    <section class="stats">
      <div class="card"><h2>75%</h2><p>Profile Completion</p></div>
      <div class="card"><h2>10</h2><p>Applied Jobs</p></div>
      <div class="card"><h2>6</h2><p>Saved Jobs</p></div>
      <div class="card"><h2>2</h2><p>Interview Calls</p></div>
    </section>

    <!-- RESUME -->
    <section class="resume card wide">
      <div>
        <h3>My Resume</h3>
        <p>resume_divy.pdf · Updated today</p>
      </div>
      <button class="primary">Apply Now</button>
    </section>

    <!-- JOBS -->
    <h3 class="section-title">Recommended Jobs</h3>

    <section class="job card wide">
      <div>
        <h4>Frontend Developer</h4>
        <p>Tech Innovations · Remote</p>
      </div>
      <button class="primary">Apply Now</button>
    </section>

    <section class="job card wide">
      <div>
        <h4>UI/UX Designer</h4>
        <p>Creative Minds · Bangalore</p>
      </div>
      <button class="primary">Apply Now</button>
    </section>

  </main>
</div>

<script src="dashboard.js"></script>
</body>
</html>
