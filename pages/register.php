<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';
?>

<body>
  <main>
    <section class="register-container">
      <div class="register-box">
        <h2>Register</h2>
        <!-- Registration form -->
        <form id="registerForm" action="#" method="post">
          <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
          </div>
          <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required>
          </div>
          <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Create a password" required>
          </div>
          <div class="form-group">
            <!-- Submit button for the registration form -->
            <button type="submit" class="btn">Register</button>
          </div>
        </form>
        <!-- Link to login page for users who already have an account -->
        <p>Already have an account? <a href="login.php" class="login-link">Login here</a></p>
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