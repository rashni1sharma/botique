<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration
$dbConfig = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'botique'
];

// Connect to database
$conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user ID from URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['message'] = "Invalid user ID.";
    $_SESSION['message_type'] = "danger";
    header("Location: users.php");
    exit();
}

$user_id = intval($_GET['id']);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Basic validation
    if (empty($username) || empty($email)) {
        $_SESSION['message'] = "All fields are required.";
        $_SESSION['message_type'] = "danger";
    } else {
        $stmt = $conn->prepare("UPDATE users SET user_name = ?, email = ? WHERE user_id = ?");
        $stmt->bind_param("ssi", $username, $email, $user_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "User updated successfully!";
            $_SESSION['message_type'] = "success";
            header("Location: users.php");
            exit();
        } else {
            $_SESSION['message'] = "Error updating user: " . $stmt->error;
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
    }
}

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['message'] = "User not found.";
    $_SESSION['message_type'] = "danger";
    header("Location: users.php");
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit User</h2>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['user_name']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="users.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
