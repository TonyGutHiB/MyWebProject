<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';

$successMessage = '';
$errorMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']); // Get the email from the form

    if (!empty($email)) {
        try {
            // Check if the user exists
            $checkQuery = "SELECT userID FROM User WHERE email = :email";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Reset the user's password
                $newPassword = password_hash('password', PASSWORD_DEFAULT); // Hash the default password
                $updateQuery = "UPDATE User SET password = :password WHERE email = :email";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bindParam(':password', $newPassword, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->execute();

                $successMessage = "Password has been reset to 'password'. Please log in with the new password.";
            } else {
                $errorMessage = "No account found with this email.";
            }
        } catch (PDOException $e) {
            $errorMessage = "An error occurred while resetting the password. Please try again.";
        }
    } else {
        $errorMessage = "Please enter a valid email address.";
    }
}
?>

<body>
<section class="forgot-password-body">
    <div class="forgot-password-box">
        <!-- Title of the forgot password section -->
        <h2>Forgot Password</h2>
        <p>Enter your email to reset your password.</p>

        <!-- Display success or error messages -->
        <?php if (!empty($successMessage)): ?>
            <div class="success-message"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
        <?php endif; ?>

        <!-- Form to reset the password -->
        <form action="forgot_password.php" method="post">
            <label for="email"></label><input type="email" id="email" name="email" required placeholder="Your email">
            <button type="submit">Reset Password</button>
            <a href="login.php" class="button-style">Back to login</a>
        </form>
    </div>
</section>
<?php
include '../includes/footer.php';
?>
</body>
</html>
