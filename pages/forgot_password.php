<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';
?>

<body>
<section class="forgot-password-body">
    <div class="forgot-password-box">
        <!-- Title of the forgot password section -->
        <h2>Forgot Password</h2>
        <p>Enter your email to reset your password.</p>
        <form action="#" method="post">
            <!-- Input field for the user's email -->
            <label for="email"></label><input type="email" id="email" name="email" required placeholder="Your email">
            <!-- Button to submit the form -->
            <button type="submit">Reset Password</button>
            <!-- Link to navigate back to the login page -->
            <a href="login.php" class="button-style">Back to login</a>
        </form>
    </div>
</section>
<?php
include '../includes/footer.php';
?>
</body>
</html>