<?php
session_start();

// Database configuration
$dbConfig = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'botique'
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
        // Delete user
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

// Get all users
$users = [];
$sql = "SELECT user_id, user_name, email, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result) {
    $users = $result->fetch_all(MYSQLI_ASSOC);
    $result->free();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Boutique Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 1200px;
            margin: 30px auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(233, 30, 99, 0.1);
            padding: 30px;
        }
        
        .page-header {
            color: var(--dark-pink);
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }
        
        .page-header h2 {
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .page-header::after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background: var(--primary-color);
            margin: 15px auto;
            border-radius: 3px;
        }
        
        .user-card {
            border-radius: 10px;
            border: 1px solid #f0c0d2;
            padding: 15px;
            margin-bottom: 20px;
            transition: all 0.3s;
        }
        
        .user-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(233, 30, 99, 0.1);
        }
        
        .user-name {
            font-weight: 600;
            color: var(--dark-pink);
            margin-bottom: 5px;
        }
        
        .user-email {
            color: #666;
            font-size: 0.9rem;
        }
        
        .user-join-date {
            color: #888;
            font-size: 0.8rem;
            font-style: italic;
        }
        
        .action-buttons .btn {
            margin-right: 5px;
            border-radius: 8px;
            padding: 5px 10px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<div class="container">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['message_type']) ?> alert-dismissible fade show">
            <?= htmlspecialchars($_SESSION['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
    <?php endif; ?>
    
    <div class="page-header">
        <h2><i class="fas fa-users me-2"></i> Manage Users</h2>
    </div>
    
    <div class="row">
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <div class="col-md-6">
                    <div class="user-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="user-name">
                                    <?= htmlspecialchars($user['user_name']) ?>
                                </h5>
                                <div class="user-email">
                                    <i class="fas fa-envelope me-1"></i>
                                    <?= htmlspecialchars($user['email']) ?>
                                </div>
                                <div class="user-join-date">
                                    Joined: <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <a href="edit_user.php?id=<?= $user['user_id'] ?>" 
                                   class="btn btn-success btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="users.php?delete_user=<?= $user['user_id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-user-slash fa-3x mb-3" style="color: var(--secondary-color);"></i>
                <h4>No Users Found</h4>
                <p>There are currently no registered users in the system.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>