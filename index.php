<?php
include('config.php');
session_start();
// Fetch all doctors from the database
$sql = "SELECT * FROM doctors";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Serenity Healthcare Centre</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
 <style>
.jumbotron {
            height: 500px;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('./images/hotel.jpg');
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .jumbotron h1 {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .jumbotron p {
            font-size: 1.5rem;
        }
    </style>
</head>
<body>

<?php
include('navbar.php');
?>

<div class="jumbotron text-center">
    <h1>Welcome to Serenity Healthcare Centre</h1>
    <p>Explore our Healthcare Services with Serenity</p>
    <a href="login.php" class="btn btn-primary btn-lg">Login to Explore</a>
</div>

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
                    <h2 class="text-danger">Login to make Appointments</h2>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer class="mt-5 py-3 bg-light">
    <div class="container text-center">
        <p>&copy; 2024 Serenity Healthcare Centre. All rights reserved.</p>
    </div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
