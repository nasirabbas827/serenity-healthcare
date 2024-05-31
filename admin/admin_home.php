<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch total number of patients
$sqlPatients = "SELECT COUNT(id) AS total_patients FROM Patients";
$resultPatients = mysqli_query($conn, $sqlPatients);
$rowPatients = mysqli_fetch_assoc($resultPatients);
$totalPatients = $rowPatients['total_patients'];

// Fetch total number of staff members
$sqlStaff = "SELECT COUNT(staff_id) AS total_staff FROM Staff";
$resultStaff = mysqli_query($conn, $sqlStaff);
$rowStaff = mysqli_fetch_assoc($resultStaff);
$totalStaff = $rowStaff['total_staff'];

// Fetch total number of doctors
$sqlDoctors = "SELECT COUNT(doctor_id) AS total_doctors FROM Doctors";
$resultDoctors = mysqli_query($conn, $sqlDoctors);
$rowDoctors = mysqli_fetch_assoc($resultDoctors);
$totalDoctors = $rowDoctors['total_doctors'];

// Fetch total number of complaints
$sqlComplaints = "SELECT COUNT(complaint_id) AS total_complaints FROM Complaints";
$resultComplaints = mysqli_query($conn, $sqlComplaints);
$rowComplaints = mysqli_fetch_assoc($resultComplaints);
$totalComplaints = $rowComplaints['total_complaints'];

// Fetch total number of diagnostic tests
$sqlTests = "SELECT COUNT(test_id) AS total_tests FROM DiagnosticTests";
$resultTests = mysqli_query($conn, $sqlTests);
$rowTests = mysqli_fetch_assoc($resultTests);
$totalTests = $rowTests['total_tests'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Admin Dashboard</h2>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Patients</h5>
                    <p class="card-text"><?php echo $totalPatients; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Staff</h5>
                    <p class="card-text"><?php echo $totalStaff; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Doctors</h5>
                    <p class="card-text"><?php echo $totalDoctors; ?></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Complaints</h5>
                    <p class="card-text"><?php echo $totalComplaints; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Tests</h5>
                    <p class="card-text"><?php echo $totalTests; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
