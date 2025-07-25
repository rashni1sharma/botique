<?php
session_start(); 
include('./conn/conn.php');

$logEmail = $logPassword = "";
$logPasswordErr = "";

// On login form submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $logEmail = trim($_POST["logEmail"]);
    $logPassword = trim($_POST["logPassword"]);

    if (empty($logEmail) || empty($logPassword)) {
        $logPasswordErr = "Please fill in both fields.";
    } else {
        $stmt = $conn->prepare("SELECT user_id, user_name, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $logEmail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $user_name, $hashedPassword);
            $stmt->fetch();

            if (password_verify($logPassword, $hashedPassword)) {
                $_SESSION["user_id"] = $user_id;
                $_SESSION["user_name"] = $user_name;
                $_SESSION["email"] = $logEmail;

                header("Location: dashboard.php");
                exit();
            } else {
                $logPasswordErr = "Incorrect password.";
            }
        } else {
            $logPasswordErr = "Wrong email or password.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url("images/bgg.jpg");
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        form {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            padding: 30px 25px;
            border-radius: 10px;
            width: 320px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
            display: flex;
            flex-direction: column;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }

        label {
            margin-bottom: 5px;
            color: #fff;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            outline: none;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            width: 100%;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .error {
            color: #ffcccc;
            background: #b30000;
            padding: 5px 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-size: 14px;
        }

        p {
            text-align: center;
            margin-top: 15px;
            color: #fff;
        }

        a {
            color: #00ffff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

        <h2>User Login</h2>

        <?php if (!empty($logPasswordErr)) : ?>
            <div class="error"><?php echo htmlspecialchars($logPasswordErr); ?></div>
        <?php endif; ?>

        <label for="logEmail">Email:</label>
        <input type="text" id="logEmail" name="logEmail" value="<?php echo htmlspecialchars($logEmail); ?>" required>

        <label for="logPassword">Password:</label>
        <input type="password" id="logPassword" name="logPassword" required>

        <input class="btn" type="submit" name="login" value="Login">

        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>
