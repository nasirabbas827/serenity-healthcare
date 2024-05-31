<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle deletion of a doctor
if (isset($_GET['delete_id'])) {
    $doctor_id = $_GET['delete_id'];
    $sql = "DELETE FROM doctors WHERE doctor_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: view_doctors.php");
    exit;
}

// Fetch all doctors from the database
$sql = "SELECT * FROM doctors";
$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>View Doctors</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<?php
include('admin_navbar.php');
?>

<div class="container mt-5">
    <h2 class="text-center">Doctors List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Picture</th>
                <th>Username</th>
                <th>Email</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Duty Timings</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><img src="<?php echo $row['picture']; ?>" alt="Doctor Picture" width="50"></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['specialization']; ?></td>
                    <td><?php echo $row['duty_timings']; ?></td>
                    <td>
                        <a href="edit_doctor.php?doctor_id=<?php echo $row['doctor_id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="view_doctors.php?delete_id=<?php echo $row['doctor_id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this doctor?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
