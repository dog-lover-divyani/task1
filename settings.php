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

/* UPDATE EMAIL */
if (isset($_POST['update_email'])) {
    $newEmail = $_POST['email'];
    $update = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
    $update->bind_param("si", $newEmail, $userId);
    $update->execute();
    $success = "Email updated successfully!";
}

/* CHANGE PASSWORD */
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

/* DELETE ACCOUNT */
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

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

/* 🌌 Starry UK Background */
body{
    color:white;
    background:
        linear-gradient(rgba(15,23,42,0.85), rgba(15,23,42,0.85)),
        url('https://images.unsplash.com/photo-1470770841072-f978cf4d019e?q=80&w=2070');
    background-size:cover;
    background-position:center;
    background-attachment:fixed;
}

.app{
    display:flex;
    min-height:100vh;
}

/* SIDEBAR */
.sidebar{
    width:240px;
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(12px);
    padding:30px 20px;
}

.logo{
    margin-bottom:30px;
}

.sidebar a{
    display:block;
    padding:10px;
    margin-bottom:10px;
    color:white;
    text-decoration:none;
    border-radius:6px;
    transition:0.3s;
}

.sidebar a:hover,
.sidebar a.active{
    background:#6366f1;
}

/* MAIN */
.main{
    flex:1;
    padding:50px;
}

.main h2{
    margin-bottom:20px;
}

/* SUCCESS / ERROR */
.success{
    color:#22c55e;
    margin-bottom:15px;
}
.error{
    color:#ef4444;
    margin-bottom:15px;
}

/* CARDS */
.card{
    background:rgba(255,255,255,0.08);
    backdrop-filter:blur(12px);
    padding:25px;
    border-radius:12px;
    margin-bottom:25px;
}

.card h3{
    margin-bottom:15px;
}

/* INPUTS */
input{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:none;
    border-radius:6px;
    outline:none;
}

/* BUTTON */
button.primary{
    padding:10px 18px;
    background:#6366f1;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    transition:0.3s;
}

button.primary:hover{
    background:#4f46e5;
}

/* Danger Zone */
.danger{
    border:1px solid red;
}
.danger h3{
    color:#ef4444;
}
.danger button{
    background:#ef4444;
}
.danger button:hover{
    background:#dc2626;
}
</style>

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

    <?php if(isset($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>

    <!-- Update Email -->
    <div class="card">
      <h3>Update Email</h3>
      <form method="POST">
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
        <button class="primary" name="update_email">Update Email</button>
      </form>
    </div>

    <!-- Change Password -->
    <div class="card">
      <h3>Change Password</h3>
      <form method="POST">
        <input type="password" name="current_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <button class="primary" name="change_password">Change Password</button>
      </form>
    </div>

    <!-- Delete Account -->
    <div class="card danger">
      <h3>Danger Zone</h3>
      <form method="POST">
        <button class="primary" name="delete_account">
          Delete My Account
        </button>
      </form>
    </div>

  </main>
</div>

</body>
</html>