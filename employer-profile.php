<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
    header("Location: index.php");
    exit();
}

$userName = $_SESSION['user_name'];
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>

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

/* Profile Card */
.profile-card{
    background: rgba(255, 255, 255, 0.12);
    backdrop-filter: blur(18px);
    border-radius:20px;
    padding:40px;
    max-width:600px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    border:2px solid transparent;
    transition:0.3s;
}

.profile-card:hover{
    border:2px solid #00f5ff;
    box-shadow: 0 0 15px #00f5ff;
}

/* Inputs */
input{
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
        <h1>My Profile</h1>

        <div class="profile-card">
            <form>

                <label>Full Name</label>
                <input type="text" value="<?php echo $userName; ?>">

                <label>Email</label>
                <input type="email" placeholder="Enter your email">

                <label>Company Name</label>
                <input type="text" placeholder="Enter company name">

                <label>Phone Number</label>
                <input type="text" placeholder="Enter phone number">

                <button type="submit">Save Changes</button>

            </form>
        </div>

    </div>

</div>

</body>
</html>