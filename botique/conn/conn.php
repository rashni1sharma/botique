<?php
$servername = "localhost"; // or 127.0.0.1
$username = "root";        // default XAMPP/WAMP username
$password = "";            // default password is empty in XAMPP
$dbname = "botique"; // replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
