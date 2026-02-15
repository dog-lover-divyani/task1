<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

/* Fetch user */
$stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user){
    $user = ['email' => ''];
}

/* ===========================
   UPDATE EMAIL
=========================== */
if (isset($_POST['update_email'])) {
    $newEmail = $_POST['email'];

    $update = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $update->bind_param("si", $newEmail, $userId);
    $update->execute();

    $success = "Email updated successfully!";
}

/* ===========================
   CHANGE PASSWORD
=========================== */
if (isset($_POST['change_password'])) {
    $current = $_POST['current_password'];
    $new = $_POST['new_password'];

    $check = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $check->bind_param("i", $userId);
    $check->execute();
    $data = $check->get_result()->fetch_assoc();

    if (password_verify($current, $data['password'])) {
        $hashed = password_hash($new, PASSWORD_DEFAULT);

        $updatePass = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $updatePass->bind_param("si", $hashed, $userId);
        $updatePass->execute();

        $success = "Password changed successfully!";
    } else {
        $error = "Current password is incorrect!";
    }
}

/* ===========================
   DELETE ACCOUNT
=========================== */
if (isset($_POST['delete_account'])) {

    $delete = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete->bind_param("i", $userId);
    $delete->execute();

    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Settings - CareerVault</title>
  <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2 class="logo">CareerVault</h2>
    <nav>
      <a href="dashboard.php">🏠 Dashboard</a>
      <a href="profile.php">👤 Profile</a>
      <a href="resume.php">📄 Resume</a>
      <a href="applied-jobs.php">💼 Applied Jobs</a>
      <a href="saved-jobs.php">❤️ Saved Jobs</a>
      <a href="settings.php" class="active">⚙ Settings</a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <h2>Account Settings</h2>

    <?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <!-- Update Email -->
    <div class="card wide">
      <h3>Update Email</h3>
      <form method="POST">
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        <button class="primary" name="update_email">Update Email</button>
      </form>
    </div>

    <!-- Change Password -->
    <div class="card wide">
      <h3>Change Password</h3>
      <form method="POST">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <button class="primary" name="change_password">Change Password</button>
      </form>
    </div>

    <!-- Delete Account -->
    <div class="card wide" style="border:1px solid red;">
      <h3 style="color:red;">Danger Zone</h3>
      <form method="POST">
        <button class="primary" style="background:red;" name="delete_account">
          Delete My Account
        </button>
      </form>
    </div>

  </main>
</div>

</body>
</html>
