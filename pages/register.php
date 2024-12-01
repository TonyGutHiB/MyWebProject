<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';

// Start the session to access logged-in user information
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    $alreadyLoggedIn = true;
} else {
    $alreadyLoggedIn = false;
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$alreadyLoggedIn) {
    // Retrieve form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validate input
    if (!empty($email) && !empty($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        try {
            // Check if the email is already registered
            $checkQuery = "SELECT COUNT(*) FROM User WHERE email = :email";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $emailExists = $stmt->fetchColumn();

            if ($emailExists) {
                $errorMessage = "An account with this email already exists.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert the user into the User table
                $insertQuery = "INSERT INTO User (email, password) VALUES (:email, :password)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $stmt->execute();

                // Success message
                $successMessage = "Registration successful! You can now <a href='login.php'>log in</a>.";
            }
        } catch (PDOException $e) {
            $errorMessage = "An error occurred while processing your registration. Please try again.";
        }
    } else {
        $errorMessage = "Please fill in all fields with valid data.";
    }
}
?>

<body>
<main>
    <section class="register-container">
        <div class="register-box">
            <?php if ($alreadyLoggedIn): ?>
                <!-- Display a message if the user is already logged in -->
                <h2>You are already logged in!</h2>
                <p>You will be redirected to the home in 5 seconds.</p>
                <meta http-equiv="refresh" content="5;url=../index.php">
            <?php else: ?>
                <!-- Display the registration form -->
                <h2>Register</h2>

                <!-- Display messages -->
                <?php if (!empty($successMessage)): ?>
                    <div class="success-message"><?= $successMessage ?></div>
                <?php endif; ?>
                <?php if (!empty($errorMessage)): ?>
                    <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
                <?php endif; ?>

                <form id="registerForm" action="register.php" method="post">
                    <div class="form-group">
                        <label for="registerEmail">Email:</label>
                        <input type="email" id="registerEmail" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label for="registerPassword">Password:</label>
                        <input type="password" id="registerPassword" name="password" placeholder="Create a password" required>
                    </div>
                    <div class="form-group">
                        <!-- Submit button for the registration form -->
                        <button type="submit" class="btn">Register</button>
                    </div>
                </form>
                <p>Already have an account? <a href="login.php" class="login-link">Login here</a></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
include '../includes/footer.php';
?>

<!-- Linking external JavaScript file -->
<script src="../assets/js/script.js"></script>
</body>
</html>
