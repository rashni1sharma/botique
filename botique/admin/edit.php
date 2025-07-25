<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "botique";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['admin_loggedin'])) {
    header("Location: edit.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid cloth ID");
}
$cloth_id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM collection WHERE collection_id = ?");
$stmt->bind_param("i", $cloth_id);
$stmt->execute();
$result = $stmt->get_result();
$cloth = $result->fetch_assoc();
$stmt->close();

if (!$cloth) {
    die("Cloth not found.");
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $image = $cloth['image'];

    // Server-side validation
    if (empty($name)) $errors[] = "Cloth name is required.";
    if (empty($description)) $errors[] = "Description is required.";
    if (!is_numeric($price) || $price <= 0) $errors[] = "Price must be a positive number.";

    if (!empty($_FILES['image']['name'])) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $errors[] = "Only JPG, PNG, and WEBP images are allowed.";
        }
    }

    if (empty($errors)) {
        if (!empty($_FILES['image']['name'])) {
            $targetDir = "images/";
            $newImage = basename($_FILES["image"]["name"]);
            $targetFile = $targetDir . $newImage;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                if (!empty($cloth['image']) && file_exists("images/" . $cloth['image'])) {
                    unlink("images/" . $cloth['image']);
                }
                $image = $newImage;
            }
        }

        $stmt = $conn->prepare("UPDATE collection SET name = ?, description = ?, price = ?, image = ? WHERE collection_id = ?");
        $stmt->bind_param("ssdsi", $name, $description, $price, $image, $cloth_id);

        if ($stmt->execute()) {
            header("Location: index.php?msg=" . urlencode("Cloth updated successfully!") . "&msg_class=alert-success");
            exit();
        } else {
            $errors[] = "Error updating cloth: " . $stmt->error;
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Clothing Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
    function validateForm() {
        const name = document.forms["clothForm"]["name"].value.trim();
        const desc = document.forms["clothForm"]["description"].value.trim();
        const price = document.forms["clothForm"]["price"].value;

        if (name === "" || desc === "" || price === "") {
            alert("All fields are required.");
            return false;
        }
        if (isNaN(price) || price <= 0) {
            alert("Price must be a positive number.");
            return false;
        }
        return true;
    }
    </script>
</head>
<body class="container mt-4">
    <h2>Edit Clothing Item</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form name="clothForm" method="POST" enctype="multipart/form-data" onsubmit="return validateForm();">
        <div class="mb-3">
            <label class="form-label">Cloth Name</label>
            <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($cloth['name']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($cloth['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" name="price" class="form-control" step="0.01" required value="<?= htmlspecialchars($cloth['price']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <?php if (!empty($cloth['image'])): ?>
                <img src="images/<?= htmlspecialchars($cloth['image']) ?>" width="150"><br>
            <?php else: ?>
                <p>No image uploaded.</p>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label class="form-label">Change Image (optional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Update Cloth</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
