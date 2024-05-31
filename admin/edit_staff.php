<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Get staff ID from URL
$staff_id = $_GET['staff_id'];

// Fetch staff details
$sql = "SELECT * FROM staff WHERE staff_id = ?";
if ($stmt = mysqli_prepare($conn, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $staff_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $staff = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $name = $_POST["name"];
    $designation = $_POST["designation"];
    $department = $_POST["department"];
    $salary = $_POST["salary"];
    $duty_timings = $_POST["duty_timings"];

    $sql = "UPDATE staff SET username = ?, email = ?, name = ?, designation = ?, department = ?, salary = ?, duty_timings = ? WHERE staff_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssssssi", $username, $email, $name, $designation, $department, $salary, $duty_timings, $staff_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: view_staffs.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Staff Member</title>
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
    <h2 class="text-center">Edit Staff Member</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?staff_id=" . $staff_id; ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $staff['username']; ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $staff['email']; ?>" required>
        </div>
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $staff['name']; ?>" required>
        </div>
        <div class="form-group">
            <label>Designation</label>
            <input type="text" name="designation" class="form-control" value="<?php echo $staff['designation']; ?>" required>
        </div>
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="<?php echo $staff['department']; ?>" required>
        </div>
        <div class="form-group">
            <label>Salary</label>
            <input type="number" step="0.01" name="salary" class="form-control" value="<?php echo $staff['salary']; ?>" required>
        </div>
        <div class="form-group">
            <label>Duty Timings</label>
            <input type="text" name="duty_timings" class="form-control" value="<?php echo $staff['duty_timings']; ?>" required>
        </div>
        <div class="form-group text-center">
            <input type="submit" class="btn btn-primary" value="Update Staff">
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
