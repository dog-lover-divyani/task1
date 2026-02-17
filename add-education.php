<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userId = $_SESSION['user_id'];
    $degree = $_POST['degree'];
    $institution = $_POST['institution'];
    $start = $_POST['start_year'];
    $end = $_POST['end_year'];

    $stmt = $conn->prepare(
        "INSERT INTO education (user_id, degree, institution, start_year, end_year)
         VALUES (?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("issss", $userId, $degree, $institution, $start, $end);
    $stmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<form method="POST">
    <h2>Add Education</h2>
    <input type="text" name="degree" placeholder="Degree" required>
    <input type="text" name="institution" placeholder="Institution" required>
    <input type="text" name="start_year" placeholder="Start Year">
    <input type="text" name="end_year" placeholder="End Year">
    <button type="submit">Save</button>
</form>
