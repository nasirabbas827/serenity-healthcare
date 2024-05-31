<?php
include('config.php');
session_start();

// Check if user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Function to handle test deletion
function deleteTest($conn, $test_id) {
    $sql = "DELETE FROM DiagnosticTests WHERE test_id = ?";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $test_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Check if delete request is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["delete_test_id"])) {
        deleteTest($conn, $_POST["delete_test_id"]);
    }
}

// Fetch all diagnostic tests from the database
$sql = "SELECT dt.test_id, p.username AS patient_name, dt.test_name, dt.test_date, dt.test_price, dt.result 
        FROM DiagnosticTests dt
        INNER JOIN Patients p ON dt.patient_id = p.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Diagnostic Tests</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>View Diagnostic Tests</h2>
    <div class="table-responsive">
        <table id="testsTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Test ID</th>
                    <th>Patient Name</th>
                    <th>Test Name</th>
                    <th>Test Date</th>
                    <th>Test Price</th>
                    <th>Result</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['test_id']; ?></td>
                    <td><?php echo $row['patient_name']; ?></td>
                    <td><?php echo $row['test_name']; ?></td>
                    <td><?php echo $row['test_date']; ?></td>
                    <td><?php echo $row['test_price']; ?></td>
                    <td><?php echo $row['result']; ?></td>
                    <td>
                        <a href="edit_test.php?id=<?php echo $row['test_id']; ?>" class="btn btn-outline-primary">Edit</a>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline-block;">
                            <input type="hidden" name="delete_test_id" value="<?php echo $row['test_id']; ?>">
                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure you want to delete this test?')">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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
    $('#testsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
});
</script>
</body>
</html>

<?php
mysqli_close($conn);
?>
