<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
    header("Location: index.php");
    exit();
}

$employerId = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: manage-jobs.php");
    exit();
}

$jobId = intval($_GET['id']);

/* Fetch job */
$stmt = $conn->prepare("SELECT * FROM jobs WHERE id=? AND employer_id=?");
$stmt->bind_param("ii", $jobId, $employerId);
$stmt->execute();
$job = $stmt->get_result()->fetch_assoc();

if (!$job) {
    echo "Job not found!";
    exit();
}

/* Update Job */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['job_title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $salary = $_POST['salary'];
    $type = $_POST['job_type'];
    $description = $_POST['description'];

    $update = $conn->prepare("
        UPDATE jobs 
        SET job_title=?, company=?, location=?, salary=?, job_type=?, description=? 
        WHERE id=? AND employer_id=?
    ");

    $update->bind_param("ssssssii", $title, $company, $location, $salary, $type, $description, $jobId, $employerId);
    $update->execute();

    header("Location: manage-jobs.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Job</title>

<style>
body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.9)),
    url('https://images.unsplash.com/photo-1519681393784-d120267933ba') no-repeat center center fixed;
    background-size: cover;
    min-height:100vh;
}

.dashboard-container{
    display:flex;
    min-height:100vh;
}

/* Sidebar */
.sidebar{
    width:250px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(18px);
    padding:30px 20px;
    color:white;
    border-right:1px solid rgba(255,255,255,0.1);
}

.sidebar h2{
    margin-bottom:40px;
    text-align:center;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    margin:15px 0;
    padding:12px;
    border-radius:10px;
    transition:0.3s;
}

.sidebar a:hover{
    background: rgba(255,255,255,0.12);
}

/* Main */
.main{
    flex:1;
    padding:60px;
    color:white;
}

h1{
    margin-bottom:30px;
}

/* Glass Form Card */
.form-card{
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(18px);
    border-radius:20px;
    padding:40px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    max-width:700px;
}

/* Inputs */
input, textarea, select{
    width:100%;
    padding:12px;
    margin-bottom:20px;
    border-radius:8px;
    border:none;
    outline:none;
}

textarea{
    resize:none;
    height:120px;
}

/* Button */
button{
    padding:10px 20px;
    border:none;
    border-radius:8px;
    background: linear-gradient(45deg, #00f5ff, #8a2be2);
    color:white;
    font-weight:bold;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    transform: scale(1.05);
    box-shadow:0 0 10px white;
}
</style>
</head>

<body>

<div class="dashboard-container">

    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Employer Panel</h2>
        <a href="employer-dashboard.php">🏠 Dashboard</a>
        <a href="post-job.php">➕ Post Job</a>
        <a href="manage-jobs.php">📋 Manage Jobs</a>
        <a href="view-applicants.php">👥 View Applicants</a>
        <a href="employer-profile.php">👤 My Profile</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <!-- Main -->
    <div class="main">

        <h1>Edit Job</h1>

        <div class="form-card">

            <form method="POST">

                <label>Job Title</label>
                <input type="text" name="job_title"
                       value="<?= htmlspecialchars($job['job_title']); ?>" required>

                <label>Company</label>
                <input type="text" name="company"
                       value="<?= htmlspecialchars($job['company']); ?>" required>

                <label>Location</label>
                <input type="text" name="location"
                       value="<?= htmlspecialchars($job['location']); ?>" required>

                <label>Salary</label>
                <input type="text" name="salary"
                       value="<?= htmlspecialchars($job['salary']); ?>" required>

                <label>Job Type</label>
                <input type="text" name="job_type"
                       value="<?= htmlspecialchars($job['job_type']); ?>" required>

                <label>Description</label>
                <textarea name="description" required>
<?= htmlspecialchars($job['description']); ?>
                </textarea>

                <button type="submit">Update Job</button>

            </form>

        </div>

    </div>

</div>

</body>
</html>