let currentIndex = 0;
let intervalId;  // Interval ID for automatic slideshow

function showSlide(index) {
    const slides = document.getElementsByClassName('slide');
    const dots = document.getElementsByClassName('dot');

    // Hide all slides
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = 'none';
        dots[i].classList.remove('active');
    }

    // Show the current slide and set the active dot
    slides[index].style.display = 'block';
    dots[index].classList.add('active');
}

function nextSlide() {
    currentIndex++;
    if (currentIndex >= document.getElementsByClassName('slide').length) {
        currentIndex = 0;
    }
    showSlide(currentIndex);
}

// Initial display of the first slide
showSlide(currentIndex);

// Start the automatic slideshow (change slide every 3 seconds)
intervalId = setInterval(nextSlide, 3000);

// Function to set a specific slide when a dot is clicked
function setSlide(index) {
    clearInterval(intervalId);  // Stop the automatic slideshow
    currentIndex = index;
    showSlide(currentIndex);
    // Restart the automatic slideshow
    intervalId = setInterval(nextSlide, 3000);
}
