<?php
include('config.php');
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_SESSION["id"];
    $complaint_text = $_POST["complaint_text"];
    $submission_date = date("Y-m-d H:i:s");
    $status = 'pending';

    // Insert complaint into database
    $sql = "INSERT INTO Complaints (patient_id, complaint_text, submission_date, status) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "issi", $patient_id, $complaint_text, $submission_date, $status);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Redirect to complaints page after submission
    header("location: view_complaints.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Complaint</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Add Complaint</h2>
    <form method="post">
        <div class="form-group">
            <label for="complaint_text">Complaint Text</label>
            <textarea class="form-control" id="complaint_text" name="complaint_text" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Complaint</button>
        <a class="btn btn-outline-dark" href="view_complaints.php">View Complaints</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
