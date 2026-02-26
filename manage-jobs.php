<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
    header("Location: index.php");
    exit();
}

$employerId = $_SESSION['user_id'];

/* Fetch jobs */
$stmt = $conn->prepare("SELECT * FROM jobs WHERE employer_id = ?");
$stmt->bind_param("i", $employerId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Jobs</title>

<style>
/* (YOUR EXISTING CSS — unchanged for cleanliness) */

body{
    margin:0;
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.9)),
    url('https://images.unsplash.com/photo-1519681393784-d120267933ba') no-repeat center center fixed;
    background-size: cover;
    min-height:100vh;
}

.dashboard-container{ display:flex; min-height:100vh; }

.sidebar{
    width:250px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(18px);
    padding:30px 20px;
    color:white;
    border-right:1px solid rgba(255,255,255,0.1);
}

.sidebar h2{ margin-bottom:40px; text-align:center; }

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    margin:15px 0;
    padding:12px;
    border-radius:10px;
    transition:0.3s;
}

.sidebar a:hover{ background: rgba(255,255,255,0.12); }

.main{ flex:1; padding:60px; color:white; }

h1{ margin-bottom:30px; }

.table-card{
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(18px);
    border-radius:20px;
    padding:30px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    overflow-x:auto;
}

table{
    width:100%;
    border-collapse: collapse;
    color:white;
    table-layout: fixed;
}

th, td{
    padding:15px;
    text-align:left;
    word-wrap: break-word;
}

th{
    border-bottom:1px solid rgba(255,255,255,0.2);
}

tr:hover{
    background: rgba(255,255,255,0.08);
}

td:nth-child(6){
    max-width:250px;
}

.action-btn{
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
    color:white;
    font-size:14px;
    transition:0.3s;
    display:inline-block;
    margin-right:5px;
}

.edit-btn{
    background: linear-gradient(45deg, #00f5ff, #8a2be2);
}

.delete-btn{
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
<h1>Manage Your Jobs</h1>

<div class="table-card">

<table>
<tr>
    <th>Job Title</th>
    <th>Company</th>
    <th>Location</th>
    <th>Salary</th>
    <th>Job Type</th>
    <th>Description</th>
    <th>Actions</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
    <td><?= htmlspecialchars($row['job_title']); ?></td>
    <td><?= htmlspecialchars($row['company']); ?></td>
    <td><?= htmlspecialchars($row['location']); ?></td>
    <td><?= htmlspecialchars($row['salary']); ?></td>
    <td><?= htmlspecialchars($row['job_type']); ?></td>
    <td><?= htmlspecialchars($row['description']); ?></td>

    <td>
        <a href="edit-job.php?id=<?= $row['id']; ?>" 
           class="action-btn edit-btn">
            Edit
        </a>

        <a href="delete-job.php?id=<?= $row['id']; ?>" 
           class="action-btn delete-btn"
           onclick="return confirm('Are you sure you want to delete this job?');">
            Delete
        </a>
    </td>
</tr>

<?php } ?>

<?php if($result->num_rows == 0){ ?>
<tr>
    <td colspan="7">No jobs posted yet.</td>
</tr>
<?php } ?>

</table>

</div>

</div>

</div>

</body>
</html>