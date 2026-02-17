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

/* SAVE EVERYTHING */
if(isset($_POST['save_all'])){

    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $headline = $_POST['headline'] ?? '';
    $address = $_POST['address'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';

    /* PROFILE PIC */
    $profile_pic = $user['profile_pic'] ?? '';

    if(!empty($_FILES['profile_pic']['name'])){
        $uploadDir = "uploads/profile-pic/";
        $fileName = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $targetFile = $uploadDir . $fileName;

        if(move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFile)){
            $profile_pic = $fileName;
        }
    }

    /* UPDATE USERS TABLE */
    $update = $conn->prepare("UPDATE users SET 
        full_name=?, email=?, phone=?, dob=?, headline=?, 
        address=?, bio=?, linkedin=?, profile_pic=? 
        WHERE id=?");

    $update->bind_param("sssssssssi",
        $full_name,$email,$phone,$dob,$headline,
        $address,$bio,$linkedin,$profile_pic,$userId
    );
    $update->execute();

    /* EDUCATION */
    if(!empty($_POST['institution'])){
        foreach($_POST['institution'] as $key => $institution){

            $degree = $_POST['degree'][$key] ?? '';
            $start_year = $_POST['start_year'][$key] ?? '';
            $end_year = $_POST['end_year'][$key] ?? '';

            $stmt = $conn->prepare("INSERT INTO education (user_id, institution, degree, start_year, end_year) VALUES (?,?,?,?,?)");
            $stmt->bind_param("issss", $userId, $institution, $degree, $start_year, $end_year);
            $stmt->execute();
        }
    }

    /* EXPERIENCE */
    if(!empty($_POST['company'])){
        foreach($_POST['company'] as $key => $company){

            $role = $_POST['role'][$key] ?? '';
            $start = $_POST['start_date'][$key] ?? '';
            $end = $_POST['end_date'][$key] ?? '';

            $stmt = $conn->prepare("INSERT INTO experience (user_id, company, job_title, start_date, end_date) VALUES (?,?,?,?,?)");
            $stmt->bind_param("issss", $userId, $company, $role, $start, $end);
            $stmt->execute();
        }
    }

    /* SKILLS */
    if(!empty($_POST['skill_name'])){
        foreach($_POST['skill_name'] as $key => $skill){

            $percent = $_POST['percentage'][$key] ?? 0;

            $stmt = $conn->prepare("INSERT INTO skills (user_id, skill_name, skill_level) VALUES (?,?,?)");
            $stmt->bind_param("isi", $userId, $skill, $percent);
            $stmt->execute();
        }
    }

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
    border-radius:12px;
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
.add-btn{
    margin-bottom:15px;
}
.profile-preview{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:10px;
}
.block{
    background:#f4f4f4;
    padding:15px;
    border-radius:10px;
    margin-bottom:10px;
}
</style>
</head>
<body>

<div class="app">

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

<main class="main">

<h2>✏ Edit Profile</h2>
<p style="margin-bottom:20px;">Update everything here.</p>

<form method="POST" enctype="multipart/form-data">

<div class="form-section">
<h4>Personal Details</h4>

<?php
$displayPic = !empty($user['profile_pic'])
? "uploads/profile-pic/".$user['profile_pic']
: "https://via.placeholder.com/120";
?>
<img src="<?php echo $displayPic; ?>" class="profile-preview">

<input type="file" name="profile_pic">
<input type="text" name="full_name" value="<?php echo $user['full_name'] ?? ''; ?>" placeholder="Full Name">
<input type="email" name="email" value="<?php echo $user['email'] ?? ''; ?>" placeholder="Email">
<input type="text" name="phone" value="<?php echo $user['phone'] ?? ''; ?>" placeholder="Phone">
<input type="date" name="dob" value="<?php echo $user['dob'] ?? ''; ?>">
<input type="text" name="headline" value="<?php echo $user['headline'] ?? ''; ?>" placeholder="Headline">
<textarea name="address" placeholder="Address"><?php echo $user['address'] ?? ''; ?></textarea>
<textarea name="bio" placeholder="About You"><?php echo $user['bio'] ?? ''; ?></textarea>
<input type="text" name="linkedin" value="<?php echo $user['linkedin'] ?? ''; ?>" placeholder="LinkedIn URL">
</div>

<div class="form-section">
<h4>Education</h4>
<div id="educationContainer"></div>
<button type="button" class="add-btn" onclick="addEducation()">+ Add Education</button>
</div>

<div class="form-section">
<h4>Experience</h4>
<div id="experienceContainer"></div>
<button type="button" class="add-btn" onclick="addExperience()">+ Add Experience</button>
</div>

<div class="form-section">
<h4>Skills</h4>
<div id="skillsContainer"></div>
<button type="button" class="add-btn" onclick="addSkill()">+ Add Skill</button>
</div>

<button type="submit" name="save_all">Save All Changes</button>

</form>

</main>
</div>

<script>
function addEducation(){
    let container = document.getElementById("educationContainer");
    let block = document.createElement("div");
    block.classList.add("block");
    block.innerHTML = `
        <input type="text" name="institution[]" placeholder="Institution" required>
        <input type="text" name="degree[]" placeholder="Degree" required>
        <input type="text" name="start_year[]" placeholder="Start Year" required>
        <input type="text" name="end_year[]" placeholder="End Year" required>
    `;
    container.appendChild(block);
}

function addExperience(){
    let container = document.getElementById("experienceContainer");
    let block = document.createElement("div");
    block.classList.add("block");
    block.innerHTML = `
        <input type="text" name="company[]" placeholder="Company Name" required>
        <input type="text" name="role[]" placeholder="Role" required>
        <input type="date" name="start_date[]" required>
        <input type="date" name="end_date[]">
    `;
    container.appendChild(block);
}

function addSkill(){
    let container = document.getElementById("skillsContainer");
    let block = document.createElement("div");
    block.classList.add("block");
    block.innerHTML = `
        <input type="text" name="skill_name[]" placeholder="Skill Name" required>
        <input type="number" name="percentage[]" placeholder="Skill %" required>
    `;
    container.appendChild(block);
}
</script>

</body>
</html>