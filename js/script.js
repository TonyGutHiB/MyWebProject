// Handle form submission for login and register
document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Login form submitted successfully!');
});

document.getElementById('registerForm')?.addEventListener('submit', function(event) {
    event.preventDefault();
    alert('Registration form submitted successfully!');
});

const images = [
    'url(../picone.jpg)',
    'url(../pictwo.jpg)',
    'url(../picthree.jpg)',
    'url(../picfour.jpg)'
];

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

// Auto-change slide every 20 seconds
setInterval(() => {
    nextSlide();
}, 5000);

// Initialize with the first background image
changeSlide(0);
// Preload the images 
const preloadImages = images.map(imageUrl => {
    const img = new Image();
    img.src = imageUrl.replace('url(', '').replace(')', '');
    return img;
});
