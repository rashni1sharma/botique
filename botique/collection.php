<?php
$conn = new mysqli("localhost", "root", "", "botique");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Our Collection</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="UTF-8">
  <meta name="description" content="Explore our stunning collection of clothes available for rent.">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .text-pink { color: #e91e63; }
    .btn-outline-danger {
      border-color: #e91e63;
      color: #e91e63;
    }
    .btn-outline-danger:hover {
      background-color: #e91e63;
      color: white;
    }
    .search-container {
      margin-bottom: 30px;
      background: #f8f9fa;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .card {
      transition: transform 0.3s ease;
      cursor: pointer;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    a.card-link {
      text-decoration: none;
      color: inherit;
    }
  </style>
</head>
<body>

<div class="container py-5">
  <h2 class="text-center text-pink mb-4">Our Stunning Collection</h2>
  
  <!-- Search Section -->
  <div class="search-container">
    <form method="get" class="row g-3">
      <div class="col-md-6">
        <label for="searchPrice" class="form-label">Search by Price</label>
        <div class="input-group">
          <input type="number" class="form-control" id="searchPrice" name="searchPrice" placeholder="Enter price to search">
          <button class="btn btn-outline-danger" type="submit">Search</button>
        </div>
      </div>
      <div class="col-md-6">
        <label for="searchName" class="form-label">Search by Name</label>
        <div class="input-group">
          <input type="text" class="form-control" id="searchName" name="searchName" placeholder="Enter item name">
          <button class="btn btn-outline-danger" type="submit">Search</button>
        </div>
      </div>
    </form>
  </div>

  <div class="row g-4">
    <?php 
    // Binary Search Function
    function binarySearchByPrice($items, $targetPrice) {
        $left = 0;
        $right = count($items) - 1;
        $results = [];
        
        while ($left <= $right) {
            $mid = floor(($left + $right) / 2);
            
            if ($items[$mid]['price'] == $targetPrice) {
                $results[] = $items[$mid];
                
                // Check left side
                $i = $mid - 1;
                while ($i >= 0 && $items[$i]['price'] == $targetPrice) {
                    $results[] = $items[$i];
                    $i--;
                }
                
                // Check right side
                $i = $mid + 1;
                while ($i < count($items) && $items[$i]['price'] == $targetPrice) {
                    $results[] = $items[$i];
                    $i++;
                }
                
                return $results;
            }
            
            if ($items[$mid]['price'] < $targetPrice) {
                $left = $mid + 1;
            } else {
                $right = $mid - 1;
            }
        }
        
        return $results;
    }

    // Get all items and sort them by price for binary search
    $sql = "SELECT * FROM collection ORDER BY price ASC";
    $result = $conn->query($sql);
    $allItems = [];
    while($row = $result->fetch_assoc()) {
        $allItems[] = $row;
    }

    // Check if we're searching by price
    if (isset($_GET['searchPrice']) && !empty($_GET['searchPrice'])) {
        $targetPrice = (float)$_GET['searchPrice'];
        $foundItems = binarySearchByPrice($allItems, $targetPrice);
        
        if (!empty($foundItems)) {
            foreach ($foundItems as $row): ?>
                <div class="col-md-3">
                  <a href="form.php?item_id=<?php echo $row['collection_id']; ?>" class="card-link">
                    <div class="card shadow-sm h-100">
                      <img src="images/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                      <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Rs. <?php echo htmlspecialchars($row['price']); ?></strong></p>
                        <p>
                          <?php
                            $stars = floor($row['rating']);
                            for ($i = 1; $i <= 5; $i++) {
                              echo $i <= $stars ? "⭐" : "☆";
                            }
                          ?>
                          (<?php echo htmlspecialchars($row['rating']); ?>)
                        </p>
                      </div>
                    </div>
                  </a>
                </div>
            <?php endforeach;
        } else {
            echo '<div class="col-12 text-center"><p>No items found with price Rs. ' . htmlspecialchars($targetPrice) . '</p></div>';
        }
    } 
    // Check if we're searching by name
    elseif (isset($_GET['searchName']) && !empty($_GET['searchName'])) {
        $searchTerm = $conn->real_escape_string($_GET['searchName']);
        $sql = "SELECT * FROM collection WHERE name LIKE '%$searchTerm%'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()): ?>
                <div class="col-md-3">
                  <a href="form.php?item_id=<?php echo $row['collection_id']; ?>" class="card-link">
                    <div class="card shadow-sm h-100">
                      <img src="images/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                      <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                        <p><strong>Rs. <?php echo htmlspecialchars($row['price']); ?></strong></p>
                        <p>
                          <?php
                            $stars = floor($row['rating']);
                            for ($i = 1; $i <= 5; $i++) {
                              echo $i <= $stars ? "⭐" : "☆";
                            }
                          ?>
                          (<?php echo htmlspecialchars($row['rating']); ?>)
                        </p>
                      </div>
                    </div>
                  </a>
                </div>
            <?php endwhile;
        } else {
            echo '<div class="col-12 text-center"><p>No items found with name "' . htmlspecialchars($_GET['searchName']) . '"</p></div>';
        }
    }
    // Show all items if no search
    else {
        foreach ($allItems as $row): ?>
            <div class="col-md-3">
              <a href="form.php?item_id=<?php echo $row['collection_id']; ?>" class="card-link">
                <div class="card shadow-sm h-100">
                  <img src="images/<?php echo htmlspecialchars($row['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['name']); ?>">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                    <p><strong>Rs. <?php echo htmlspecialchars($row['price']); ?></strong></p>
                    <p>
                      <?php
                        $stars = floor($row['rating']);
                        for ($i = 1; $i <= 5; $i++) {
                          echo $i <= $stars ? "⭐" : "☆";
                        }
                      ?>
                      (<?php echo htmlspecialchars($row['rating']); ?>)
                    </p>
                  </div>
                </div>
              </a>
            </div>
        <?php endforeach;
    } 
    ?>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>