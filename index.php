<!DOCTYPE html>
<html lang="en">

<?php
require_once 'includes/db.php';
include 'includes/header.php';
?>

<body>
    <!-- Hero Section with Slideshow -->
    <section class="hero">
        <div class="hero-content">
            <h2>Welcome to the Future of Fashion</h2>
            <p>We create a seamless platform for buying and selling fashion items and accessories with ease and style.</p>
            <a href="pages/services.php" class="btn">Discover Our Services</a>
        </div>

        <!-- Slideshow navigation controls -->
        <br>
        <div class="slideshow-controls">
            <span class="prev-slide" onclick="prevSlide()">&#10094;</span>
            <span class="next-slide" onclick="nextSlide()">&#10095;</span>
        </div>
    </section>

    <section class="features">
        <h2>Why Choose Us?</h2>
        <div class="features-grid">
            <div class="feature">
                <h3>Diverse Selection</h3>
                <p>Access a wide variety of unique, trendy, and hard-to-find clothing and accessories all in one place, catering to every style.</p>
            </div>
            <div class="feature">
                <h3>Simple & Secure</h3>
                <p>Enjoy a seamless shopping experience with easy navigation, personalized recommendations, and secure payment options.</p>
            </div>
            <div class="feature">
                <h3>Sustainable Fashion</h3>
                <p>Support eco-friendly and ethical fashion by buying second-hand or from designers using sustainable practices.</p>
            </div>
        </div>
    </section>

    <?php
    include 'includes/footer.php';
    ?>

    <script>
        // Array of background images
        const images = [
            'url(assets/images/picone.jpg)',
            'url(assets/images/pictwo.jpg)',
            'url(assets/images/picthree.jpg)',
            'url(assets/images/picfour.jpg)'
        ];

        // Preload images
        const preloadImages = images.map(imageUrl => {
            const img = new Image();
            img.src = imageUrl.replace('url(', '').replace(')', '');
            return img;
        });

        let currentIndex = 0;
        const heroSection = document.querySelector('.hero');

        // Function to change the slide
        function changeSlide(index) {
            currentIndex = index;
            if (currentIndex >= images.length) {
                currentIndex = 0;
            } else if (currentIndex < 0) {
                currentIndex = images.length - 1;
            }
            heroSection.style.backgroundImage = images[currentIndex];
        }

        // Show the next slide
        function nextSlide() {
            changeSlide(currentIndex + 1);
        }

        // Show the previous slide
        function prevSlide() {
            changeSlide(currentIndex - 1);
        }

        // Auto-change slide every 7 seconds (7000 milliseconds)
        setInterval(nextSlide, 7000);

        // Initialize with the first background image
        changeSlide(0);
    </script>
</body>
</html>