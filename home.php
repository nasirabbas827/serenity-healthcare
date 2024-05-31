<?php
include('config.php');
session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Fetch all doctors from the database
$sql = "SELECT * FROM doctors";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Home Page</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<?php include('navbar.php'); ?>

<div class="container mt-5">
    <h2>Our Doctors</h2>
    <div class="row">
        <?php while ($doctor = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="admin/<?php echo $doctor['picture']; ?>" class="card-img-top" alt="Doctor Picture">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $doctor['name']; ?></h5>
                        <p class="card-text"><?php echo $doctor['specialization']; ?></p>
                        <p class="card-text"><?php echo $doctor['duty_timings']; ?></p>
                        <button class="btn btn-primary make-appointment-btn" data-toggle="modal" data-target="#appointmentModal" data-doctor-id="<?php echo $doctor['doctor_id']; ?>">Make Appointment</button>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Appointment Modal -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="make_appointment.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Make Appointment</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="patient_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="doctor_id" id="doctor_id">
                    <div class="form-group">
                        <label for="appointment_date">Appointment Date</label>
                        <input type="datetime-local" name="appointment_date" class="form-control" id="appointment_date" min="<?php echo date('Y-m-d\TH:i'); ?>" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Schedule Appointment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // JavaScript to set doctor_id in the appointment modal when clicking "Make Appointment" button
    $('.make-appointment-btn').click(function() {
        var doctorId = $(this).data('doctor-id');
        $('#doctor_id').val(doctorId);
    });
</script>
</body>
</html>

<?php
mysqli_close($conn);
?>
