<?php
include 'db.php';
session_start();
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('YOUR_GOOGLE_CLIENT_ID');
$client->setClientSecret('YOUR_GOOGLE_CLIENT_SECRET');
$client->setRedirectUri('http://localhost/jobportal/google-callback.php');

if (!isset($_GET['code'])) {
    header('Location: index.php');
    exit();
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
$client->setAccessToken($token['access_token']);

$oauth = new Google_Service_Oauth2($client);
$userInfo = $oauth->userinfo->get();

$email = mysqli_real_escape_string($conn, $userInfo->email);
$name  = mysqli_real_escape_string($conn, $userInfo->name);
$googleId = $userInfo->id;

/* 🔍 Check if user exists */
$result = $conn->query("SELECT * FROM users WHERE email='$email'");

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    // 🆕 Auto Register
    $conn->query("
        INSERT INTO users (full_name, email, oauth_provider, oauth_id, role)
        VALUES ('$name', '$email', 'google', '$googleId', 'candidate')
    ");
    $user = $conn->query("SELECT * FROM users WHERE email='$email'")->fetch_assoc();
}

/* 🔐 Login */
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['full_name'];

$_SESSION['toast_msg'] = "Welcome, " . $user['full_name'];
$_SESSION['toast_type'] = "success";

header("Location: welcome.php");
exit();
