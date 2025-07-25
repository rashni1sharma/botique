<?php
session_start();
include('./conn/conn.php');

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$clothes_id = intval($_GET['id']);

// Get clothing item details
$clothes_query = "SELECT * FROM clothes WHERE clothes_id = $clothes_id";
$clothes_result = mysqli_query($conn, $clothes_query);
$clothes = mysqli_fetch_assoc($clothes_result);

if (!$clothes) {
    die("Item not found");
}

// Calculate return date (7 days from now)
$rental_date = date('Y-m-d H:i:s');
$return_date = date('Y-m-d H:i:s', strtotime('+7 days'));

// Insert rental record
$insert_query = "INSERT INTO rentals (user_id, clothes_id, rental_date, return_date, amount, status)
                VALUES ($user_id, $clothes_id, '$rental_date', '$return_date', {$clothes['price']}, 'active')";

if (mysqli_query($conn, $insert_query)) {
    header("Location: user_panel.php?page=rentals");
} else {
    die("Rental failed: " . mysqli_error($conn));
}