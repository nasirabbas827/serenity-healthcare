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
    $designation = $_POST["designation"];
    $department = $_POST["department"];
    $salary = $_POST["salary"];
    $duty_timings = $_POST["duty_timings"];

    $sql = "INSERT INTO staff (username, password, email, name, designation, department, salary, duty_timings) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssssssss", $username, $password, $email, $name, $designation, $department, $salary, $duty_timings);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    mysqli_close($conn);
    echo '<div class="alert alert-success" role="alert">Staff member added successfully.</div>';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
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
    <h2 class="text-center">Add Staff Member</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="text" name="password" class="form-control" required>
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
            <label>Designation</label>
            <input type="text" name="designation" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Salary</label>
            <input type="number" step="0.01" name="salary" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Duty Timings</label>
            <input type="text" name="duty_timings" class="form-control" required>
        </div>
        <div class="form-group text-center">
            <input type="submit" class="btn btn-primary" value="Add Staff">
            <a class="btn btn-outline-dark" href="view_staffs.php">View Staff</a>
        </div>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
