<?php
session_start();
include("db.php");

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT id, password FROM users 
         WHERE email = ? AND role = 'employer'"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['employer_id'] = $user['id'];
        header("Location: employer-dashboard.php");
        exit();
    } else {
        echo "Invalid credentials";
    }
}
?>

<form method="POST">
    <h2>Employer Login</h2>
    <input type="email" name="email" required>
    <input type="password" name="password" required>
    <button name="login">Login</button>
</form>
