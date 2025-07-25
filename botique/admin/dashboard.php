<?php
$conn = new mysqli("localhost", "root", "", "botique");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
session_start();
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: dashboard.php");
    exit();
}   
?>
</head>
<body class="container mt-5">
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .card { border-radius: 1rem; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="container mt-5">
    <h2 class="mb-4">🧵 Boutique Admin Dashboard</h2>

    

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card p-3 text-white bg-success">
                <h5>Total Income</h5>
                <h3>Rs. <?= number_format($income, 2) ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-white bg-primary">
                <h5>Total Orders</h5>
                <h3><?= $total_orders ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-white bg-warning">
                <h5>Active Users</h5>
                <h3><?= $total_users ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-white bg-info">
                <h5>Available Clothes</h5>
                <h3><?= $total_clothes ?></h3>
            </div>
        </div>
    </div>

    <div class="d-flex gap-3">
        <a href="index.php" class="btn btn-outline-primary">👕 Manage Clothes</a>
        <a href="overview.php" class="btn btn-outline-success">📊 Monthly Report</a>
        <a href="#" class="btn btn-outline-dark">👤 Admin Profile</a>
    </div>
</body>
</html>
