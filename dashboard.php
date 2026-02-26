<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

/* ===========================
   HANDLE APPLY
=========================== */
if (isset($_POST['apply_job'])) {

    $jobId = $_POST['job_id'];

    $check = $conn->prepare("SELECT id FROM job_applications WHERE user_id=? AND job_id=?");
    $check->bind_param("ii", $userId, $jobId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        $insert = $conn->prepare("INSERT INTO job_applications (user_id, job_id) VALUES (?, ?)");
        $insert->bind_param("ii", $userId, $jobId);
        $insert->execute();
    }
}

/* ===========================
   HANDLE SAVE
=========================== */
if (isset($_POST['save_job'])) {

    $jobId = $_POST['job_id'];

    $check = $conn->prepare("SELECT id FROM saved_jobs WHERE user_id=? AND job_id=?");
    $check->bind_param("ii", $userId, $jobId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        $insert = $conn->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)");
        $insert->bind_param("ii", $userId, $jobId);
        $insert->execute();
    }
}

/* ===========================
   APPLIED JOBS
=========================== */
$appliedJobs = [];
$appQuery = $conn->prepare("SELECT job_id FROM job_applications WHERE user_id=?");
$appQuery->bind_param("i", $userId);
$appQuery->execute();
$res = $appQuery->get_result();
while ($row = $res->fetch_assoc()) {
    $appliedJobs[] = $row['job_id'];
}

/* ===========================
   SAVED JOBS
=========================== */
$savedJobs = [];
$saveQuery = $conn->prepare("SELECT job_id FROM saved_jobs WHERE user_id=?");
$saveQuery->bind_param("i", $userId);
$saveQuery->execute();
$resSave = $saveQuery->get_result();
while ($row = $resSave->fetch_assoc()) {
    $savedJobs[] = $row['job_id'];
}

/* ===========================
   USER DATA
=========================== */
$userQuery = $conn->prepare("SELECT * FROM users WHERE id=?");
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$user = $userQuery->get_result()->fetch_assoc();

/* ===========================
   EDUCATION / EXPERIENCE / SKILLS COUNT
=========================== */
$eduQuery = $conn->prepare("SELECT id FROM education WHERE user_id=?");
$eduQuery->bind_param("i", $userId);
$eduQuery->execute();
$eduQuery->store_result();
$educationCount = $eduQuery->num_rows;

$expQuery = $conn->prepare("SELECT id FROM experience WHERE user_id=?");
$expQuery->bind_param("i", $userId);
$expQuery->execute();
$expQuery->store_result();
$experienceCount = $expQuery->num_rows;

$skillQuery = $conn->prepare("SELECT id FROM skills WHERE user_id=?");
$skillQuery->bind_param("i", $userId);
$skillQuery->execute();
$skillQuery->store_result();
$skillsCount = $skillQuery->num_rows;

/* ===========================
   PROFILE COMPLETION
=========================== */
$totalFields = 13;
$filled = 0;

if (!empty($user['profile_pic'])) $filled++;
if (!empty($user['full_name'])) $filled++;
if (!empty($user['email'])) $filled++;
if (!empty($user['phone'])) $filled++;
if (!empty($user['dob'])) $filled++;
if (!empty($user['headline'])) $filled++;
if (!empty($user['address'])) $filled++;
if (!empty($user['bio'])) $filled++;
if (!empty($user['linkedin'])) $filled++;

if ($educationCount > 0) $filled++;
if ($experienceCount > 0) $filled++;
if ($skillsCount > 0) $filled++;

$completion = round(($filled / $totalFields) * 100);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>CareerVault Dashboard</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background:
        linear-gradient(rgba(10,15,40,0.85), rgba(10,15,40,0.95)),
        url('https://images.unsplash.com/photo-1470770841072-f978cf4d019e')
        no-repeat center center fixed;
    background-size: cover;
    color: white;
}

.app { display: flex; }

.sidebar {
    width: 250px;
    background: rgba(0,0,0,0.6);
    padding: 20px;
    min-height: 100vh;
}

.sidebar a {
    display: block;
    color: white;
    padding: 10px;
    text-decoration: none;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.1);
    border-radius: 5px;
}

.user-box {
    margin-top: 30px;
    text-align: center;
}

.user-box img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
}

.default-avatar {
    font-size: 40px;
}

.main {
    flex: 1;
    padding: 30px;
}

.card {
    background: rgba(0,0,0,0.5);
    padding: 20px;
    margin-bottom: 15px;
    border-radius: 10px;
}

.stats {
    display: flex;
    gap: 20px;
}

.progress-bar {
    background: #333;
    border-radius: 20px;
    overflow: hidden;
    margin-top: 10px;
    height: 12px;
}

.progress-fill {
    background: #6c63ff;
    height: 100%;
}

.primary {
    background: #6c63ff;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
}

.primary:hover { background: #5848e5; }

.save-btn {
    background: #ff4d6d;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    margin-left: 10px;
}

.save-btn:hover { background: #e63956; }

.logout {
    background: crimson;
    color: white;
    border: none;
    padding: 8px 12px;
    cursor: pointer;
    border-radius: 5px;
}
</style>
</head>

<body>

<div class="app">

<aside class="sidebar">
    <h2>CareerVault</h2>

    <nav>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="profile.php">👤 Profile</a>
        <a href="resume.php">📄 Resume</a>
        <a href="applied-jobs.php">💼 Applied Jobs</a>
        <a href="saved-jobs.php">❤️ Saved Jobs</a>
        <a href="settings.php">⚙ Settings</a>
    </nav>

    <div class="user-box">
        <?php if (!empty($user['profile_pic'])) { ?>
            <img src="<?= $user['profile_pic']; ?>" alt="Profile">
        <?php } else { ?>
            <div class="default-avatar">👤</div>
        <?php } ?>

        <p><?= htmlspecialchars($user['full_name']); ?></p>
        <p><?= htmlspecialchars($user['email']); ?></p>

        <form action="logout.php" method="POST">
            <button class="logout">Logout</button>
        </form>
    </div>
</aside>

<main class="main">

<section class="welcome">
    <h1>Welcome, <?= htmlspecialchars($user['full_name']); ?> 👋</h1>
    <p>Explore opportunities and grow your career.</p>
</section>

<section class="stats">
    <div class="card">
        <h2><?= $completion ?>%</h2>
        <p>Profile Completion</p>

        <div class="progress-bar">
            <div class="progress-fill" style="width: <?= $completion ?>%;"></div>
        </div>
    </div>

    <div class="card">
        <h2><?= count($appliedJobs) ?></h2>
        <p>Applied Jobs</p>
    </div>
</section>

<h3>Recommended Jobs</h3>

<?php
$jobsQuery = $conn->query("SELECT id, job_title, company, location FROM jobs");

while ($job = $jobsQuery->fetch_assoc()) {

    $applied = in_array($job['id'], $appliedJobs);
    $saved = in_array($job['id'], $savedJobs);
?>

<form method="POST" class="card">
    <h4><?= htmlspecialchars($job['job_title']); ?></h4>
    <p><?= htmlspecialchars($job['company']); ?> · <?= htmlspecialchars($job['location']); ?></p>

    <input type="hidden" name="job_id" value="<?= $job['id']; ?>">

    <button class="primary" name="apply_job" <?= $applied ? 'disabled' : ''; ?>>
        <?= $applied ? "Applied ✓" : "Apply Now"; ?>
    </button>

    <button class="save-btn" name="save_job" <?= $saved ? 'disabled' : ''; ?>>
        <?= $saved ? "Saved ❤️" : "Save Job"; ?>
    </button>
</form>

<?php } ?>

</main>
</div>

</body>
</html>