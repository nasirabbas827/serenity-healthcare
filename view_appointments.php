<?php
include('config.php');
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the patient ID from the session
$patient_id = $_SESSION["id"];

// Fetch appointments for the logged-in patient
$sql = "SELECT a.appointment_id, a.appointment_date, a.status, d.name AS doctor_name
        FROM appointments a
        JOIN doctors d ON a.doctor_id = d.doctor_id
        WHERE a.patient_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $patient_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>
    <div class="container mt-5">
        <h2>Your Appointments</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['appointment_id']; ?></td>
                        <td><?php echo $row['doctor_name']; ?></td>
                        <td><?php echo $row['appointment_date']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
