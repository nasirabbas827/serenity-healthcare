<?php
include('config.php');

if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    // Fetch appointments
    $appointments_sql = "SELECT * FROM appointments WHERE patient_id = $patient_id";
    $appointments_result = mysqli_query($conn, $appointments_sql);

    $appointments = [];
    while ($row = mysqli_fetch_assoc($appointments_result)) {
        $appointments[] = [
            'appointment_id' => $row['appointment_id'],
            'appointment_date' => $row['appointment_date'],
            'status' => $row['status']
        ];
    }

    // Fetch diagnostic tests
    $tests_sql = "SELECT * FROM DiagnosticTests WHERE patient_id = $patient_id";
    $tests_result = mysqli_query($conn, $tests_sql);

    $tests = [];
    while ($row = mysqli_fetch_assoc($tests_result)) {
        $tests[] = [
            'test_name' => $row['test_name'],
            'test_date' => $row['test_date'],
            'test_price' => $row['test_price']
        ];
    }

    echo json_encode(['appointments' => $appointments, 'tests' => $tests]);
}
?>
