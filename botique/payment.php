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


if (isset($_POST['submit_rent'])) {
    $id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $delivery_date = $_POST['delivery_date'];

    $sql = "INSERT INTO clothes (id, full_name, phone, address, delivery_date)
            VALUES ('$id', '$full_name', '$phone', '$address', '$delivery_date')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Rent request submitted!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rent Dress</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  
    <!-- Modal Form -->
    <div class="modal fade" id="rentFormModal" tabindex="-1" aria-labelledby="rentFormModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" action="" class="modal-content p-3">
          <div class="modal-header">
            <h5 class="modal-title" id="rentFormModalLabel">Rent This Dress</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="dress_id" value="123"> <!-- Can be dynamic -->
            <div class="mb-3">
              <label class="form-label">Full Name</label>
              <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Phone</label>
              <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Address</label>
              <input type="text" name="address" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Delivery Date</label>
              <input type="date" name="delivery_date" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="submit_rent" class="btn btn-primary w-100">Submit</button>
          </div>
        </form>
      </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
