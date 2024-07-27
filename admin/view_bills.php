<?php
include('config.php');
session_start();

// Check if user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Handle form submission to update a bill
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $bill_id = $_POST['bill_id'];
    $amount = $_POST['amount'];
    $bill_type = $_POST['bill_type'];
    $description = $_POST['description'];

    $update_sql = "UPDATE Bills SET amount = ?, bill_type = ?, description = ? WHERE bill_id = ?";
    if ($stmt = mysqli_prepare($conn, $update_sql)) {
        mysqli_stmt_bind_param($stmt, "dssi", $amount, $bill_type, $description, $bill_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("Location: view_bills.php");
    exit;
}

// Fetch all bills from the database
$sql = "SELECT b.bill_id, p.username AS patient_name, p.status AS patient_status, b.amount, b.bill_date, b.bill_type, b.description
        FROM Bills b
        INNER JOIN Patients p ON b.patient_id = p.id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Bills</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5">
    <h2>View Bills</h2>
    <div class="table-responsive">
        <table id="billsTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Bill ID</th>
                    <th>Patient Name</th>
                    <th>Patient Status</th> <!-- Added this column -->
                    <th>Amount</th>
                    <th>Bill Date</th>
                    <th>Bill Type</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['bill_id']; ?></td>
                    <td><?php echo $row['patient_name']; ?></td>
                    <td><?php echo ucfirst($row['patient_status']); ?></td> <!-- Display status -->
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['bill_date']; ?></td>
                    <td><?php echo ucfirst($row['bill_type']); ?></td>
                    <td><?php echo $row['description']; ?></td>
                    <td>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#editBillModal"
                                data-bill-id="<?php echo $row['bill_id']; ?>"
                                data-amount="<?php echo $row['amount']; ?>"
                                data-bill-type="<?php echo $row['bill_type']; ?>"
                                data-description="<?php echo $row['description']; ?>">Edit</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Bill Modal -->
<div class="modal fade" id="editBillModal" tabindex="-1" aria-labelledby="editBillModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="view_bills.php" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBillModalLabel">Edit Bill</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="bill_id" id="bill_id">
                    <div class="form-group">
                        <label for="amount">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="bill_type">Bill Type</label>
                        <select class="form-control" id="bill_type" name="bill_type" required>
                            <option value="appointment">Appointment</option>
                            <option value="medical">Medical</option>
                            <option value="diagnostic">Diagnostic</option>
                            <option value="all">All</option>
                            <option value="outdoor">Outdoor Patient Fee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Bill</button>
                </div>
            </form>
        </div>
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
    $('#billsTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });

    $('#editBillModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var billId = button.data('bill-id');
        var amount = button.data('amount');
        var billType = button.data('bill-type');
        var description = button.data('description'); 

        var modal = $(this);
        modal.find('#bill_id').val(billId);
        modal.find('#amount').val(amount);
        modal.find('#bill_type').val(billType);
        modal.find('#description').val(description); 
    });
});
</script>
</body>
</html>
<?php mysqli_close($conn); ?>
