<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];
$jobId = $_GET['job_id'];

$stmt = $conn->prepare("
    INSERT IGNORE INTO saved_jobs (user_id, job_id)
    VALUES (?, ?)
");
$stmt->bind_param("ii", $userId, $jobId);
$stmt->execute();

header("Location: dashboard.php");
exit();