<?php include 'db.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['clothes_name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image']['clothes_name'];
    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$image);

    $conn->query("INSERT INTO clothes (clothes_name, description, price, image)
                  VALUES ('$name', '$desc', '$price', '$image')");
    header("Location: admin_dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Cloth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2>Add New Cloth</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-2"><input class="form-control" name="clothes_name" placeholder="clothes_name" required></div>
            <div class="mb-2"><textarea class="form-control" name="description" placeholder="description" required></textarea></div>
            <div class="mb-2"><input class="form-control" name="price" type="number" placeholder="Price" required></div>
            <div class="mb-2"><input class="form-control" name="image" type="file" required></div>
            <button class="btn btn-success" type="submit">Save</button>
            <a href="index.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>
