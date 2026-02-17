<?php
session_start();
include("db.php");

if (!isset($_SESSION['employer_id'])) {
    header("Location: employer-login.php");
    exit();
}

$employerId = $_SESSION['employer_id'];

if (isset($_POST['post_job'])) {

    $title = $_POST['title'];
    $company = $_POST['company'];
    $location = $_POST['location'];

    $stmt = $conn->prepare(
        "INSERT INTO jobs (job_title, company, location, employer_id) 
         VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param("sssi", $title, $company, $location, $employerId);
    $stmt->execute();

    echo "Job Posted Successfully!";
}
?>

<form method="POST">
    <h2>Post Job</h2>
    <input type="text" name="title" placeholder="Job Title" required>
    <input type="text" name="company" placeholder="Company Name" required>
    <input type="text" name="location" placeholder="Location" required>
    <button name="post_job">Post Job</button>
</form>
