<?php
include('config.php');
session_start();

// Check if user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables
$test_id = $test_name = $test_date = $test_price = $result = "";
$test_name_err = $test_date_err = $test_price_err = "";

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate test name
    if (empty(trim($_POST["test_name"]))) {
        $test_name_err = "Please enter the test name.";
    } else {
        $test_name = trim($_POST["test_name"]);
    }

    // Validate test date
    if (empty(trim($_POST["test_date"]))) {
        $test_date_err = "Please select the test date.";
    } else {
        $test_date = trim($_POST["test_date"]);
    }

    // Validate test price
    if (empty(trim($_POST["test_price"]))) {
        $test_price_err = "Please enter the test price.";
    } else {
        $test_price = trim($_POST["test_price"]);
    }

    // Validate result
    $result = trim($_POST["result"]);

    // Check input errors before updating the database
    if (empty($test_name_err) && empty($test_date_err) && empty($test_price_err)) {
        // Prepare an update statement
        $sql = "UPDATE DiagnosticTests SET test_name = ?, test_date = ?, test_price = ?, result = ? WHERE test_id = ?";

        if ($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssdsi", $param_test_name, $param_test_date, $param_test_price, $param_result, $param_test_id);

            // Set parameters
            $param_test_name = $test_name;
            $param_test_date = $test_date;
            $param_test_price = $test_price;
            $param_result = $result;
            $param_test_id = $_GET["id"];

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
    }

    // Close connection
    mysqli_close($conn);
} else {
    // Retrieve the test information from the database
    if (isset($_GET["id"])) {
        $sql = "SELECT test_name, test_date, test_price, result FROM DiagnosticTests WHERE test_id = ?";
        if ($stmt = mysqli_prepare($conn, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $param_test_id);
            $param_test_id = $_GET["id"];
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $test_name, $test_date, $test_price, $result);
                    mysqli_stmt_fetch($stmt);
                } else {
                    echo "Test not found.";
                    exit;
                }
            }
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Diagnostic Test</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>Edit Diagnostic Test</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $_GET["id"]; ?>" method="post">
        <div class="form-group">
            <label>Test Name</label>
            <input type="text" name="test_name" class="form-control <?php echo (!empty($test_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $test_name; ?>">
            <span class="invalid-feedback"><?php echo $test_name_err; ?></span>
        </div>
        <div class="form-group">
            <label>Test Date</label>
            <input type="datetime-local" name="test_date" class="form-control <?php echo (!empty($test_date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $test_date; ?>">
            <span class="invalid-feedback"><?php echo $test_date_err; ?></span>
        </div>
        <div class="form-group">
            <label>Test Price</label>
            <input type="text" name="test_price" class="form-control <?php echo (!empty($test_price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $test_price; ?>">
            <span class="invalid-feedback"><?php echo $test_price_err; ?></span>
        </div>
        <div class="form-group">
            <label>Result</label>
            <textarea name="result" class="form-control" rows="3"><?php echo $result; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Diagnostic Test</button>
        <a href="view_tests.php" class="btn btn-outline-dark">Cancel</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
