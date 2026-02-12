<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

$query = $conn->prepare("
    SELECT j.job_title, j.location, ja.applied_at
    FROM job_applications ja
    JOIN jobs j ON ja.job_id = j.id
    WHERE ja.user_id = ?
    ORDER BY ja.applied_at DESC
");
$query->bind_param("i", $userId);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Applied Jobs</title>
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
            <a href="applied-jobs.php" class="active">💼 Applied Jobs</a>
            <a href="saved-jobs.php">❤️ Saved Jobs</a>
            <a href="settings.php">⚙ Settings</a>
        </nav>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main">
        <h2>💼 Applied Jobs</h2>
        <p style="margin-bottom:20px;">Here are all the jobs you've applied for.</p>

        <?php if ($result->num_rows > 0) { ?>
            
            <?php while ($row = $result->fetch_assoc()) { ?>
                <div class="card">
                    <h3><?= htmlspecialchars($row['job_title']) ?></h3>
                    <p><?= htmlspecialchars($row['location']) ?></p>
                    <small>Applied on: <?= date("d M Y", strtotime($row['applied_at'])) ?></small>
                </div>
                
            <?php } ?>

        <?php } else { ?>

            <div class="card wide">
                <p>You haven't applied to any jobs yet.</p>
            </div>

        <?php } ?>

    </main>

</div>

</body>
</html>
