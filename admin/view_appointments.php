<?php
include('config.php');
session_start();

// Check if user is logged in as admin, if not, redirect to login page
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("location: admin_login.php");
    exit;
}

// Fetch all appointments from the database
$sql = "SELECT a.appointment_id, a.appointment_date, a.status AS appointment_status, p.username AS patient, p.status AS patient_status, d.name AS doctor 
        FROM appointments a 
        JOIN patients p ON a.patient_id = p.id 
        JOIN doctors d ON a.doctor_id = d.doctor_id";
$result = mysqli_query($conn, $sql);

// Handle form submission for updating appointments
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = htmlspecialchars($_POST["appointment_id"]);
    $appointment_date = htmlspecialchars($_POST["appointment_date"]);
    $status = htmlspecialchars($_POST["status"]);

    $update_sql = "UPDATE appointments SET appointment_date = ?, status = ? WHERE appointment_id = ?";
    if ($stmt = mysqli_prepare($conn, $update_sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $appointment_date, $status, $appointment_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("location: view_appointments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
</head>
<body>

<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Manage Appointments</h2>
    <table id="appointmentsTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Appointment ID</th>
                <th>Patient</th>
                <th>Patient Status</th>
                <th>Doctor</th>
                <th>Appointment Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['appointment_id']; ?></td>
                    <td><?php echo $row['patient']; ?></td>
                    <td><?php echo ucfirst($row['patient_status']); ?></td>
                    <td><?php echo $row['doctor']; ?></td>
                    <td><?php echo $row['appointment_date']; ?></td>
                    <td><?php echo ucfirst($row['appointment_status']); ?></td>
                    <td>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#editAppointmentModal" data-appointment-id="<?php echo $row['appointment_id']; ?>" data-appointment-date="<?php echo $row['appointment_date']; ?>" data-status="<?php echo $row['appointment_status']; ?>">Edit</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Edit Appointment Modal -->
<div class="modal fade" id="editAppointmentModal" tabindex="-1" aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="view_appointments.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAppointmentModalLabel">Edit Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="appointment_id" id="appointment_id">
                    <div class="form-group">
                        <label for="appointment_date">Appointment Date</label>
                        <input type="datetime-local" name="appointment_date" class="form-control" id="appointment_date" min="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status" required>
                            <option value="scheduled">Scheduled</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#appointmentsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });

    $('#editAppointmentModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var appointmentId = button.data('appointment-id');
        var appointmentDate = button.data('appointment-date');
        var status = button.data('status');
        
        var modal = $(this);
        modal.find('#appointment_id').val(appointmentId);
        modal.find('#appointment_date').val(appointmentDate.replace(" ", "T"));
        modal.find('#status').val(status);
    });
});
</script>
</body>
</html>

<?php
mysqli_close($conn);
?>
