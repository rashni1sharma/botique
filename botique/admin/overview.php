<?php
// Start session and check admin authenticatio

// Database connection
$conn = new mysqli("localhost", "root", "", "botique");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set default time period (current month)
$current_year = date('Y');
$current_month = date('m');
$year = isset($_GET['year']) ? intval($_GET['year']) : $current_year;
$month = isset($_GET['month']) ? intval($_GET['month']) : $current_month;

// Get summary statistics
function getSummaryStats($conn, $year, $month) {
    $stats = [];
    
    // Monthly income
    $result = $conn->query("SELECT SUM(total_amount) as income 
                          FROM orders 
                          WHERE YEAR(order_date) = $year 
                          AND MONTH(order_date) = $month");
    $stats['income'] = $result->fetch_assoc()['income'] ?? 0;
    
    // Total orders
    $result = $conn->query("SELECT COUNT(*) as orders 
                          FROM orders 
                          WHERE YEAR(order_date) = $year 
                          AND MONTH(order_date) = $month");
    $stats['orders'] = $result->fetch_assoc()['orders'] ?? 0;
    
    // New customers
    $result = $conn->query("SELECT COUNT(*) as customers 
                          FROM users 
                          WHERE YEAR(created_at) = $year 
                          AND MONTH(created_at) = $month");
    $stats['customers'] = $result->fetch_assoc()['customers'] ?? 0;
    
    return $stats;
}

// Get monthly trends
function getMonthlyTrends($conn) {
    $trends = [];
    $result = $conn->query("SELECT 
                          YEAR(order_date) as year, 
                          MONTH(order_date) as month, 
                          COUNT(*) as order_count,
                          SUM(total_amount) as income
                          FROM orders
                          GROUP BY YEAR(order_date), MONTH(order_date)
                          ORDER BY year DESC, month DESC
                          LIMIT 12");
    while ($row = $result->fetch_assoc()) {
        $trends[] = $row;
    }
    return $trends;
}

// Get popular items
function getPopularItems($conn, $year, $month) {
    $items = [];
    $result = $conn->query("SELECT 
                          c.name, 
                          COUNT(oi.order_id) as order_count,
                          SUM(oi.quantity) as total_quantity
                          FROM order_items oi
                          JOIN clothes c ON oi.cloth_id = c.id
                          JOIN orders o ON oi.order_id = o.order_id
                          WHERE YEAR(o.order_date) = $year 
                          AND MONTH(o.order_date) = $month
                          GROUP BY c.id
                          ORDER BY total_quantity DESC
                          LIMIT 10");
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}

// Get recent orders
function getRecentOrders($conn, $limit = 10) {
    $orders = [];
    $result = $conn->query("SELECT 
                          o.order_id, 
                          u.username,
                          o.total_amount,
                          o.order_date,
                          o.status
                          FROM orders o
                          JOIN users u ON o.user_id = u.user_id
                          ORDER BY o.order_date DESC
                          LIMIT $limit");
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    return $orders;
}

// Get data
$stats = getSummaryStats($conn, $year, $month);
$trends = getMonthlyTrends($conn);
$popular_items = getPopularItems($conn, $year, $month);
$recent_orders = getRecentOrders($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Summary - Boutique Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card { border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.3s; }
        .card:hover { transform: translateY(-5px); }
        .stat-card { color: white; }
        .chart-container { position: relative; height: 300px; }
        .status-badge { padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; }
        .status-completed { background-color: #28a745; color: white; }
        .status-pending { background-color: #ffc107; color: black; }
        .status-cancelled { background-color: #dc3545; color: white; }
        .progress-thin { height: 6px; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><i class="bi bi-bar-chart-line"></i> Reports & Summary</h1>
            <a href="dashboard.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
        </div>

        <!-- Period Selector -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label class="form-label">Year</label>
                        <select name="year" class="form-select">
                            <?php for ($y = $current_year; $y >= $current_year - 5; $y--): ?>
                                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Month</label>
                        <select name="month" class="form-select">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-filter"></i> Apply Filter</button>
                    </div>
                    <div class="col-md-3 text-end">
                        <a href="reports.php?export=1&year=<?= $year ?>&month=<?= $month ?>" class="btn btn-success">
                            <i class="bi bi-file-earmark-excel"></i> Export to Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stat-card bg-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Monthly Income</h6>
                                <h2>Rs. <?= number_format($stats['income'], 2) ?></h2>
                            </div>
                            <i class="bi bi-currency-rupee" style="font-size: 2rem; opacity: 0.3;"></i>
                        </div>
                        <div class="progress progress-thin mt-2">
                            <div class="progress-bar bg-white" style="width: <?= min(100, ($stats['income'] / max(1, $stats['income'])) * 100) ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Total Orders</h6>
                                <h2><?= $stats['orders'] ?></h2>
                            </div>
                            <i class="bi bi-cart-check" style="font-size: 2rem; opacity: 0.3;"></i>
                        </div>
                        <div class="progress progress-thin mt-2">
                            <div class="progress-bar bg-white" style="width: <?= min(100, ($stats['orders'] / max(1, $stats['orders'])) * 100) ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">New Customers</h6>
                                <h2><?= $stats['customers'] ?></h2>
                            </div>
                            <i class="bi bi-people" style="font-size: 2rem; opacity: 0.3;"></i>
                        </div>
                        <div class="progress progress-thin mt-2">
                            <div class="progress-bar bg-white" style="width: <?= min(100, ($stats['customers'] / max(1, $stats['customers'])) * 100) ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stat-card bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title">Avg. Order Value</h6>
                                <h2>Rs. <?= number_format($stats['orders'] > 0 ? $stats['income'] / $stats['orders'] : 0, 2) ?></h2>
                            </div>
                            <i class="bi bi-graph-up" style="font-size: 2rem; opacity: 0.3;"></i>
                        </div>
                        <div class="progress progress-thin mt-2">
                            <div class="progress-bar bg-white" style="width: <?= min(100, ($stats['orders'] / max(1, $stats['orders'])) * 100) ?>%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Monthly Income Trend</h5>
                        <div class="chart-container">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Order Status Distribution</h5>
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Recent Orders</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_orders as $order): ?>
                                        <tr>
                                            <td>#<?= $order['order_id'] ?></td>
                                            <td><?= htmlspecialchars($order['username']) ?></td>
                                            <td>Rs. <?= number_format($order['total_amount'], 2) ?></td>
                                            <td><?= date('M d, Y', strtotime($order['order_date'])) ?></td>
                                            <td>
                                                <span class="status-badge status-<?= strtolower($order['status']) ?>">
                                                    <?= $order['status'] ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">Top Selling Items</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Orders</th>
                                        <th>Quantity</th>
                                        <th>Popularity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($popular_items as $item): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($item['name']) ?></td>
                                            <td><?= $item['order_count'] ?></td>
                                            <td><?= $item['total_quantity'] ?></td>
                                            <td>
                                                <div class="progress progress-thin">
                                                    <div class="progress-bar bg-success" 
                                                         style="width: <?= min(100, ($item['total_quantity'] / max(1, $popular_items[0]['total_quantity'])) * 100) ?>%">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Income Trend Chart
        const incomeCtx = document.getElementById('incomeChart').getContext('2d');
        const incomeChart = new Chart(incomeCtx, {
            type: 'line',
            data: {
                labels: [<?= implode(',', array_map(function($t) { 
                    return "'" . date('M Y', mktime(0, 0, 0, $t['month'], 1, $t['year'])) . "'"; 
                }, $trends)) ?>],
                datasets: [{
                    label: 'Monthly Income (Rs.)',
                    data: [<?= implode(',', array_column($trends, 'income')) ?>],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rs. ' + context.raw.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        // Status Distribution Chart (example data - replace with actual query)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'Pending', 'Cancelled'],
                datasets: [{
                    data: [75, 15, 10], // Replace with actual data from your database
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(220, 53, 69, 0.8)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>