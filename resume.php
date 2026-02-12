<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

/* FETCH USER DATA */
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

/* SAVE RESUME CONTENT */
if(isset($_POST['save_resume'])){

    $objective = $_POST['objective'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $projects = $_POST['projects'] ?? '';

    $update = $conn->prepare("UPDATE users SET 
        objective=?, 
        skills_text=?, 
        projects=? 
        WHERE id=?");

    $update->bind_param("sssi",
        $objective,
        $skills,
        $projects,
        $userId
    );

    $update->execute();
    header("Location: resume.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Resume Builder</title>
<link rel="stylesheet" href="dashboard.css">
<style>
.form-section{
    background:white;
    padding:25px;
    border-radius:10px;
}
textarea{
    width:100%;
    min-height:100px;
    padding:10px;
    margin-bottom:15px;
}
button{
    padding:8px 14px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
button:hover{
    background:#4338ca;
}
</style>
</head>

<body>

<div class="app">

<!-- SIDEBAR -->
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

<!-- MAIN -->
<main class="main">

<h2>📄 Resume Builder</h2>
<p style="margin-bottom:20px;">Edit your resume content and download it as PDF.</p>

<div class="form-section">

<form method="POST">

    <label><strong>Career Objective</strong></label>
    <textarea name="objective"><?php echo $user['objective'] ?? ''; ?></textarea>

    <label><strong>Skills</strong></label>
    <textarea name="skills"><?php echo $user['skills_text'] ?? ''; ?></textarea>

    <label><strong>Projects</strong></label>
    <textarea name="projects"><?php echo $user['projects'] ?? ''; ?></textarea>

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