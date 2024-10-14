<?php
// login.php
include '../db_connect.php';
session_start(); // Start a session

$errors = [];
$usernameOrEmail = $password = '';
$borderClass = ['usernameOrEmail' => '', 'password' => ''];

if (isset($_COOKIE['usernameOrEmail'])) {
    $usernameOrEmail = $_COOKIE['usernameOrEmail'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usernameOrEmail = trim($_POST['usernameOrEmail']);
    $password = $_POST['password'];
    $rememberMe = isset($_POST['rememberMe']);

    // Validation
    if (empty($usernameOrEmail)) {
        $errors['usernameOrEmail'] = "Please enter your username or email.";
        $borderClass['usernameOrEmail'] = 'error';
    } elseif (!filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL) && strlen($usernameOrEmail) < 3) {
        $errors['usernameOrEmail'] = "Please enter a valid username or email.";
        $borderClass['usernameOrEmail'] = 'error';
    }

    if (empty($password)) {
        $errors['password'] = "Please enter your password.";
        $borderClass['password'] = 'error';
    }

    // Check credentials if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                $_SESSION['username'] = $usernameOrEmail;

                // Set cookie for "Remember Me"
                if ($rememberMe) {
                    setcookie('usernameOrEmail', $usernameOrEmail, time() + (86400 * 30), "/"); // 30 days
                } else {
                    setcookie('usernameOrEmail', '', time() - 3600, "/"); // Delete cookie
                }

                header("Location: index.php");
                exit();
            } else {
                $errors['password'] = "Invalid password.";
                $borderClass['password'] = 'error';
            }
        } else {
            $errors['usernameOrEmail'] = "No user found with that username or email.";
            $borderClass['usernameOrEmail'] = 'error';
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="logStyle.css">
    <style>
        .error-message {
            color: red;
            margin-top: 5px;
        }
        .remember-me {
            margin: 10px 0;
        }
        .forgot-password, .register-account {
            margin-top: 15px;
        }
        .logo {
            max-width: 100px; /* Adjust size as needed */
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="../assets/images/Logo.png" alt="Logo" class="logo"> <!-- Add your logo here -->
        <h2>Login</h2>
        
        <?php if (!empty($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li class="error-message"><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="login.php" method="post">
            <label for="usernameOrEmail">Username or Email:</label>
            <input type="text" name="usernameOrEmail" value="<?php echo htmlspecialchars($usernameOrEmail); ?>" class="<?php echo $borderClass['usernameOrEmail']; ?>" required>
            
            <label for="password">Password:</label>
            <input type="password" name="password" class="<?php echo $borderClass['password']; ?>" required>
            
            <div class="remember-me">
                <input type="checkbox" name="rememberMe" id="rememberMe" <?php if (isset($_COOKIE['usernameOrEmail'])) echo 'checked'; ?>>
                <label for="rememberMe">Remember Me</label>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="forgot-password">
            <a href="forgot_password.php">Forgot Password?</a>
        </div>

        <div class="register-account">
            <p>Don't have an account? <a href="../Register/register.php">Register here</a>.</p>
        </div>
    </div>
</body>
</html>
