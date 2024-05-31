<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $name = $_POST["name"];
    $specialization = $_POST["specialization"];
    $duty_timings = $_POST["duty_timings"];
    
    // Handling file upload for the picture
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["picture"]["name"]);
    move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file);

    $sql = "INSERT INTO doctors (picture, username, password, email, name, specialization, duty_timings) VALUES (?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssss", $target_file, $username, $password, $email, $name, $specialization, $duty_timings);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: view_doctors.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor</title>
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
    <h2 class="text-center">Add Doctor</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Picture</label>
            <input type="file" name="picture" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Specialization</label>
            <input type="text" name="specialization" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Duty Timings</label>
            <input type="text" name="duty_timings" class="form-control" required>
        </div>
        <div class="form-group text-center">
            <input type="submit" class="btn btn-primary" value="Add Doctor">
            <a class="btn btn-outline-dark" href="view_doctors.php">View Doctors</a>
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
