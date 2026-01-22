<?php
session_start();

// Security Check: Redirect back to login if not authenticated
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit();
}

header("Location: job-seeker-profile.php");
exit();
?>
// Retrieve and clear flash messages
$message = isset($_SESSION['toast_msg']) ? $_SESSION['toast_msg'] : "";
$msgClass = isset($_SESSION['toast_type']) ? $_SESSION['toast_type'] : "";
unset($_SESSION['toast_msg']);
unset($_SESSION['toast_type']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Job Portal</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background: #f8fafc;">

    <?php if($message != ""): ?>
        <div id="toast" class="toast-notification <?php echo $msgClass; ?>">
            <div class="toast-content">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo $message; ?></span>
            </div>
            <div class="progress-bar"></div>
        </div>
    <?php endif; ?>

    <div class="auth-container">
        <div class="form-box" style="text-align: center;">
            <i class="fa-solid fa-circle-user" style="font-size: 60px; color: #6366f1; margin-bottom: 20px;"></i>
            <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
            <p style="color: #6b7280; margin: 15px 0;">You have successfully logged into the Job Portal.</p>
            
            <a href="logout.php" class="submit-btn" style="text-decoration: none; display: block; margin-top: 20px;">
                Log Out
            </a>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>