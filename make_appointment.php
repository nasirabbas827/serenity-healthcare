<?php
include('config.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get form data
$patient_id = $_POST["patient_id"];
$doctor_id = $_POST["doctor_id"];
$appointment_date = $_POST["appointment_date"];

// Insert appointment into the database
$sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) VALUES (?, ?, ?, 'scheduled')";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "iis", $patient_id, $doctor_id, $appointment_date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
header("location: home.php");
exit;
?>

