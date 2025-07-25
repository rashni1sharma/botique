<?php include 'db.php';
$id = $_GET['id'];
$conn->query("DELETE FROM clothes WHERE id=$id");
header("Location: index.php");
?>
