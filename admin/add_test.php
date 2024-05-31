<?php
include('config.php');
session_start();

// Check if user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST["patient_id"];
    $test_name = $_POST["test_name"];
    $test_date = $_POST["test_date"];
    $test_price = $_POST["test_price"];
    $result = $_POST["result"];

    // Prepare an insert statement
    $sql = "INSERT INTO DiagnosticTests (patient_id, test_name, test_date, test_price, result) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($conn, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "issds", $patient_id, $test_name, $test_date, $test_price, $result);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to view diagnostic tests page
            header("location: view_tests.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Diagnostic Test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Add Diagnostic Test</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Patient</label>
            <select name="patient_id" class="form-control">
                <option value="">Select Patient</option>
                <?php
                // Fetch patients from database
                $sql = "SELECT id, username FROM Patients";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['username'] . "</option>";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label>Test Name</label>
            <input type="text" name="test_name" class="form-control">
        </div>
        <div class="form-group">
            <label>Test Date</label>
            <input type="datetime-local" name="test_date" class="form-control">
        </div>
        <div class="form-group">
            <label>Test Price</label>
            <input type="text" name="test_price" class="form-control">
        </div>
        <div class="form-group">
            <label>Result</label>
            <textarea name="result" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Diagnostic Test</button>
        <a href="view_tests.php" class="btn btn-outline-dark">View Tests</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
