<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $dob = $_POST['dob'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];

    $resumePath = null;

    if (!empty($_FILES['resume']['name'])) {
        $folder = "uploads/";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);}
        $fileName = time() . "_" . basename($_FILES["resume"]["name"]);
        $resumePath = $folder . $fileName;
        move_uploaded_file($_FILES["resume"]["tmp_name"], $resumePath);
    }

    /* 🧮 PROFILE COMPLETION */
    $completion = 0;
    if ($dob) $completion += 20;
    if ($phone) $completion += 20;
    if ($location) $completion += 20;
    if ($resumePath) $completion += 40;

    /* 💾 SAVE PROFILE */
    $stmt = $conn->prepare(
        "INSERT INTO job_seeker_profiles (user_id, dob, phone, location, resume)
         VALUES (?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
         dob=VALUES(dob), phone=VALUES(phone), location=VALUES(location), resume=VALUES(resume)"
    );
    $stmt->bind_param("issss", $userId, $dob, $phone, $location, $resumePath);
    $stmt->execute();

    /* 🔁 UPDATE USERS TABLE 
    $update = $conn->prepare(
        "UPDATE users SET profile_completion=? WHERE id=?"
    );
    $update->bind_param("ii", $completion, $userId);
    $update->execute();*/

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
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
