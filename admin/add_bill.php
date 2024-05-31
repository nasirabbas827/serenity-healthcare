<?php
include('config.php');
session_start();

// Check if user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all patients
$patients_sql = "SELECT id, username FROM patients";
$patients_result = mysqli_query($conn, $patients_sql);

// Handle form submission to add a new bill
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $amount = $_POST['amount'];
    $bill_date = $_POST['bill_date'];
    $bill_type = $_POST['bill_type'];
    $description = $_POST['description'];

    $insert_sql = "INSERT INTO Bills (patient_id, amount, bill_date, bill_type, description) VALUES (?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $insert_sql)) {
        mysqli_stmt_bind_param($stmt, "idsss", $patient_id, $amount, $bill_date, $bill_type, $description);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: view_bills.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bill</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Add Bill</h2>
    <form action="add_bill.php" method="post">
        <div class="form-group">
            <label for="patient_id">Select Patient</label>
            <select class="form-control" id="patient_id" name="patient_id" required>
                <option value="">Select Patient</option>
                <?php while ($row = mysqli_fetch_assoc($patients_result)): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div id="patientDetails" class="card mt-4" style="display: none;">
            <div class="card-body">
                <h5 class="card-title">Patient Details</h5>
                <div id="appointmentDetails"></div>
                <div id="testDetails"></div>
            </div>
        </div>
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="form-group">
            <label for="bill_date">Bill Date</label>
            <input type="datetime-local" class="form-control" id="bill_date" name="bill_date" required>
        </div>
        <div class="form-group">
    <label for="bill_type">Bill Type</label>
    <select class="form-control" id="bill_type" name="bill_type" required>
        <option value="appointment">Appointment</option>
        <option value="medical">Medical</option>
        <option value="diagnostic">Diagnostic</option>
        <option value="all">All</option>
    </select>
</div>
<div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Bill</button>
        <a class="btn btn-outline-dark" href="view_bills.php">View Bills</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#patient_id').change(function() {
        var patientId = $(this).val();
        if (patientId) {
            $.ajax({
                url: 'get_patient_details.php',
                type: 'GET',
                data: { patient_id: patientId },
                success: function(response) {
                    response = JSON.parse(response);
                    var appointmentsHtml = '<h5>Appointments</h5><ul>';
                    for (var i = 0; i < response.appointments.length; i++) {
                        appointmentsHtml += '<li>Appointment ID: ' + response.appointments[i].appointment_id +
                                            ', Date: ' + response.appointments[i].appointment_date +
                                            ', Status: ' + response.appointments[i].status + '</li>';
                    }
                    appointmentsHtml += '</ul>';

                    var testsHtml = '<h5>Diagnostic Tests</h5><ul>';
                    for (var j = 0; j < response.tests.length; j++) {
                        testsHtml += '<li>Test Name: ' + response.tests[j].test_name +
                                     ', Date: ' + response.tests[j].test_date +
                                     ', Price: ' + response.tests[j].test_price + '</li>';
                    }
                    testsHtml += '</ul>';

                    $('#patientDetails').show();
                    $('#appointmentDetails').html(appointmentsHtml);
                    $('#testDetails').html(testsHtml);
                },
                error: function() {
                    $('#patientDetails').hide();
                }
            });
        } else {
            $('#patientDetails').hide();
        }
    });
});
</script>
</body>
</html>
<?php mysqli_close($conn); ?>
