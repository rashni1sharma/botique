<?php
session_start();

// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "botique";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Input cleaner
function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$name = $email = $password = "";
$emailErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["register"])) {
    $name = test_input($_POST["name"]);
    $email = test_input($_POST["email"]);
    $password = test_input($_POST["password"]);
    $confirmPassword = test_input($_POST["confirmPassword"]);

    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } elseif ($result->num_rows > 0) {
        $emailErr = "Email is already taken";
    } elseif ($password !== $confirmPassword) {
        $emailErr = "Passwords do not match";
    } elseif (empty($name) || empty($email) || empty($password)) {
        $emailErr = "All fields are required";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertQuery = "INSERT INTO users (user_name, email, password) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("sss", $name, $email, $hashedPassword);

        if ($insertStmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $emailErr = "Registration failed: " . $conn->error;
        }
        $insertStmt->close();
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form { width: 300px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .input-group { margin-bottom: 15px; }
        .input-group label { display: block; margin-bottom: 5px; }
        .input-group input {
            width: 100%; padding: 8px; border: 1px solid #ccc;
            border-radius: 4px;
        }
        .error { color: red; font-size: 13px; }
        .btn {
            width: 100%; padding: 10px;
            background-color: #007bff; color: white;
            border: none; border-radius: 4px;
            font-weight: bold; cursor: pointer;
        }
        .btn:hover { background-color: #0056b3; }
        p { text-align: center; margin-top: 15px; }
        a { color: #007bff; }
    </style>

    <script>
        function validateForm() {
            var name = document.getElementById("name").value.trim();
            var email = document.getElementById("email").value.trim();
            var password = document.getElementById("password").value;
            var confirmPassword = document.getElementById("confirmPassword").value;

            document.getElementById("nameErr").innerText = "";
            document.getElementById("emailErr").innerText = "";
            document.getElementById("passwordErr").innerText = "";
            document.getElementById("confirmPasswordErr").innerText = "";

            let isValid = true;

            if (name === "") {
                document.getElementById("nameErr").innerText = "Name is required";
                isValid = false;
            } else if (!/^[a-zA-Z\s]+$/.test(name)) {
                document.getElementById("nameErr").innerText = "Enter a valid name";
                isValid = false;
            }

            if (email === "") {
                document.getElementById("emailErr").innerText = "Email is required";
                isValid = false;
            } else if (!/^[a-zA-Z][a-zA-Z0-9._%+-]*@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(email)) {
                document.getElementById("emailErr").innerText = "Invalid email format";
                isValid = false;
            }

            if (password === "") {
                document.getElementById("passwordErr").innerText = "Password is required";
                isValid = false;
            } else if (!/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&^_-])[A-Za-z\d@$!%*#?&^_-]{8,}$/.test(password)) {
                document.getElementById("passwordErr").innerText = "Min 8 chars, 1 upper, 1 lower, 1 number, 1 special char";
                isValid = false;
            }

            if (confirmPassword === "") {
                document.getElementById("confirmPasswordErr").innerText = "Confirm your password";
                isValid = false;
            } else if (password !== confirmPassword) {
                document.getElementById("confirmPasswordErr").innerText = "Passwords do not match";
                isValid = false;
            }

            return isValid;
        }
    </script>
</head>
<body>
    <div class="container">
        <form method="post" action="" onsubmit="return validateForm()">
            <h2>Sign Up</h2>
            <div class="input-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($name); ?>">
                <div class="error" id="nameErr"></div>
            </div>

            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
                <div class="error" id="emailErr"><?php echo $emailErr; ?></div>
            </div>

            <div class="input-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
                <div class="error" id="passwordErr"></div>
            </div>

            <div class="input-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" name="confirmPassword" id="confirmPassword">
                <div class="error" id="confirmPasswordErr"></div>
            </div>

            <button type="submit" class="btn" name="register">Sign Up</button>
            <p>Already have an account? <a href="login.php">Log In</a></p>
        </form>
    </div>
</body>
</html>
