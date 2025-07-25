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
    'database' => 'botique' // Ensure this is the correct DB name
];

// Establish database connection
try {
    $conn = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $user_id = intval($_GET['delete_user']);
    
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "User deleted successfully";
            $_SESSION['message_type'] = "success";
        } else {
            throw new Exception($stmt->error);
        }
        $stmt->close();
    } catch (Exception $e) {
        $_SESSION['message'] = "Error deleting user: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
    }
    
    header("Location: users.php");
    exit();
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows == 0) {
    echo "<tr><td colspan='6'>No users found (0 rows in DB)</td></tr>";
}
$users = $result->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Users</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #ff6b9a;
            --secondary-color: #ffd6e5;
            --dark-pink: #e91e63;
            --light-pink: #fff5f8;
        }
        body {
            background-color: var(--light-pink);
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="page-header">
        <h2>Manage Users</h2>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)): ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['user_id']; ?></td>
                        <td><?php echo $user['user_name']; ?></td>
                        <td><?php echo $user['email']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['user_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                        <td>
                            <a href="?delete_user=<?php echo $user['user_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No users found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php



