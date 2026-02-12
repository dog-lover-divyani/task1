<?php
session_start();
include("db.php");
require('fpdf/fpdf.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont("Arial","B",16);

// Name
$pdf->Cell(0,10,$user['full_name'],0,1,'C');

$pdf->SetFont("Arial","",12);
$pdf->Cell(0,8,$user['email'],0,1,'C');

$pdf->Ln(10);

// Objective
$pdf->SetFont("Arial","B",14);
$pdf->Cell(0,8,"Objective",0,1);
$pdf->SetFont("Arial","",12);
$pdf->MultiCell(0,8,$user['objective']);
$pdf->Ln(5);

// Skills
$pdf->SetFont("Arial","B",14);
$pdf->Cell(0,8,"Skills",0,1);
$pdf->SetFont("Arial","",12);
$pdf->MultiCell(0,8,$user['skills_text']);
$pdf->Ln(5);

// Projects
$pdf->SetFont("Arial","B",14);
$pdf->Cell(0,8,"Projects",0,1);
$pdf->SetFont("Arial","",12);
$pdf->MultiCell(0,8,$user['projects']);

$pdf->Output("D","Resume.pdf");
exit();
?>