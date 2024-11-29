<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';
?>

<body>
  <main>
    <section class="login-container">
      <div class="login-box">
        <h2>Login</h2>
        <!-- Login form for user authentication -->
        <form id="loginForm" action="#" method="post">
          <div class="form-group">
            <label for="loginEmail">Email:</label>
            <input type="email" id="loginEmail" name="email" placeholder="Enter your email" required>
          </div>
          <div class="form-group">
            <label for="loginPassword">Password:</label>
            <input type="password" id="loginPassword" name="password" placeholder="Enter your password" required>
          </div>
          <div class="form-group">
            <!-- Submit button for the login form -->
            <button type="submit" class="btn">Login</button>
          </div>
        </form>
        
        <!-- Links for registration and password recovery -->
        <p>Don't have an account? <a href="register.php" class="register-link">Register here</a></p>
        <p><a href="forgot_password.php" class="forgot-password-link">Forgot your password?</a></p>
      </div>
    </section>
  </main>

  <footer>
    <!-- Footer section with copyright information -->
    <p>&copy; 2024 StyleHub. All Rights Reserved.</p>
  </footer>

  <!-- Linking external JavaScript file -->
  <script src="../assets/js/script.js"></script>
</body>
</html>