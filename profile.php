<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

/* ================= FETCH USER ================= */
$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

/* Profile Image Path */
$profileImage = !empty($user['profile_pic']) 
    ? "uploads/profile-pic/" . $user['profile_pic']
    : "https://via.placeholder.com/120";
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>
<link rel="stylesheet" href="dashboard.css">

<style>
.btn-edit{
    display:inline-block;
    padding:8px 14px;
    background:#4f46e5;
    color:white;
    border-radius:6px;
    text-decoration:none;
}
.btn-edit:hover{
    background:#4338ca;
}
.profile-header{
    display:flex;
    align-items:center;
    gap:20px;
}
.profile-header img{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:4px solid #4f46e5;
}
.profile-info h3{
    margin:0;
}
.profile-info p{
    margin:3px 0;
    color:gray;
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
        <a href="profile.php" class="active">👤 Profile</a>
        <a href="resume.php">📄 Resume</a>
        <a href="applied-jobs.php">💼 Applied Jobs</a>
        <a href="saved-jobs.php">❤️ Saved Jobs</a>
        <a href="settings.php">⚙ Settings</a>
    </nav>
</aside>

<!-- MAIN CONTENT -->
<main class="main">

<h2>👤 My Profile</h2>
<p style="margin-bottom:20px;">Manage your profile details.</p>

<div class="card wide">

    <div class="profile-header">

        <!-- PROFILE IMAGE -->
        <img src="<?php echo $profileImage; ?>">

        <!-- PROFILE INFO -->
        <div class="profile-info">
            <h3><?php echo $user['full_name'] ?? 'No Name'; ?></h3>
            <p><?php echo $user['email']; ?></p>

            <?php if(!empty($user['headline'])): ?>
                <p><strong><?php echo $user['headline']; ?></strong></p>
            <?php endif; ?>

            <?php if(!empty($user['phone'])): ?>
                <p>📞 <?php echo $user['phone']; ?></p>
            <?php endif; ?>

            <?php if(!empty($user['dob'])): ?>
                <p>🎂 <?php echo $user['dob']; ?></p>
            <?php endif; ?>

            <?php if(!empty($user['address'])): ?>
                <p>📍 <?php echo $user['address']; ?></p>
            <?php endif; ?>

            <?php if(!empty($user['linkedin'])): ?>
                <p>🔗 <a href="<?php echo $user['linkedin']; ?>" target="_blank">
                    LinkedIn Profile
                </a></p>
            <?php endif; ?>
        </div>

        <!-- EDIT BUTTON -->
        <div style="margin-left:auto;">
            <a href="edit-profile.php" class="btn-edit">
                ✏ Edit Profile
            </a>
        </div>

    </div>

    <?php if(!empty($user['bio'])): ?>
        <hr style="margin:20px 0;">
        <h4>About</h4>
        <p><?php echo $user['bio']; ?></p>
    <?php endif; ?>

</div>

</main>
</div>

</body>
</html>