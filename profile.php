<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

/* FETCH USER */
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

/* FETCH EDUCATION */
$eduStmt = $conn->prepare("SELECT * FROM education WHERE user_id=? ORDER BY created_at DESC");
$eduStmt->bind_param("i", $userId);
$eduStmt->execute();
$education = $eduStmt->get_result();

/* FETCH EXPERIENCE */
$expStmt = $conn->prepare("SELECT * FROM experience WHERE user_id=? ORDER BY created_at DESC");
$expStmt->bind_param("i", $userId);
$expStmt->execute();
$experience = $expStmt->get_result();

/* FETCH SKILLS */
$skillStmt = $conn->prepare("SELECT * FROM skills WHERE user_id=? ORDER BY created_at DESC");
$skillStmt->bind_param("i", $userId);
$skillStmt->execute();
$skills = $skillStmt->get_result();

/* Profile Image */
$profileImage = !empty($user['profile_pic']) 
    ? "uploads/profile-pic/" . $user['profile_pic']
    : "https://via.placeholder.com/120";
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<link rel="stylesheet" href="dashboard.css">
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

<!-- PROFILE HEADER -->
<div class="profile-card">

    <div class="profile-top">
        <img src="<?php echo $profileImage; ?>" class="profile-avatar">

        <div class="profile-details">
            <h2><?php echo htmlspecialchars($user['full_name'] ?? 'No Name'); ?></h2>
            <p class="muted"><?php echo htmlspecialchars($user['email']); ?></p>

            <?php if(!empty($user['headline'])): ?>
                <p class="headline"><?php echo htmlspecialchars($user['headline']); ?></p>
            <?php endif; ?>

            <?php if(!empty($user['phone'])): ?>
                <p class="muted">📞 <?php echo htmlspecialchars($user['phone']); ?></p>
            <?php endif; ?>

            <?php if(!empty($user['dob'])): ?>
                <p class="muted">🎂 <?php echo htmlspecialchars($user['dob']); ?></p>
            <?php endif; ?>

            <?php if(!empty($user['address'])): ?>
                <p class="muted">📍 <?php echo htmlspecialchars($user['address']); ?></p>
            <?php endif; ?>

            <?php if(!empty($user['linkedin'])): ?>
                <p>
                    <a href="<?php echo $user['linkedin']; ?>" target="_blank" class="link">
                        🔗 LinkedIn Profile
                    </a>
                </p>
            <?php endif; ?>
        </div>

        <a href="edit-profile.php" class="edit-btn">Edit Profile</a>
    </div>

    <?php if(!empty($user['bio'])): ?>
        <div class="profile-section">
            <h3>About</h3>
            <p><?php echo htmlspecialchars($user['bio']); ?></p>
        </div>
    <?php endif; ?>

    <!-- EDUCATION -->
    <div class="profile-section">
        <h3>Education</h3>

        <?php if ($education->num_rows > 0): ?>
            <?php while ($row = $education->fetch_assoc()): ?>
                <div class="entry">
                    <h4><?= htmlspecialchars($row['degree'] ?? '') ?></h4>
                    <p><?= htmlspecialchars($row['institution'] ?? '') ?></p>
                    <span class="muted">
                        <?= htmlspecialchars($row['start_year'] ?? '') ?> -
                        <?= htmlspecialchars($row['end_year'] ?? '') ?>
                    </span>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="muted">No education added yet.</p>
        <?php endif; ?>
    </div>

    <!-- EXPERIENCE -->
    <div class="profile-section">
        <h3>Experience</h3>

        <?php if ($experience->num_rows > 0): ?>
            <?php while ($row = $experience->fetch_assoc()): ?>
                <div class="entry">
                    <h4><?= htmlspecialchars($row['job_title'] ?? '') ?></h4>
                    <p><?= htmlspecialchars($row['company'] ?? '') ?></p>
                    <span class="muted">
                        <?= htmlspecialchars($row['start_date'] ?? '') ?> -
                        <?= htmlspecialchars($row['end_date'] ?? '') ?>
                    </span>
                    <p><?= htmlspecialchars($row['description'] ?? '') ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="muted">No experience added yet.</p>
        <?php endif; ?>
    </div>

    <!-- SKILLS -->
    <div class="profile-section">
        <h3>Skills</h3>

        <?php if ($skills->num_rows > 0): ?>
            <div class="skills-wrap">
                <?php while ($row = $skills->fetch_assoc()): ?>
                    <span class="skill-tag">
                        <?= htmlspecialchars($row['skill_name'] ?? '') ?>
                    </span>
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