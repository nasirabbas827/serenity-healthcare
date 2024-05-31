<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch doctor details for the given doctor_id
if (isset($_GET['doctor_id'])) {
    $doctor_id = $_GET['doctor_id'];
    $sql = "SELECT * FROM doctors WHERE doctor_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $doctor_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $doctor = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);
    }
}

// Handle form submission for updating doctor details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST["doctor_id"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $name = $_POST["name"];
    $specialization = $_POST["specialization"];
    $duty_timings = $_POST["duty_timings"];
    
    // Handling file upload for the picture
    if ($_FILES["picture"]["name"]) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["picture"]["name"]);
        move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);
    } else {
        $target_file = $_POST["existing_picture"];
    }

    $sql = "UPDATE doctors SET picture = ?, username = ?, password = ?, email = ?, name = ?, specialization = ?, duty_timings = ? WHERE doctor_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssssi", $target_file, $username, $password, $email, $name, $specialization, $duty_timings, $doctor_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: view_doctors.php");
        exit;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Doctor</title>
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
    <h2 class="text-center">Edit Doctor</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="doctor_id" value="<?php echo $doctor['doctor_id']; ?>">
        <input type="hidden" name="existing_picture" value="<?php echo $doctor['picture']; ?>">
        <div class="form-group">
            <label>Picture</label>
            <input type="file" name="picture" class="form-control">
            <img src="<?php echo $doctor['picture']; ?>" alt="Doctor Picture" width="50">
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $doctor['username']; ?>" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" name="password" class="form-control" value="<?php echo $doctor['password']; ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $doctor['email']; ?>" required>
        </div>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $doctor['name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Specialization</label>
            <input type="text" name="specialization" class="form-control" value="<?php echo $doctor['specialization']; ?>" required>
        </div>
        <div class="form-group">
            <label>Duty Timings</label>
            <input type="text" name="duty_timings" class="form-control" value="<?php echo $doctor['duty_timings']; ?>" required>
        </div>
        <div class="form-group text-center">
            <input type="submit" class="btn btn-primary" value="Update Doctor">
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
?>
