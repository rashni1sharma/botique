<?php
session_start(); 
include ('./conn/conn.php');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "botique";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Recommendation logic — Only run if 'id' is passed in URL
$recommend_result = null;

if (isset($_GET['id'])) {
    $item_id = intval($_GET['id']); // Secure the input
    $item_sql = "SELECT * FROM clothes WHERE cloth_id = $item_id";
    $item_result = mysqli_query($conn, $item_sql);

    if ($item_result && mysqli_num_rows($item_result) > 0) {
        $row = mysqli_fetch_assoc($item_result);
        $category = $row['category'];

        $recommend_sql = "SELECT * FROM clothes WHERE category = '$category' AND cloth_id != $item_id LIMIT 4";
        $recommend_result = mysqli_query($conn, $recommend_sql);
    }
}

// Handle search input
$search = "";
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM clothes WHERE cloth_name LIKE '%$search%' OR description LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM clothes";
}

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clothing Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .text-pink { color: #e83e8c; }
    </style>
</head>
<body>
<section class="clothing-images py-4 text-center">
    <div class="container">
        <h3 class="mb-4 text-center text-pink">Explore Our Collection</h3>

        <!-- 🔍 Search Form -->
        <form method="GET" class="mb-4 d-flex justify-content-center">
            <input type="text" name="search" class="form-control w-50 me-2" placeholder="Search for clothes..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- 🛍️ Product Grid -->
        <div class="row">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 shadow">
                            <img src="images/<?= $row['image'] ?>" class="card-img-top" alt="<?= $row['clothes_name'] ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $row['clothes_name'] ?></h5>
                                <p class="card-text"><?= $row['description'] ?></p>
                                <p><strong>Price:</strong> Rs.<?= $row['price'] ?></p>
                                <form action="checkout.php" method="POST">
                                    <input type="hidden" name="pid" value="<?= $row['clothes_id'] ?>">
                                    <input type="hidden" name="amount" value="<?= $row['price'] ?>">
                                    <button type="submit" class="btn btn-outline-success">Rent</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-muted">No clothes found matching your search.</p>
            <?php endif; ?>
        </div>

        <!-- ⭐ Recommendations -->
        <?php if ($recommend_result && mysqli_num_rows($recommend_result) > 0): ?>
            <h4 class="mt-5 text-center text-pink">Recommended for You</h4>
            <div class="row">
                <?php while ($rec = mysqli_fetch_assoc($recommend_result)): ?>
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card h-100 shadow">
                            <img src="images/<?= $rec['image'] ?>" class="card-img-top" alt="<?= $rec['clothes_name'] ?>">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= $rec['clothes_name'] ?></h5>
                                <p class="card-text"><?= $rec['description'] ?></p>
                                <p><strong>Price:</strong> Rs.<?= $rec['price'] ?></p>
                                <form action="checkout.php" method="POST">
                                    <input type="hidden" name="pid" value="<?= $rec['clothes_id'] ?>">
                                    <input type="hidden" name="amount" value="<?= $rec['price'] ?>">
                                    <button type="submit" class="btn btn-outline-success">Pay for rent</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
mysqli_close($conn);
include('./cloth.php');
?>