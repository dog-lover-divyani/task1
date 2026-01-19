<?php
$host = "localhost";
$user = "root"; // Default XAMPP user
$pass = "";     // Default XAMPP password
$dbname = "job_portal";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>