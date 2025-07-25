<?php
// delete_cloth.php
session_start();

// Show all errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "botique";

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check DB connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if admin is logged in
session_start();

// After verifying username and password:
$_SESSION['admin_loggedin'] = true;
$_SESSION['admin_username'] = 'admin'; // Set admin username
if (!isset($_SESSION['admin_loggedin']) || !$_SESSION['admin_loggedin']) {
    header("Location: admin_login.php");
    exit();
}

// Check if ID is passed and numeric
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID provided.");
}

$collection_id = intval($_GET['id']);
$success = true;
$message = "";

echo "🧾 ID to delete: $collection_id<br>";

// 1. Fetch item for image reference
$item = getClothingItem($collection_id);
if (!$item) {
    die("❌ Clothing item not found in database.");
}

echo "📷 Image to delete: " . $item['image'] . "<br>";

// 2. Delete image from folder
if (!empty($item['image'])) {
    $image_path = "images/" . $item['image'];
    echo "🛠️ Image path: $image_path<br>";

    if (file_exists($image_path)) {
        if (unlink($image_path)) {
            echo "✅ Image deleted successfully.<br>";
        } else {
            echo "⚠️ Failed to delete image file.<br>";
            $success = false;
        }
    } else {
        echo "⚠️ Image file does not exist.<br>";
    }
} else {
    echo "⚠️ No image filename in database.<br>";
}

// 3. Delete record from database
if (deleteFromDatabase($collection_id)) {
    echo "✅ Record deleted from database.<br>";
    $message .= "Clothing item deleted successfully.";
} else {
    echo "❌ Failed to delete from database.<br>";
    $message .= "Error deleting from database.";
    $success = false;
}

// Final redirect (can be disabled for debug)
header("Location: index.php?msg=" . urlencode($message) . "&msg_class=" . ($success ? "alert-success" : "alert-warning"));
exit();

// --------------------
// Helper Functions
// --------------------

function getClothingItem($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT image FROM collection WHERE collection_id = ?");
    if (!$stmt) {
        echo "❌ SQL prepare failed (getClothingItem).<br>";
        return false;
    }

    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        echo "❌ SQL execute failed (getClothingItem).<br>";
        $stmt->close();
        return false;
    }

    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();

    return $item ?: false;
}

function deleteFromDatabase($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM collection WHERE collection_id = ?");
    if (!$stmt) {
        echo "❌ SQL prepare failed (deleteFromDatabase).<br>";
        return false;
    }

    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->close();

    return $result;
}
?>
