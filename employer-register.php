<?php
include("db.php");

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO users (full_name, email, password, role) 
         VALUES (?, ?, ?, 'employer')"
    );

    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();

    header("Location: employer-login.php");
}
?>

<form method="POST">
    <h2>Employer Register</h2>
    <input type="text" name="name" placeholder="Company Name" required>
    <input type="email" name="email" placeholder="Company Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button name="register">Register</button>
</form>
