// Handle form submission for login with validation
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    // Email validation regex pattern
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        alert('Please enter a valid email.');
        return;
    }

    if (password.length < 8) {
        alert('Password must be at least 8 characters long.');
        return;
    }

    alert('Login form submitted successfully!');
});

// Handle form submission for registration with validation
document.getElementById('registerForm')?.addEventListener('submit', function(event) {
    event.preventDefault();

    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        alert('Please enter a valid email.');
        return;
    }

    // Password validation: min length 8 characters
    if (password.length < 8) {
        alert('Password must be at least 8 characters long.');
        return;
    }

    if (password !== confirmPassword) {
        alert('Passwords do not match.');
        return;
    }

    alert('Registration form submitted successfully!');
});

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