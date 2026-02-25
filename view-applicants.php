<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
    header("Location: index.php");
    exit();
}

$employerId = $_SESSION['user_id'];
$userName = $_SESSION['user_name'];

// UI focus – simple query (we’ll refine logic later)
$query = "SELECT a.*, j.job_title 
          FROM applications a
          JOIN jobs j ON a.job_id = j.id
          WHERE j.employer_id = $employerId";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Applicants</title>

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

/* Glass Table */
.table-card{
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(18px);
    border-radius:20px;
    padding:30px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    border:2px solid transparent;
    transition:0.3s;
}

.table-card:hover{
    border:2px solid #00f5ff;
    box-shadow: 0 0 15px #00f5ff;
}

table{
    width:100%;
    border-collapse: collapse;
    color:white;
}

th, td{
    padding:15px;
    text-align:left;
}

th{
    border-bottom:1px solid rgba(255,255,255,0.2);
}

tr:hover{
    background: rgba(255,255,255,0.08);
}

/* Status Badge */
.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.pending{
    background: linear-gradient(45deg, #f7971e, #ffd200);
    color:black;
}

.accepted{
    background: linear-gradient(45deg, #00c853, #64dd17);
}

.rejected{
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
}

/* Action Buttons */
.action-btn{
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:13px;
    margin-right:5px;
    transition:0.3s;
}

.view-btn{
    background: linear-gradient(45deg, #00f5ff, #8a2be2);
}

.approve-btn{
    background: linear-gradient(45deg, #00c853, #64dd17);
}

.reject-btn{
    background: linear-gradient(45deg, #ff416c, #ff4b2b);
}

.action-btn:hover{
    transform: scale(1.05);
    box-shadow: 0 0 10px white;
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
        <h1>Applicants</h1>

        <div class="table-card">

            <table>
                <tr>
                    <th>Applicant Name</th>
                    <th>Email</th>
                    <th>Job Applied</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

                <?php
                if($result && $result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $statusClass = strtolower($row['status']);
                        echo "<tr>
                                <td>{$row['applicant_name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['job_title']}</td>
                                <td><span class='status $statusClass'>{$row['status']}</span></td>
                                <td>
                                    <a href='#' class='action-btn view-btn'>View</a>
                                    <a href='#' class='action-btn approve-btn'>Approve</a>
                                    <a href='#' class='action-btn reject-btn'>Reject</a>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No applicants yet.</td></tr>";
                }
                ?>
            </table>

        </div>

    </div>

</div>

</body>
</html>