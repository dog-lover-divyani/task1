<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

/* ================= REMOVE SAVED JOB ================= */
if(isset($_GET['remove'])){
    $jobId = $_GET['remove'];

    $stmt = $conn->prepare("DELETE FROM saved_jobs WHERE user_id=? AND job_id=?");
    $stmt->bind_param("ii", $userId, $jobId);
    $stmt->execute();

    header("Location: saved-jobs.php");
    exit();
}

/* ================= FETCH SAVED JOBS ================= */
$query = $conn->prepare("
    SELECT j.id, j.job_title, j.location, sj.saved_at
    FROM saved_jobs sj
    JOIN jobs j ON sj.job_id = j.id
    WHERE sj.user_id = ?
    ORDER BY sj.saved_at DESC
");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Saved Jobs</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="app">

<!-- SIDEBAR -->
<aside class="sidebar">
    <h2 class="logo">CareerVault</h2>
    <nav>
        <a href="dashboard.php">🏠 Dashboard</a>
        <a href="profile.php">👤 Profile</a>
        <a href="resume.php">📄 Resume</a>
        <a href="applied-jobs.php">💼 Applied Jobs</a>
        <a href="saved-jobs.php" class="active">❤️ Saved Jobs</a>
        <a href="settings.php">⚙ Settings</a>
    </nav>
</aside>

<!-- MAIN CONTENT -->
<main class="main">

<h2>❤️ Saved Jobs</h2>
<p style="margin-bottom:20px;">Jobs you saved for later.</p>

<?php if ($result->num_rows > 0) { ?>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="card">
            <h3><?= htmlspecialchars($row['job_title']) ?></h3>
            <p><?= htmlspecialchars($row['location']) ?></p>
            <small>Saved on: <?= date("d M Y", strtotime($row['saved_at'])) ?></small>

            <div style="margin-top:10px;">
                <a href="saved-jobs.php?remove=<?= $row['id'] ?>" 
                   style="color:red; text-decoration:none;">
                    ❌ Remove
                </a>
            </div>
        </div>
    <?php } ?>

<?php } else { ?>

    <div class="card wide">
        <p>You haven't saved any jobs yet.</p>
    </div>

<?php } ?>

</main>

</div>

</body>
</html>