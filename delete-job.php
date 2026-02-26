<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'employer') {
    header("Location: index.php");
    exit();
}

$employerId = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: manage-jobs.php");
    exit();
}

$jobId = intval($_GET['id']);

/* Delete job (only if belongs to employer) */
$stmt = $conn->prepare("DELETE FROM jobs WHERE id=? AND employer_id=?");
$stmt->bind_param("ii", $jobId, $employerId);
$stmt->execute();

header("Location: manage-jobs.php");
exit();
?>