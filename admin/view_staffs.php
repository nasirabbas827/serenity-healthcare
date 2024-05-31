<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle delete request
if (isset($_GET['delete'])) {
    $staff_id = $_GET['delete'];
    $sql = "DELETE FROM staff WHERE staff_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $staff_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        header("Location: view_staffs.php");
    }
}

// Fetch all staff members
$sql = "SELECT * FROM staff";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Staff Members</title>
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
    <h2 class="text-center">Staff Members</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Department</th>
                <th>Salary</th>
                <th>Duty Timings</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['staff_id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['designation']; ?></td>
                    <td><?php echo $row['department']; ?></td>
                    <td><?php echo $row['salary']; ?></td>
                    <td><?php echo $row['duty_timings']; ?></td>
                    <td>
                        <a href="edit_staff.php?staff_id=<?php echo $row['staff_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="view_staffs.php?delete=<?php echo $row['staff_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this staff member?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
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
