<?php
include('config.php');
session_start();

// Check if user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Delete pharmacy inventory if delete button is clicked
if (isset($_POST['delete']) && isset($_POST['medicine_id'])) {
    $medicine_id = $_POST['medicine_id'];
    $sql = "DELETE FROM PharmacyInventory WHERE medicine_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $medicine_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

// Fetch pharmacy inventory data
$sql = "SELECT * FROM PharmacyInventory";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Pharmacy Inventory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Pharmacy Inventory</h2>
    <table id="inventoryTable" class="table table-bordered">
        <thead>
            <tr>
                <th>Medicine ID</th>
                <th>Medicine Name</th>
                <th>Quantity</th>
                <th>Purchase Date</th>
                <th>Expiry Date</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['medicine_id']; ?></td>
                    <td><?php echo $row['medicine_name']; ?></td>
                    <td><?php echo $row['quantity']; ?></td>
                    <td><?php echo $row['purchase_date']; ?></td>
                    <td><?php echo $row['expiry_date']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td>
                        <a href="edit_pharmacy.php?medicine_id=<?php echo $row['medicine_id']; ?>" class="btn btn-outline-primary">Edit</a>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="medicine_id" value="<?php echo $row['medicine_id']; ?>">
                            <button type="submit" name="delete" class="btn btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
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
    $('#inventoryTable').DataTable({
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
