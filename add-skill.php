<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userId = $_SESSION['user_id'];
    $skill = $_POST['skill_name'];

    $stmt = $conn->prepare(
        "INSERT INTO skills (user_id, skill_name)
         VALUES (?, ?)"
    );
    $stmt->bind_param("is", $userId, $skill);
    $stmt->execute();

    header("Location: profile.php");
    exit();
}
?>

<form method="POST">
    <h2>Add Skill</h2>
    <input type="text" name="skill_name" placeholder="Skill" required>
    <button type="submit">Save</button>
</form>
