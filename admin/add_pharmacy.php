<?php
include('config.php');
session_start();

// Check if user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
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

    // Insert pharmacy inventory into the database
    $sql = "INSERT INTO PharmacyInventory (medicine_name, quantity, purchase_date, expiry_date, price) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssd", $medicine_name, $quantity, $purchase_date, $expiry_date, $price);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    // Redirect to admin dashboard
    header("Location: view_pharmacy.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Pharmacy Inventory</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mt-5 mb-5">
    <h2>Add Pharmacy Inventory</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Medicine Name</label>
            <input type="text" name="medicine_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Purchase Date</label>
            <input type="date" name="purchase_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="text" name="price" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Inventory</button>
        <a href="view_pharmacy.php" class="btn btn-outline-dark">View Pharmacy Inventory</a>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
