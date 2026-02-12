<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

/* ================= ADD EDUCATION ================= */
if (isset($_POST['add_education'])) {
    $degree = $_POST['degree'];
    $institution = $_POST['institution'];
    $start_year = $_POST['start_year'];
    $end_year = $_POST['end_year'];

    $stmt = $conn->prepare("INSERT INTO education (user_id, degree, institution, start_year, end_year) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issii", $userId, $degree, $institution, $start_year, $end_year);
    $stmt->execute();
}

/* ================= ADD EXPERIENCE ================= */
if (isset($_POST['add_experience'])) {
    $job_title = $_POST['job_title'];
    $company = $_POST['company'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("INSERT INTO experience (user_id, job_title, company, start_date, end_date, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $userId, $job_title, $company, $start_date, $end_date, $description);
    $stmt->execute();
}

/* ================= ADD CERTIFICATE ================= */
if (isset($_POST['add_certificate'])) {
    $certificate_name = $_POST['certificate_name'];
    $issued_by = $_POST['issued_by'];
    $issue_date = $_POST['issue_date'];

    $stmt = $conn->prepare("INSERT INTO certificates (user_id, certificate_name, issued_by, issue_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $certificate_name, $issued_by, $issue_date);
    $stmt->execute();
}

/* ================= FETCH DATA ================= */
$education = $conn->query("SELECT * FROM education WHERE user_id = $userId ORDER BY start_year DESC");
$experience = $conn->query("SELECT * FROM experience WHERE user_id = $userId ORDER BY start_date DESC");
$certificates = $conn->query("SELECT * FROM certificates WHERE user_id = $userId ORDER BY issue_date DESC");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Profile</title>
        <style>
        body { font-family: Arial; background:#f4f6fa; margin:0; padding:20px; }
        .section { background:white; padding:20px; border-radius:10px; margin-bottom:20px; }
        button { padding:8px 15px; background:#4f46e5; color:white; border:none; border-radius:6px; cursor:pointer; }
        .card { background:#f1f3f8; padding:15px; margin:10px 0; border-radius:8px; }

/* MODAL */
.modal {
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
}
.modal-content {
    background:white;
    padding:20px;
    border-radius:10px;
    width:400px;
}
input, textarea {
    width:100%;
    padding:8px;
    margin:6px 0;
}
.close { float:right; cursor:pointer; font-weight:bold; }
</style>
</head>
<body>

<h2>👤 My Profile</h2>

<!-- EDUCATION -->
<div class="section">
    <h3>🎓 Education</h3>
    <button onclick="openModal('eduModal')">Add Education</button>

    <?php while($row = $education->fetch_assoc()) { ?>
        <div class="card">
            <strong><?= htmlspecialchars($row['degree']) ?></strong><br>
            <?= htmlspecialchars($row['institution']) ?><br>
            <?= $row['start_year'] ?> - <?= $row['end_year'] ?>
        </div>
    <?php } ?>
</div>

<!-- EXPERIENCE -->
<div class="section">
    <h3>💼 Experience</h3>
    <button onclick="openModal('expModal')">Add Experience</button>

    <?php while($row = $experience->fetch_assoc()) { ?>
        <div class="card">
            <strong><?= htmlspecialchars($row['job_title']) ?></strong><br>
            <?= htmlspecialchars($row['company']) ?><br>
            <?= $row['start_date'] ?> - <?= $row['end_date'] ?><br>
            <?= htmlspecialchars($row['description']) ?>
        </div>
    <?php } ?>
</div>

<!-- CERTIFICATES -->
<div class="section">
    <h3>🏆 Certificates</h3>
    <button onclick="openModal('certModal')">Add Certificate</button>

    <?php while($row = $certificates->fetch_assoc()) { ?>
        <div class="card">
            <strong><?= htmlspecialchars($row['certificate_name']) ?></strong><br>
            Issued by <?= htmlspecialchars($row['issued_by']) ?><br>
            <?= $row['issue_date'] ?>
        </div>
    <?php } ?>
</div>

<!-- EDUCATION MODAL -->
<div class="modal" id="eduModal">
<div class="modal-content">
<span class="close" onclick="closeModal('eduModal')">X</span>
<h3>Add Education</h3>
<form method="POST">
<input type="text" name="degree" placeholder="Degree" required>
<input type="text" name="institution" placeholder="Institution" required>
<input type="number" name="start_year" placeholder="Start Year" required>
<input type="number" name="end_year" placeholder="End Year" required>
<button name="add_education">Save</button>
</form>
</div>
</div>

<!-- EXPERIENCE MODAL -->
<div class="modal" id="expModal">
<div class="modal-content">
<span class="close" onclick="closeModal('expModal')">X</span>
<h3>Add Experience</h3>
<form method="POST">
<input type="text" name="job_title" placeholder="Job Title" required>
<input type="text" name="company" placeholder="Company" required>
<input type="date" name="start_date" required>
<input type="date" name="end_date">
<textarea name="description" placeholder="Description"></textarea>
<button name="add_experience">Save</button>
</form>
</div>
</div>

<!-- CERTIFICATE MODAL -->
<div class="modal" id="certModal">
<div class="modal-content">
<span class="close" onclick="closeModal('certModal')">X</span>
<h3>Add Certificate</h3>
<form method="POST">
<input type="text" name="certificate_name" placeholder="Certificate Name" required>
<input type="text" name="issued_by" placeholder="Issued By" required>
<input type="date" name="issue_date" required>
<button name="add_certificate">Save</button>
</form>
</div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).style.display = "flex";
}
function closeModal(id) {
    document.getElementById(id).style.display = "none";
}
</script>

</body>
</html>