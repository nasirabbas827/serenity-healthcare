<?php
include('config.php');
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all complaints with patient name from the database
$sql = "SELECT c.complaint_id, c.patient_id, p.username AS patient_name, c.complaint_text, c.submission_date, c.admin_reply, c.status
        FROM Complaints c
        INNER JOIN Patients p ON c.patient_id = p.id";
$result = mysqli_query($conn, $sql);

// Function to update complaint status
function updateStatus($complaint_id, $status) {
    global $conn;
    $sql = "UPDATE Complaints SET status = ? WHERE complaint_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $complaint_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Function to add admin reply
function addReply($complaint_id, $reply) {
    global $conn;
    $sql = "UPDATE Complaints SET admin_reply = ? WHERE complaint_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $reply, $complaint_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Process form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['status'])) {
        // Update complaint status
        $complaint_id = $_POST['complaint_id'];
        $status = $_POST['status'];
        updateStatus($complaint_id, $status);
    } elseif (isset($_POST['reply'])) {
        // Add admin reply
        $complaint_id = $_POST['complaint_id'];
        $reply = $_POST['reply'];
        addReply($complaint_id, $reply);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Complaints</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>View Complaints</h2>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Complaint ID</th>
                    <th>Patient Name</th>
                    <th>Complaint Text</th>
                    <th>Submission Date</th>
                    <th>Admin Reply</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $row['complaint_id']; ?></td>
                        <td><?php echo $row['patient_name']; ?></td>
                        <td><?php echo $row['complaint_text']; ?></td>
                        <td><?php echo $row['submission_date']; ?></td>
                        <td><?php echo $row['admin_reply']; ?></td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="complaint_id" value="<?php echo $row['complaint_id']; ?>">
                                <select class="form-control"  name="status" onchange="this.form.submit()">
                                    <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="resolved" <?php if ($row['status'] == 'resolved') echo 'selected'; ?>>Resolved</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="complaint_id" value="<?php echo $row['complaint_id']; ?>">
                                <input class="form-control" type="text" name="reply" placeholder="Add Reply">
                                <button class="btn btn-success m-2"  type="submit">Submit</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
