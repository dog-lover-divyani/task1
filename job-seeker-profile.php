<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

$success = "";
$error = "";

if (isset($_POST['save_profile'])) {

    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dob = $_POST['dob'];
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);

    // ==== RESUME UPLOAD ====
    $resumeName = "";
    if (!empty($_FILES['resume']['name'])) {

        $uploadDir = "uploads/resumes/";
        $fileName = time() . "_" . basename($_FILES["resume"]["name"]);
        $targetPath = $uploadDir . $fileName;

        $fileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        $allowed = ['pdf', 'doc', 'docx'];

        if (!in_array($fileType, $allowed)) {
            $error = "Only PDF, DOC, DOCX files allowed.";
        } else {
            if (move_uploaded_file($_FILES["resume"]["tmp_name"], $targetPath)) {
                $resumeName = $fileName;
            } else {
                $error = "Resume upload failed.";
            }
        }
    }

    if ($error == "") {
        $sql = "INSERT INTO job_seeker_profiles 
                (user_name, email, dob, phone, location, resume) 
                VALUES 
                ('$name', '$email', '$dob', '$phone', '$location', '$resumeName')";

        if ($conn->query($sql)) {
            $success = "Profile saved successfully!";
        } else {
            $error = "Database error.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Seeker Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>

<div class="profile-container">
    <h2>CareerVault Profile</h2>

    <form method="POST" action="" enctype="multipart/form-data">

    <!-- Personal Information -->
    <div class="section">
        <h3>Personal Information</h3>

        <label>Full Name</label>
        <input type="text" name="full_name" placeholder="Enter your name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="Enter your email" required>

        <label>Date of Birth</label>
        <input type="date" name="dob">
    </div>

    <!-- Contact Details -->
    <div class="section">
        <h3>Contact Details</h3>

        <label>Phone Number</label>
        <input type="tel" name="phone" placeholder="Enter phone number">

        <label>Location</label>
        <input type="text" name="location" placeholder="City, Country">
    </div>

    <!-- Resume Upload -->
    <div class="section">
        <h3>Resume Upload</h3>

        <input type="file" name="resume" accept=".pdf,.doc,.docx">
    </div>

    <button type="submit" name="save_profile">Save Profile</button>
</form>

</div>

</body>
</html>
