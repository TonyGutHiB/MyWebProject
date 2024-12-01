<!DOCTYPE html>
<html lang="en">

<?php
require_once '../includes/db.php';
include '../includes/header.php';
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
        <form id="contactForm">
            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="5" placeholder="Write your message here"
                          required></textarea>
            </div>

            <div class="form-group" style="margin-top: 30px;">
                <button type="submit" class="btn">Send Message</button>
            </div>
        </form>

        <div id="successMessage">
            Message sent successfully!
        </div>
    </section>
</section>

<?php
include '../includes/footer.php';
?>

<script>
    // JavaScript to handle form submission
    document.getElementById('contactForm').addEventListener('submit', function (event) {
        event.preventDefault();
        document.getElementById('successMessage').style.display = 'block';
    });
</script>
</body>
</html>