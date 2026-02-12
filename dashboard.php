<?php
session_start();
include("db.php");

/* 🔐 Protect dashboard */
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

/* 📝 HANDLE JOB APPLY (ID BASED SYSTEM) */
if (isset($_POST['apply_job'])) {

    $jobId = $_POST['job_id'];

    $check = $conn->prepare(
        "SELECT id FROM job_applications WHERE user_id = ? AND job_id = ?"
    );
    $check->bind_param("ii", $userId, $jobId);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        $applyQuery = $conn->prepare(
            "INSERT INTO job_applications (user_id, job_id)
             VALUES (?, ?)"
        );
        $applyQuery->bind_param("ii", $userId, $jobId);
        $applyQuery->execute();
    }
}

/* ✅ FETCH APPLIED JOB IDs */
$appliedJobs = [];

$appQuery = $conn->prepare(
    "SELECT job_id FROM job_applications WHERE user_id = ?"
);
$appQuery->bind_param("i", $userId);
$appQuery->execute();

$res = $appQuery->get_result();

while ($row = $res->fetch_assoc()) {
    $appliedJobs[] = $row['job_id'];
}

/* 👤 Fetch user data */
$userQuery = $conn->prepare(
    "SELECT id, full_name, email, profile_completion, profile_pic 
     FROM users 
     WHERE id = ?"
);
$userQuery->bind_param("i", $userId);
$userQuery->execute();
$user = $userQuery->get_result()->fetch_assoc();

/* 📄 Fetch resume */
$profileQuery = $conn->prepare(
    "SELECT resume 
     FROM job_seeker_profiles 
     WHERE user_id = ?"
);
$profileQuery->bind_param("i", $userId);
$profileQuery->execute();
$profile = $profileQuery->get_result()->fetch_assoc();

$resumeName = ($profile && $profile['resume'])
    ? basename($profile['resume'])
    : "No resume uploaded";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>CareerVault Dashboard</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2 class="logo">CareerVault</h2>

    <nav>
      <a href="dashboard.php" class="active">🏠 Dashboard</a>
      <a href="profile.php">👤 Profile</a>
      <a href="resume.php">📄 Resume</a>
      <a href="applied-jobs.php">💼 Applied Jobs</a>
      <a href="saved-jobs.php">❤️ Saved Jobs</a>
      <a href="settings.php">⚙ Settings</a>
    </nav>


    <div class="user-box">
      <?php if (!empty($user['profile_pic'])) { ?>
      <img src="<?php echo $user['profile_pic']; ?>" alt="Profile">
      <?php } else { ?>
    <div class="default-avatar">👤</div>
    <?php } ?>

    <p class="name"><?php echo htmlspecialchars($user['full_name']); ?></p>
    <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
    <form action="logout.php" method="POST">
      <button class="logout">Logout</button>
    </form>
    </div>
  </aside>

  <!-- MAIN -->
  <main class="main">
  
    <!-- TOP BAR -->
    <div class="topbar">
      <h3>Dashboard</h3>
      <input type="text" placeholder="Search here..." />

      <!-- 🌗 THEME TOGGLE -->
      <div class="theme-toggle" id="themeToggle">
        <div class="toggle-circle"></div>
    </div>


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
      <div class="card"><h2><?= count($appliedJobs) ?></h2><p>Applied Jobs</p></div>
      <div class="card"><h2>6</h2><p>Saved Jobs</p></div>
      <div class="card"><h2>2</h2><p>Interview Calls</p></div>
    </section>

    <!-- RESUME -->
    <section class="resume card wide">
      <div>
        <h3>My Resume</h3>
        <p><?= htmlspecialchars($resumeName); ?></p>
      </div>

      <?php if ($profile && $profile['resume']) { ?>
        <a href="<?= $profile['resume']; ?>" download>
          <button class="primary">Download Resume</button>
        </a>
      <?php } else { ?>
        <a href="job-seeker-profile.php">
          <button class="primary">Upload Resume</button>
        </a>
      <?php } ?>
    </section>

    <!-- JOBS -->
<h3 class="section-title">Recommended Jobs</h3>

<?php
$jobsQuery = $conn->query("SELECT id, job_title, company, location FROM jobs");

while ($job = $jobsQuery->fetch_assoc()) {

    $applied = in_array($job['id'], $appliedJobs);
?>

<form method="POST" class="job card wide">

    <div>
        <h4><?= htmlspecialchars($job['job_title']) ?></h4>
        <p>
            <?= htmlspecialchars($job['company']) ?>
            ·
            <?= htmlspecialchars($job['location']) ?>
        </p>
    </div>

    <input type="hidden" name="job_id" value="<?= $job['id'] ?>">

    <div style="margin-top:10px; display:flex; gap:10px; align-items:center;">

        <button 
            class="primary <?= $applied ? 'disabled' : '' ?>"
            name="apply_job"
            <?= $applied ? 'disabled' : '' ?>>
            <?= $applied ? "Applied ✓" : "Apply Now" ?>
        </button>

        <a href="save-job.php?job_id=<?= $job['id'] ?>" 
           style="color:#e11d48; text-decoration:none; font-weight:600;">
            ❤️ Save
        </a>

    </div>

</form>

<?php } ?>

  </main>
</div>

<script src="dashboard.js"></script>
</body>
</html>
