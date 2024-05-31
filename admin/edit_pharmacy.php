<?php
include('config.php');
session_start();

// Check if user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Check if medicine ID is provided
if (!isset($_GET['medicine_id']) || empty($_GET['medicine_id'])) {
    header("Location: view_pharmacy.php");
    exit;
}

// Initialize variables
$medicine_id = $_GET['medicine_id'];
$medicine_name = $quantity = $purchase_date = $expiry_date = $price = "";

// Fetch pharmacy inventory data for the selected medicine ID
$sql = "SELECT * FROM PharmacyInventory WHERE medicine_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $medicine_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $medicine_name = $row['medicine_name'];
    $quantity = $row['quantity'];
    $purchase_date = $row['purchase_date'];
    $expiry_date = $row['expiry_date'];
    $price = $row['price'];
} else {
    // Redirect if medicine ID is not found
    header("Location: view_pharmacy.php");
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $medicine_name = $_POST["medicine_name"];
    $quantity = $_POST["quantity"];
    $purchase_date = $_POST["purchase_date"];
    $expiry_date = $_POST["expiry_date"];
    $price = $_POST["price"];

    // Update pharmacy inventory in the database
    $sql = "UPDATE PharmacyInventory SET medicine_name = ?, quantity = ?, purchase_date = ?, expiry_date = ?, price = ? WHERE medicine_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sisssi", $medicine_name, $quantity, $purchase_date, $expiry_date, $price, $medicine_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Redirect to view pharmacy page
    header("Location: view_pharmacy.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pharmacy Inventory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Edit Pharmacy Inventory</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . '?medicine_id=' . $medicine_id); ?>" method="post">
        <div class="form-group">
            <label>Medicine Name</label>
            <input type="text" name="medicine_name" class="form-control" value="<?php echo $medicine_name; ?>" required>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo $quantity; ?>" required>
        </div>
        <div class="form-group">
            <label>Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" value="<?php echo $purchase_date; ?>" required>
        </div>
        <div class="form-group">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" value="<?php echo $expiry_date; ?>" required>
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="text" name="price" class="form-control" value="<?php echo $price; ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Inventory</button>
        <a href="view_pharmacy.php" class="btn btn-outline-dark">Cancel</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
