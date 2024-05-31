<?php
include('config.php');
session_start();

// Check if user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if patient ID is provided
if (!isset($_GET["id"]) || empty(trim($_GET["id"]))) {
    header("Location: view_patients.php");
    exit;
}

// Get patient ID from the URL parameter
$patient_id = trim($_GET["id"]);

// Fetch patient's information from the database
$sql_patient = "SELECT * FROM Patients WHERE id = ?";
if ($stmt_patient = mysqli_prepare($conn, $sql_patient)) {
    mysqli_stmt_bind_param($stmt_patient, "i", $patient_id);
    if (mysqli_stmt_execute($stmt_patient)) {
        $result_patient = mysqli_stmt_get_result($stmt_patient);
        $row_patient = mysqli_fetch_assoc($result_patient);
        mysqli_stmt_close($stmt_patient);
    } else {
        echo "Error fetching patient information.";
        exit;
    }
} else {
    echo "Error preparing statement.";
    exit;
}

// Fetch patient's appointments from the database
$sql_appointments = "SELECT * FROM Appointments WHERE patient_id = ?";
if ($stmt_appointments = mysqli_prepare($conn, $sql_appointments)) {
    mysqli_stmt_bind_param($stmt_appointments, "i", $patient_id);
    if (mysqli_stmt_execute($stmt_appointments)) {
        $result_appointments = mysqli_stmt_get_result($stmt_appointments);
        mysqli_stmt_close($stmt_appointments);
    } else {
        echo "Error fetching appointments.";
        exit;
    }
} else {
    echo "Error preparing statement.";
    exit;
}

// Fetch patient's tests from the database
$sql_tests = "SELECT * FROM DiagnosticTests WHERE patient_id = ?";
if ($stmt_tests = mysqli_prepare($conn, $sql_tests)) {
    mysqli_stmt_bind_param($stmt_tests, "i", $patient_id);
    if (mysqli_stmt_execute($stmt_tests)) {
        $result_tests = mysqli_stmt_get_result($stmt_tests);
        mysqli_stmt_close($stmt_tests);
    } else {
        echo "Error fetching tests.";
        exit;
    }
} else {
    echo "Error preparing statement.";
    exit;
}

// Fetch patient's bills from the database
$sql_bills = "SELECT * FROM Bills WHERE patient_id = ?";
if ($stmt_bills = mysqli_prepare($conn, $sql_bills)) {
    mysqli_stmt_bind_param($stmt_bills, "i", $patient_id);
    if (mysqli_stmt_execute($stmt_bills)) {
        $result_bills = mysqli_stmt_get_result($stmt_bills);
        mysqli_stmt_close($stmt_bills);
    } else {
        echo "Error fetching bills.";
        exit;
    }
} else {
    echo "Error preparing statement.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Medical History</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Medical History - <?php echo $row_patient['username']; ?></h2>
    <h3>Appointments</h3>
    <div class="table-responsive">
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
                <?php while ($row_appointment = mysqli_fetch_assoc($result_appointments)): ?>
                <tr>
                    <td><?php echo $row_appointment['appointment_id']; ?></td>
                    <td><?php echo $row_appointment['doctor_id']; ?></td>
                    <td><?php echo $row_appointment['appointment_date']; ?></td>
                    <td><?php echo $row_appointment['status']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <h3>Tests</h3>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Test ID</th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Price</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_test = mysqli_fetch_assoc($result_tests)): ?>
                <tr>
                    <td><?php echo $row_test['test_id']; ?></td>
                    <td><?php echo $row_test['test_name']; ?></td>
                    <td><?php echo $row_test['test_date']; ?></td>
                    <td><?php echo $row_test['test_price']; ?></td>
                    <td><?php echo $row_test['result']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    
    <h3>Bills</h3>
    <div class="table-responsive">
        <table class="table table-bordered">
        <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row_bill = mysqli_fetch_assoc($result_bills)): ?>
                <tr>
                    <td><?php echo $row_bill['bill_id']; ?></td>
                    <td><?php echo $row_bill['description']; ?></td>
                    <td><?php echo $row_bill['date']; ?></td>
                    <td><?php echo $row_bill['amount']; ?></td>
                    <td><?php echo $row_bill['status']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
