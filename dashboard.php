<?php
session_start();
include "config/db.php";

// TEMP: assume user id = 1 (for testing)
$_SESSION['user_id'] = 1;

$user_id = $_SESSION['user_id'];

$query = "SELECT full_name, email, profile_completion 
          FROM users 
          WHERE id = $user_id";

$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);
?>
