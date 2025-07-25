<?php include 'db.php'; ?>
<?php
$id = $_GET['id'];
$item = $conn->query("SELECT * FROM clothes WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];

    if ($_FILES['image']['name']) {
        $image = $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$image);
        $conn->query("UPDATE clothes SET name='$name', description='$desc', price='$price', image='$image' WHERE id=$id");
    } else {
        $conn->query("UPDATE clothes SET name='$name', description='$desc', price='$price' WHERE id=$id");
    }
    header("Location: admin_dashboard.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Cloth</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2>Edit Cloth</h2>
        <form method="POST" enctype="multipart/form-data">
            <input class="form-control mb-2" name="name" value="<?= $item['name'] ?>" required>
            <textarea class="form-control mb-2" name="description"><?= $item['description'] ?></textarea>
            <input class="form-control mb-2" name="price" type="number" value="<?= $item['price'] ?>" required>
            <input class="form-control mb-2" name="image" type="file">
            <img src="uploads/<?= $item['image'] ?>" width="100"><br><br>
            <button class="btn btn-primary">Update</button>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back</a>
        </form>
    </div>
</body>
</html>
