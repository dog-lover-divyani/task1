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

/* UPDATE PERSONAL INFO */
if(isset($_POST['save_personal'])){

    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $headline = $_POST['headline'] ?? '';
    $address = $_POST['address'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';

    /* PROFILE PIC UPLOAD */
    $profile_pic = $user['profile_pic'] ?? '';

    if(!empty($_FILES['profile_pic']['name'])){

        $uploadDir = "uploads/profile-pic/";
        $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $targetFile = $uploadDir . $fileName;

        if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)){
            $profile_pic = $fileName;
        }
    }

    $update = $conn->prepare("UPDATE users SET 
        full_name=?, 
        email=?, 
        phone=?, 
        dob=?, 
        headline=?, 
        address=?, 
        bio=?, 
        linkedin=?, 
        profile_pic=?
        WHERE id=?");

    $update->bind_param("sssssssssi",
        $full_name,
        $email,
        $phone,
        $dob,
        $headline,
        $address,
        $bio,
        $linkedin,
        $profile_pic,
        $userId
    );

    $update->execute();

    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>
<link rel="stylesheet" href="dashboard.css">
<style>
.form-section{
    background:white;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
}
input, textarea{
    width:100%;
    padding:8px;
    margin-bottom:10px;
}
button{
    padding:8px 14px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
}
.profile-preview{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:10px;
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
        <a href="resume.php">📄 Resume</a>
        <a href="applied-jobs.php">💼 Applied Jobs</a>
        <a href="saved-jobs.php">❤️ Saved Jobs</a>
        <a href="settings.php">⚙ Settings</a>
    </nav>
</aside>

<!-- MAIN CONTENT -->
<main class="main">

<h2>✏ Edit Profile</h2>
<p style="margin-bottom:20px;">Update your personal and professional details.</p>

<!-- PERSONAL INFO -->
<div class="form-section">
<h4>Personal Details</h4>

<form method="POST" enctype="multipart/form-data">

    <!-- Current Profile Pic -->
    <?php
        $displayPic = !empty($user['profile_pic'])
            ? "uploads/profile-pic/".$user['profile_pic']
            : "https://via.placeholder.com/120";
    ?>
    <img src="<?php echo $displayPic; ?>" class="profile-preview">

    <label>Change Profile Picture</label>
    <input type="file" name="profile_pic">

    <label>Full Name</label>
    <input type="text" name="full_name"
           value="<?php echo $user['full_name'] ?? ''; ?>" required>

    <label>Email</label>
    <input type="email" name="email"
           value="<?php echo $user['email'] ?? ''; ?>" required>

    <label>Phone</label>
    <input type="text" name="phone"
           value="<?php echo $user['phone'] ?? ''; ?>">

    <label>Date of Birth</label>
    <input type="date" name="dob"
           value="<?php echo $user['dob'] ?? ''; ?>">

    <label>Professional Headline</label>
    <input type="text" name="headline"
           value="<?php echo $user['headline'] ?? ''; ?>">

    <label>Address</label>
    <textarea name="address"><?php echo $user['address'] ?? ''; ?></textarea>

    <label>About You</label>
    <textarea name="bio"><?php echo $user['bio'] ?? ''; ?></textarea>

    <label>LinkedIn URL</label>
    <input type="text" name="linkedin"
           value="<?php echo $user['linkedin'] ?? ''; ?>">

    <button type="submit" name="save_personal">
        Save Personal Info
    </button>

</form>
</div>

<!-- EDUCATION -->
<div class="form-section">
<h4>Add Education</h4>
<form method="POST" action="add-education.php">
    <input type="text" name="college" placeholder="College Name" required>
    <input type="text" name="degree" placeholder="Degree" required>
    <input type="text" name="year" placeholder="Year" required>
    <button>Add Education</button>
</form>
</div>

<!-- EXPERIENCE -->
<div class="form-section">
<h4>Add Experience</h4>
<form method="POST" action="add-experience.php">
    <input type="text" name="company" placeholder="Company Name" required>
    <input type="text" name="role" placeholder="Role" required>
    <input type="date" name="start_date" required>
    <input type="date" name="end_date">
    <button>Add Experience</button>
</form>
</div>

<!-- SKILLS -->
<div class="form-section">
<h4>Add Skill</h4>
<form method="POST" action="add-skill.php">
    <input type="text" name="skill_name" placeholder="Skill Name" required>
    <input type="number" name="percentage" placeholder="Skill %" required>
    <button>Add Skill</button>
</form>
</div>

</main>
</div>

</body>
</html>