<?php
session_start();
$conn = new mysqli("localhost", "root", "", "botique");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // Basic hashing (use password_hash() for production)

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user found
    if ($result->num_rows === 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid Credentials');</script>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Boutique</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #8e2de2, #4a00e0);
              background-image: url("images/bgg.jpg");
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 400px;
        }

        .login-box h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #4a00e0;
        }

        .login-box input[type="text"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .login-box button {
            width: 100%;
            padding: 12px;
            background: #4a00e0;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-box button:hover {
            background: #3700b3;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #888;
        }
    </style>
</head>
<body>
    <form method="POST" class="login-box">
        <h2>Admin Login</h2>
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit" name="login">Login</button>
        <div class="footer">
            &copy; 2025 Boutique Admin Panel
        </div>
    </form>
</body>
</html>
