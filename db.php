<?php
$host = "sql100.infinityfree.com";
$user = "if0_41255947";
$pass = "RadhaKrishna04";
$db   = "if0_41255947_job_portal";

$conn = mysqli_connect($host, $user, $pass, $db, 3306);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>