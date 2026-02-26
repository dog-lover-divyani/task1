<?php
session_start();
include("db.php");
if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
$userId=$_SESSION['user_id'];

$query = $conn->prepare("
SELECT j.id, j.job_title, j.location, ja.applied_at, ja.status
FROM job_applications ja
INNER JOIN jobs j ON ja.job_id = j.id
WHERE ja.user_id = ?
ORDER BY ja.applied_at DESC
");
$query->bind_param("i",$userId);
$query->execute();
$result=$query->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>Applied Jobs</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{
    margin:0;
    padding:0;
    font-family:'Segoe UI',sans-serif;
    color:white;
    background: 
        linear-gradient(rgba(15,23,42,0.85), rgba(15,23,42,0.85)),
        url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=2070');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
}.app{display:flex;min-height:100vh;}
.sidebar{width:240px;background:rgba(255,255,255,0.05);backdrop-filter:blur(10px);padding:30px 20px;}
.logo{margin-bottom:30px;}
.sidebar a{display:block;padding:10px;margin-bottom:10px;color:white;text-decoration:none;border-radius:6px;transition:.3s;}
.sidebar a:hover,.sidebar a.active{background:#6366f1;}
.main{flex:1;padding:40px;}
.card{background:rgba(255,255,255,0.05);padding:20px;border-radius:10px;margin-bottom:15px;}
</style>
</head>
<body>
<div class="app">
<aside class="sidebar">
<h2 class="logo">CareerVault</h2>
<nav>
<a href="dashboard.php">🏠 Dashboard</a>
<a href="profile.php">👤 Profile</a>
<a href="resume.php">📄 Resume</a>
<a href="applied-jobs.php" class="active">💼 Applied Jobs</a>
<a href="saved-jobs.php">❤️ Saved Jobs</a>
<a href="settings.php">⚙ Settings</a>
</nav>
</aside>
<main class="main">
<h2>💼 Applied Jobs</h2>
<?php if($result->num_rows>0): while($row=$result->fetch_assoc()): ?>
<div class="card">
<h3><?= htmlspecialchars($row['job_title']) ?></h3>
<p><?= htmlspecialchars($row['location']) ?></p>
<small>Applied on: <?= date("d M Y",strtotime($row['applied_at'])) ?></small>
</div>
<?php endwhile; else: ?>
<div class="card"><p>You haven't applied to any jobs yet.</p></div>
<?php endif; ?>
</main>
</div>
</body>
</html>