<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if(isset($_POST['save_resume'])){
    $objective=$_POST['objective']??'';
    $skills=$_POST['skills']??'';
    $projects=$_POST['projects']??'';

    $update=$conn->prepare("UPDATE users SET objective=?,skills_text=?,projects=? WHERE id=?");
    $update->bind_param("sssi",$objective,$skills,$projects,$userId);
    $update->execute();
    header("Location: resume.php"); exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Resume Builder</title>
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
.form-section{background:rgba(255,255,255,0.05);padding:30px;border-radius:12px;}
textarea{width:100%;min-height:100px;padding:10px;margin:10px 0;border-radius:6px;border:none;}
button{padding:8px 14px;background:#6366f1;color:white;border:none;border-radius:6px;cursor:pointer;margin-top:10px;}
button:hover{background:#4f46e5;}
</style>
</head>
<body>
<div class="app">
<aside class="sidebar">
<h2 class="logo">CareerVault</h2>
<nav>
<a href="dashboard.php">🏠 Dashboard</a>
<a href="profile.php">👤 Profile</a>
<a href="resume.php" class="active">📄 Resume</a>
<a href="applied-jobs.php">💼 Applied Jobs</a>
<a href="saved-jobs.php">❤️ Saved Jobs</a>
<a href="settings.php">⚙ Settings</a>
</nav>
</aside>
<main class="main">
<h2>📄 Resume Builder</h2>
<div class="form-section">
<form method="POST">
<label><strong>Career Objective</strong></label>
<textarea name="objective"><?= $user['objective'] ?? '' ?></textarea>
<label><strong>Skills</strong></label>
<textarea name="skills"><?= $user['skills_text'] ?? '' ?></textarea>
<label><strong>Projects</strong></label>
<textarea name="projects"><?= $user['projects'] ?? '' ?></textarea>
<button type="submit" name="save_resume">Save Resume</button>
</form>
<br>
<form method="POST" action="generate-resume.php">
<button type="submit">⬇ Download as PDF</button>
</form>
</div>
</main>
</div>
</body>
</html>