<?php
$conn = mysqli_connect("localhost", "root", "", "job_portal");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
