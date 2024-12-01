// Array of background images for the hero section
const images = [
    'url(../picone.jpg)',
    'url(../pictwo.jpg)',
    'url(../picthree.jpg)',
    'url(../picfour.jpg)'
];

let currentIndex = 0;
const heroSection = document.querySelector('.hero');

// Function to change the background image of the hero section
function changeSlide(index) {
    currentIndex = index;

    if (currentIndex >= images.length) {
        currentIndex = 0; // Wrap around to the first image
    } else if (currentIndex < 0) {
        currentIndex = images.length - 1; // Wrap to the last image
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

// Auto-change slide every 5 seconds
setInterval(nextSlide, 5000);

// Initialize the hero section with the first background image
changeSlide(0);

// Preload the images for smoother transitions
const preloadImages = images.map(imageUrl => {
    const img = new Image();
    img.src = imageUrl.replace('url(', '').replace(')', '');
    return img;
});

// Set the active class on the current navigation link
document.addEventListener('DOMContentLoaded', () => {
    const navLinks = document.querySelectorAll('nav ul li a');
    const currentUrl = window.location.pathname.split('/').pop(); // Get current page

    navLinks.forEach(link => {
        if (link.getAttribute('href') === currentUrl) {
            link.classList.add('active');
        }
    });
});