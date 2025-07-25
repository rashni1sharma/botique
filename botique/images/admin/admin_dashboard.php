<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "botique";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - Dress Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4 text-center">👗 Dress Rental Admin Dashboard</h2>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <?php
                $countResult = $conn->query("SELECT COUNT(*) AS total FROM clothes");
                $totalDresses = $countResult->fetch_assoc()['total'];

                $incomeResult = $conn->query("SELECT SUM(price) AS total_income FROM clothes");
                $totalIncome = $incomeResult->fetch_assoc()['total_income'];
            ?>
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Dresses</h5>
                        <p class="card-text fs-4"><?= $totalDresses ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Estimated Total Income</h5>
                        <p class="card-text fs-4">Rs<?= number_format($totalIncome, 2) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-dark mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Last Activity</h5>
                        <p class="card-text fs-6">Check activity log (future)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Dress Button -->
        <div class="d-flex justify-content-end mb-3">
            <a href="add.php" class="btn btn-success">+ Add New Dress</a>
        </div>

        <!-- Dress List Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Dress Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
$result = $conn->query("SELECT * FROM clothes ORDER BY clothes_id DESC");
if ($result === false) {
    // Display the error for debugging (remove this in production)
    die("Query failed: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['clothes_id']}</td>
        <td>{$row['clothes_name']}</td>
        <td>{$row['description']}</td>
        <td>{$row['price']}</td>
        <td><img src='../{$row['image']}' width='60'></td>
        <td>
            <a href='edit.php?id={$row['clothes_id']}' class='btn btn-warning btn-sm'>Edit</a>
            <a href='delete.php?id={$row['clothes_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
        </td>
    </tr>";
}
?>
<?php
$countResult = $conn->query("SELECT COUNT(*) AS total FROM clothes");
if ($countResult === false) {
    die("Count query failed: " . $conn->error);
}
$totalDresses = $countResult->fetch_assoc()['total'];

$incomeResult = $conn->query("SELECT SUM(price) AS total_income FROM clothes");
if ($incomeResult === false) {
    die("Income query failed: " . $conn->error);
}
$totalIncome = $incomeResult->fetch_assoc()['total_income'];
?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
