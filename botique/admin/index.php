<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "botique");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total users count
$userCount = 0;
$userResult = $conn->query("SELECT COUNT(*) as total_users FROM users");
if ($userResult) {
    $userData = $userResult->fetch_assoc();
    $userCount = $userData['total_users'];
}

// Get total clothes count
$clothesCount = 0;
$clothesResult = $conn->query("SELECT COUNT(*) as total_clothes FROM collection");
if ($clothesResult) {
    $clothesData = $clothesResult->fetch_assoc();
    $clothesCount = $clothesData['total_clothes'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .sidebar {
            height: 100vh;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
            width: 250px;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .dashboard-card {
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .nav-link:hover, .nav-link.active {
            background-color: #0d6efd;
            color: white;
        }
        .chart-container {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="text-center mb-4">
            <h4>Admin Dashboard</h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#"><i class="fas fa-tachometer-alt"></i> Dashboard Overview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="add_item.php"><i class="fas fa-tshirt"></i> Manage Clothes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_user.php"><i class="fas fa-users"></i> Manage Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#"><i class="fas fa-chart-bar"></i> Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fas fa-cog"></i> Logout</a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <h2 class="mb-4">Dashboard Overview</h2>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="dashboard-card bg-primary text-white">
                    <h5>Total Users</h5>
                    <h2><?php echo $userCount; ?></h2>
                    <p>Registered users</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card bg-success text-white">
                    <h5>Total Clothes</h5>
                    <h2><?php echo $clothesCount; ?></h2>
                    <p>Products in collection</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="dashboard-card bg-info text-white">
                    <h5>Total Sales</h5>
                    <h2>Rs. 25,000</h2>
                    <p>This month</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Clothes Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="pieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">

                            <li class="list-group-item">New user registered - Roshani Sharma</li>
                            <li class="list-group-item">Product "Sarre" added</li>
                            <li class="list-group-item">Order #1234 completed</li>
                            <li class="list-group-item">Inventory updated</li>
                            <li class="list-group-item">System backup performed</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <h2 class="mt-5 mb-3">Clothes List</h2>
        <a href="add_item.php" class="btn btn-primary mb-3">+ Add New Cloth</a>
        <a href="overview.php" class="btn btn-info mb-3">📊 Monthly Overview</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $conn->query("SELECT * FROM collection");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['collection_id']}</td>
                            <td>{$row['name']}</td>
                            <td>Rs. {$row['price']}</td>
                            <td>{$row['description']}</td>
                            <td><img src='uploads/collection/{$row['image']}' alt='{$row['name']}' class='img-thumbnail' style='width: 100px;'></td>
                            <td>
                                <a href='edit.php?id={$row['collection_id']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='delete.php?id={$row['collection_id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                        </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        // Pie Chart Data
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Saree', 'Lehengha', 'Dresses', 'Accessories', 'Other'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        '#4e73df',
                        '#1cc88a',
                        '#36b9cc',
                        '#f6c23e',
                        '#e74a3b'
                    ],
                    hoverBackgroundColor: [
                        '#2e59d9',
                        '#17a673',
                        '#2c9faf',
                        '#dda20a',
                        '#be2617'
                    ],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                    title: {
                        display: true,
                        text: 'Clothing Categories Distribution',
                        fontSize: 18,
                        fontColor: '#333',
                    },
                },
            },
        });
    </script>
</body>
</html>