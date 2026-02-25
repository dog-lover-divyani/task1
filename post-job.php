<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
    header("Location: index.php");
    exit();
}

$employerId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

if(isset($_POST['post_job'])){
    $title = $_POST['job_title'];
    $company = $_POST['company'];
    $location = $_POST['location'];
    $description = $_POST['description'];
    $salary = $_POST['salary'];
    $jobType = $_POST['job_type'];

    $stmt = $conn->prepare("INSERT INTO jobs (job_title, company, location, description, salary, job_type, employer_id) VALUES (?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssi", $title, $company, $location, $description, $salary, $jobType, $employerId);
    $stmt->execute();

    echo "<script>alert('Job Posted Successfully!');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Post Job</title>

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

/* Glass Form */
.form-card{
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(18px);
    border-radius:20px;
    padding:40px;
    max-width:600px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    border:2px solid transparent;
    transition:0.3s;
}

.form-card:hover{
    border:2px solid #8a2be2;
    box-shadow: 0 0 15px #8a2be2;
}

/* Inputs */
input, select, textarea{
    width:100%;
    padding:12px;
    margin-bottom:20px;
    border:none;
    border-radius:8px;
    outline:none;
}

/* Button */
button{
    padding:12px 25px;
    border:none;
    border-radius:8px;
    background: linear-gradient(45deg, #00f5ff, #8a2be2);
    color:white;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    box-shadow: 0 0 15px #00f5ff,
                0 0 25px #8a2be2;
    transform: scale(1.05);
}
</style>
</head>

<body>

<div class="dashboard-container">

    <div class="sidebar">
        <h2>Employer Panel</h2>
        <a href="employer-dashboard.php">🏠 Dashboard</a>
        <a href="post-job.php">➕ Post Job</a>
        <a href="manage-jobs.php">📋 Manage Jobs</a>
        <a href="view-applicants.php">👥 View Applicants</a>
        <a href="employer-profile.php">👤 My Profile</a>
        <a href="logout.php">🚪 Logout</a>
    </div>

    <div class="main">
        <h1>Post a New Job</h1>

        <div class="form-card">
            <form method="POST">

                <input type="text" name="job_title" placeholder="Job Title" required>

                <input type="text" name="company" placeholder="Company Name" required>

                <input type="text" name="location" placeholder="Location" required>

                <input type="text" name="salary" placeholder="Salary">

                <select name="job_type" required>
                    <option value="">Select Job Type</option>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Internship">Internship</option>
                </select>

                <textarea name="description" placeholder="Job Description" rows="5" required></textarea>

                <button type="submit" name="post_job">Post Job</button>

            </form>
        </div>

    </div>

</div>

</body>
</html>