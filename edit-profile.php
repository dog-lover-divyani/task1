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

if(isset($_POST['save_all'])){

    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $headline = $_POST['headline'] ?? '';
    $address = $_POST['address'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $linkedin = $_POST['linkedin'] ?? '';

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

<style>
body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    background: url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
    background-size: cover;
    color:white;
}
body::before{
    content:"";
    position:fixed;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.65);
    z-index:-1;
}
.app{ display:flex; }

.sidebar{
    width:250px;
    min-height:100vh;
    padding:25px;
    background:rgba(0,0,0,0.6);
    backdrop-filter:blur(10px);
}
.sidebar a{
    display:block;
    padding:12px;
    margin-bottom:10px;
    text-decoration:none;
    color:#ddd;
    border-radius:8px;
    transition:0.3s;
}
.sidebar a:hover{
    background:rgba(255,255,255,0.15);
    transform:translateX(6px);
}

.main{ flex:1; padding:40px; }

.form-section{
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(20px);
    padding:25px;
    border-radius:15px;
    margin-bottom:25px;
}

input, textarea{
    width:100%;
    padding:10px;
    margin-bottom:12px;
    border-radius:8px;
    border:none;
    background:rgba(255,255,255,0.15);
    color:white;
}

button{
    padding:10px 18px;
    background:#4f46e5;
    color:white;
    border:none;
    border-radius:8px;
    cursor:pointer;
}

.profile-preview{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:15px;
}

.block{
    background:rgba(255,255,255,0.05);
    padding:15px;
    border-radius:10px;
    margin-bottom:15px;
}
</style>
</head>

<body>

<div class="app">
<aside class="sidebar">
    <h2>CareerVault</h2>
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="profile.php">👤 Profile</a>
    <a href="resume.php">📄 Resume</a>
    <a href="applied-jobs.php">💼 Applied Jobs</a>
    <a href="saved-jobs.php">❤️ Saved Jobs</a>
    <a href="settings.php">⚙ Settings</a>
</aside>

<main class="main">

<h2>✏ Edit Profile</h2>

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
<button type="button" onclick="addEducation()">+ Add Education</button>
</div>

<div class="form-section">
<h4>Experience</h4>
<div id="experienceContainer"></div>
<button type="button" onclick="addExperience()">+ Add Experience</button>
</div>

<div class="form-section">
<h4>Skills</h4>
<div id="skillsContainer"></div>
<button type="button" onclick="addSkill()">+ Add Skill</button>
</div>

<button type="submit" name="save_all">Save All Changes</button>

</form>
</main>
</div>

<script>
function addEducation(){
    let container = document.getElementById("educationContainer");
    container.innerHTML += `
        <div class="block">
        <input type="text" name="institution[]" placeholder="Institution" required>
        <input type="text" name="degree[]" placeholder="Degree" required>
        <input type="text" name="start_year[]" placeholder="Start Year" required>
        <input type="text" name="end_year[]" placeholder="End Year" required>
        </div>`;
}

function addExperience(){
    let container = document.getElementById("experienceContainer");
    container.innerHTML += `
        <div class="block">
        <input type="text" name="company[]" placeholder="Company Name" required>
        <input type="text" name="role[]" placeholder="Role" required>
        <input type="date" name="start_date[]" required>
        <input type="date" name="end_date[]">
        </div>`;
}

function addSkill(){
    let container = document.getElementById("skillsContainer");
    container.innerHTML += `
        <div class="block">
        <input type="text" name="skill_name[]" placeholder="Skill Name" required>
        <input type="number" name="percentage[]" placeholder="Skill %" required>
        </div>`;
}
</script>

</body>
</html>