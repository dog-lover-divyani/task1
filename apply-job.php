<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'candidate') {
    header("Location: index.php");
    exit();
}

$jobId = $_GET['job_id'];
$candidateId = $_SESSION['user_id'];
$name = $_SESSION['user_name'];
$email = $_SESSION['user_email']; // make sure this exists in session

if(isset($_POST['apply'])){

    $resumeName = $_FILES['resume']['name'];
    $tempName = $_FILES['resume']['tmp_name'];

    // Make resume unique
    $resumeName = time() . "_" . $resumeName;

    move_uploaded_file($tempName, "uploads/" . $resumeName);

    $stmt = $conn->prepare("INSERT INTO applications 
        (job_id, candidate_id, applicant_name, email, resume) 
        VALUES (?, ?, ?, ?, ?)");

    $stmt->bind_param("iisss", $jobId, $candidateId, $name, $email, $resumeName);
    $stmt->execute();

    echo "<script>alert('Application Submitted Successfully!'); 
          window.location='candidate-dashboard.php';</script>";
}
?>

<form method="POST" enctype="multipart/form-data">
    <h2>Upload Resume</h2>
    <input type="file" name="resume" required>
    <button type="submit" name="apply">Apply Now</button>
</form>