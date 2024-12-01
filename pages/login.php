<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form fields are not empty
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        // Get the user details from the database
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM User WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {

            // Check if the user is also a seller
            $sql = "SELECT * FROM Seller WHERE userID = :userID";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userID', $user['userID']);
            $stmt->execute();
            $seller = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($seller) {
                $_SESSION['is_seller'] = true;
            }

            // Set the session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['userID'];
            $_SESSION['email'] = $user['email'];

            // Check if the user is an admin (userID is 1)
            if ((int)$_SESSION['user_id'] === 1) {
                $_SESSION['is_admin'] = true;
            }

            // Redirect to home
            header('Location: ../index.php');
        } else {
            $error_message = 'Invalid email or password.';
        }
    } else {
        $error_message = 'Please fill in all the required fields.';
    }
}

?>

<body>
<main>
    <section class="login-container">
        <div class="login-box">
            <h2>Login</h2>
            <!-- Login form for user authentication -->
            <form id="loginForm" action="login.php" method="post">
                <div class="form-group">
                    <label for="loginEmail">Email:</label>
                    <input type="email" id="loginEmail" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group">
                    <label for="loginPassword">Password:</label>
                    <input type="password" id="loginPassword" name="password" placeholder="Enter your password"
                           required>
                </div>
                <div class="form-group">
                    <!-- Submit button for the login form -->
                    <button type="submit" class="btn">Login</button>
                </div>
            </form>

            <?php if (!empty($error_message)): ?>
                <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
            <?php endif; ?>

            <!-- Links for registration and password recovery -->
            <p>Don't have an account? <a href="register.php" class="register-link">Register here</a></p>
            <p><a href="forgot_password.php" class="forgot-password-link">Forgot your password?</a></p>
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