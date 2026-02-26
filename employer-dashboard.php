<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$employerId = $_SESSION['user_id'];

/* Employer Info */
$userQuery = $conn->prepare(
    "SELECT full_name, email, profile_pic 
     FROM users 
     WHERE id = ?"
);
$userQuery->bind_param("i", $employerId);
$userQuery->execute();
$user = $userQuery->get_result()->fetch_assoc();
$userName = $user['full_name']; // <-- ADD THIS

/* Fetch Employer Jobs */
$jobsQuery = $conn->prepare(
    "SELECT * FROM jobs WHERE employer_id = ?"
);
$jobsQuery->bind_param("i", $employerId);
$jobsQuery->execute();
$jobsResult = $jobsQuery->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employer Dashboard</title>

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

        /* SIDEBAR */
        .sidebar{
    width:250px;
    background: rgba(255, 255, 255, 0.05); /* very light glass */
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
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
            background: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }

        /* MAIN */
        .main{
            flex:1;
            padding:60px;
        }

        .welcome{
            color:white;
            margin-bottom:40px;
        }

        /* CARD GRID */
        .card-container{
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap:30px;
        }

        /* GLASS CARD */
        .card{
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-radius:20px;
            padding:30px;
            color:white;
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
            transition:0.3s ease;
            border:2px solid transparent; /* important */
        }

        /* Neon Border Hover */
        .card:hover{
            transform: translateY(-8px);
            border: 2px solid #8a2be2;
            box-shadow: 
            0 0 10px #8a2be2,
            0 0 20px #00f5ff;
        }
        .card h3{
            margin-top:0;
        }

        .card p{
            opacity:0.85;
        }

        /* BUTTON STYLE */
        .card-btn{
            display:inline-block;
            margin-top:20px;
            padding:10px 22px;
            border-radius:8px;
            background: linear-gradient(45deg, #00f5ff, #8a2be2);
            color:white;
            font-weight:600;
            text-decoration:none;
            transition:0.3s;
        }

        .card-btn:hover{
            box-shadow: 0 0 15px #00f5ff,
                        0 0 25px #8a2be2;
            transform: scale(1.07);
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
        <h1 class="welcome">Welcome, <?php echo $userName; ?> 👋</h1>

        <div class="card-container">

            <div class="card">
                <h3>Post a New Job</h3>
                <p>Create job listings and find the right candidates easily.</p>
                <a href="post-job.php" class="card-btn">Post Job</a>
            </div>

            <div class="card">
                <h3>Manage Jobs</h3>
                <p>Edit, update, or delete your existing job posts.</p>
                <a href="manage-jobs.php" class="card-btn">Manage Jobs</a>
            </div>

            <div class="card">
                <h3>View Applications</h3>
                <p>Check and review candidates who applied.</p>
                <a href="view-applicants.php" class="card-btn">View Applications</a>
            </div>

        </div>

    </div>

</div>

</body>
</html>