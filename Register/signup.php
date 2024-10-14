<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form action="signup.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button type="submit">Sign Up</button>

            <p class="or">
                ----------or--------
              </p>
              <div class="icons">
                <i class="fab fa-google"></i>
                <i class="fab fa-facebook"></i>
              </div>
              <div class="links">
                <p>Already have an account? </p>
                <button id="signUpButton">Sign In</button>
              </div>
            </div>
        </form>
    </div>
</body>
</html>
