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

$eduStmt = $conn->prepare("SELECT * FROM education WHERE user_id=? ORDER BY created_at DESC");
$eduStmt->bind_param("i", $userId);
$eduStmt->execute();
$education = $eduStmt->get_result();

$expStmt = $conn->prepare("SELECT * FROM experience WHERE user_id=? ORDER BY created_at DESC");
$expStmt->bind_param("i", $userId);
$expStmt->execute();
$experience = $expStmt->get_result();

$skillStmt = $conn->prepare("SELECT * FROM skills WHERE user_id=? ORDER BY created_at DESC");
$skillStmt->bind_param("i", $userId);
$skillStmt->execute();
$skills = $skillStmt->get_result();

$profileImage = !empty($user['profile_pic']) 
    ? "uploads/profile-pic/" . $user['profile_pic']
    : "https://via.placeholder.com/120";
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family: 'Segoe UI', sans-serif;
}

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
}

.app{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */
.sidebar{
    width:230px;
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(10px);
    padding:30px 20px;
}

.logo{
    margin-bottom:30px;
}

.sidebar a{
    display:block;
    padding:10px;
    margin-bottom:10px;
    color:white;
    text-decoration:none;
    border-radius:6px;
    transition:0.3s;
}

.sidebar a:hover,
.sidebar a.active{
    background:#6366f1;
}

/* MAIN */
.main{
    flex:1;
    padding:40px;
}

.page-title{
    margin-bottom:20px;
}

.profile-card{
    background: rgba(255,255,255,0.05);
    padding:30px;
    border-radius:12px;
}

.profile-top{
    display:flex;
    align-items:flex-start;
    gap:25px;
    margin-bottom:20px;
}

.profile-avatar{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #6366f1;
}

.profile-details h2{
    margin-bottom:5px;
}

.muted{
    color:#cbd5e1;
    font-size:14px;
}

.headline{
    color:#a5b4fc;
    margin-top:5px;
}

.edit-btn{
    margin-left:auto;
    background:#6366f1;
    padding:8px 14px;
    border-radius:6px;
    text-decoration:none;
    color:white;
}

.profile-section{
    margin-top:25px;
}

.profile-section h3{
    margin-bottom:10px;
    color:#a5b4fc;
}

.entry{
    margin-bottom:15px;
}

.skills-wrap{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}

.skill-tag{
    background:#6366f1;
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
}
</style>

</head>

<body>
<div class="app">

<aside class="sidebar">
    <h2 class="logo">CareerVault</h2>
    <nav>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="profile.php" class="active">👤 Profile</a>
        <a href="resume.php">📄 Resume</a>
        <a href="applied-jobs.php">💼 Applied Jobs</a>
        <a href="saved-jobs.php">❤️ Saved Jobs</a>
        <a href="settings.php">⚙ Settings</a>
    </nav>
</aside>

<main class="main">

<h2 class="page-title">My Profile</h2>

<div class="profile-card">

<div class="profile-top">
<img src="<?php echo $profileImage; ?>" class="profile-avatar">

<div class="profile-details">
<h2><?= htmlspecialchars($user['full_name'] ?? 'No Name'); ?></h2>
<p class="muted"><?= htmlspecialchars($user['email']); ?></p>

<?php if(!empty($user['headline'])): ?>
<p class="headline"><?= htmlspecialchars($user['headline']); ?></p>
<?php endif; ?>

<?php if(!empty($user['phone'])): ?>
<p class="muted">📞 <?= htmlspecialchars($user['phone']); ?></p>
<?php endif; ?>

<?php if(!empty($user['dob'])): ?>
<p class="muted">🎂 <?= htmlspecialchars($user['dob']); ?></p>
<?php endif; ?>

<?php if(!empty($user['address'])): ?>
<p class="muted">📍 <?= htmlspecialchars($user['address']); ?></p>
<?php endif; ?>

<?php if(!empty($user['linkedin'])): ?>
<p><a href="<?= $user['linkedin']; ?>" target="_blank" style="color:#a5b4fc;">🔗 LinkedIn</a></p>
<?php endif; ?>

</div>

<a href="edit-profile.php" class="edit-btn">Edit Profile</a>
</div>

<?php if(!empty($user['bio'])): ?>
<div class="profile-section">
<h3>About</h3>
<p><?= htmlspecialchars($user['bio']); ?></p>
</div>
<?php endif; ?>

<div class="profile-section">
<h3>Education</h3>
<?php if ($education->num_rows > 0): ?>
<?php while ($row = $education->fetch_assoc()): ?>
<div class="entry">
<h4><?= htmlspecialchars($row['degree']); ?></h4>
<p><?= htmlspecialchars($row['institution']); ?></p>
<span class="muted"><?= htmlspecialchars($row['start_year']); ?> - <?= htmlspecialchars($row['end_year']); ?></span>
</div>
<?php endwhile; ?>
<?php else: ?>
<p class="muted">No education added yet.</p>
<?php endif; ?>
</div>

<div class="profile-section">
<h3>Experience</h3>
<?php if ($experience->num_rows > 0): ?>
<?php while ($row = $experience->fetch_assoc()): ?>
<div class="entry">
<h4><?= htmlspecialchars($row['job_title']); ?></h4>
<p><?= htmlspecialchars($row['company']); ?></p>
<span class="muted"><?= htmlspecialchars($row['start_date']); ?> - <?= htmlspecialchars($row['end_date']); ?></span>
<p><?= htmlspecialchars($row['description']); ?></p>
</div>
<?php endwhile; ?>
<?php else: ?>
<p class="muted">No experience added yet.</p>
<?php endif; ?>
</div>

<div class="profile-section">
<h3>Skills</h3>
<?php if ($skills->num_rows > 0): ?>
<div class="skills-wrap">
<?php while ($row = $skills->fetch_assoc()): ?>
<span class="skill-tag"><?= htmlspecialchars($row['skill_name']); ?></span>
<?php endwhile; ?>
</div>
<?php else: ?>
<p class="muted">No skills added yet.</p>
<?php endif; ?>
</div>

</div>
</main>
</div>
</body>
</html>