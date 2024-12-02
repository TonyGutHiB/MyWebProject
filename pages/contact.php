<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';

// Start the session to access the logged-in user information
session_start();

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check if the user is logged in
    if ($_SESSION['loggedin'] !== true) {
        $errorMessage = "You must be logged in to send a message.";
    } else {
        // Get user ID from the session
        $userID = $_SESSION['user_id'];

        // Retrieve form data
        $email = trim($_POST['email']);
        $message = trim($_POST['message']);

        // Validate input
        if (!empty($email) && !empty($message) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                // Insert the ticket into the Ticket table
                $sql = "INSERT INTO Ticket (userID, email, message) VALUES (:userID, :email, :message)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':userID', $userID, PDO::PARAM_INT);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':message', $message, PDO::PARAM_STR);
                $stmt->execute();

                // Success message
                $successMessage = "Message sent successfully!";
            } catch (PDOException $e) {
                $errorMessage = "An error occurred while sending your message. Please try again.";
            }
        } else {
            $errorMessage = "Please fill in all fields with valid data.";
        }
    }
}
?>

<body>
<!-- Contact Intro Section -->
<section class="contact-intro">
    <h2>Contact Us</h2>
    <p>If you have any questions or need assistance, feel free to reach out to us by filling out the form below.</p>
</section>

<!-- Contact Form Section -->
<section class="contact-container">
    <section class="contact-form">
        <!-- Display messages -->
        <div id="responseMessages">
            <!-- Success and Error messages will be loaded dynamically here -->
            <?php if (!empty($successMessage)): ?>
                <div class="success-message"><?= htmlspecialchars($successMessage) ?></div>
            <?php endif; ?>
            <?php if (!empty($errorMessage)): ?>
                <div class="error-message"><?= htmlspecialchars($errorMessage) ?></div>
            <?php endif; ?>
        </div>

        <!-- Check if user is logged in -->
        <?php if ($_SESSION['loggedin']): ?>
            <!-- Show the form if logged in -->
            <form id="contactForm" action="contact.php" method="post">
                <div class="form-group">
                    <label for="email">Your Email:</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>

                <div class="form-group">
                    <label for="message">Your Message:</label>
                    <textarea id="message" name="message" rows="5" placeholder="Write your message here" required></textarea>
                </div>

                <div class="form-group" style="margin-top: 30px;">
                    <button type="submit" class="btn">Send Message</button>
                </div>
            </form>
        <?php else: ?>
            <!-- Show a login prompt if not logged in -->
            <div class="login-prompt">
                <p>You must be logged in to send a message<br><br>
                    <a href="login.php" class="text-link">Log In</a> or <a href="register.php" class="text-link">Register</a></p>
            </div>
        <?php endif; ?>
    </section>
</section>

<?php
include '../includes/footer.php';
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#contactForm').submit(function(e) {
            e.preventDefault(); // Prevent the form from submitting normally

            const formData = $(this).serialize(); // Serialize form data

            // Disable the submit button to prevent multiple submissions
            $('button[type="submit"]').attr('disabled', true).text('Sending...');

            // Send the form data via AJAX
            $.ajax({
                url: 'contact.php',
                type: 'POST',
                data: formData,
                success: function(response) {
                    // Dynamically update success or error message
                    $('#responseMessages').html($(response).find('#responseMessages').html());

                    // Re-enable the submit button after response
                    $('button[type="submit"]').attr('disabled', false).text('Send Message');

                    // Optionally, clear the form fields after successful submission
                    $('#contactForm')[0].reset();
                },
                error: function() {
                    // In case of error, show a generic error message
                    $('#responseMessages').html('<div class="error-message">An error occurred. Please try again later.</div>');
                    $('button[type="submit"]').attr('disabled', false).text('Send Message');
                }
            });
        });
    });
</script>

</body>
</html>
