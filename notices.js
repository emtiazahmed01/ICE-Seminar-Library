let currentSlideIndex = 0;
const slides = document.querySelectorAll(".slide");
const dots = document.querySelectorAll(".dot");

// ✅ If PHP sets an initial slide index, use it
if (typeof initialSlide !== "undefined") {
    currentSlideIndex = initialSlide;
}

function showSlide(index) {
    slides.forEach(slide => slide.classList.remove("active"));
    dots.forEach(dot => dot.classList.remove("active"));

    currentSlideIndex = (index + slides.length) % slides.length;

    slides[currentSlideIndex].classList.add("active");
    dots[currentSlideIndex].classList.add("active");
}

// Auto-play (5s interval)
setInterval(() => showSlide(currentSlideIndex + 1), 5000);

// Prev / Next buttons
function changeSlide(n) {
    showSlide(currentSlideIndex + n);
}

// Dots navigation + sidebar links
function currentSlide(n) {
    showSlide(n);
}


const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('nav-links');
const overlay = document.getElementById('overlay');
const dropdownBtns = document.querySelectorAll('.dropdown-btn');

// Toggle nav links and overlay
hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('active'); // Hamburger animation
    navLinks.classList.toggle('active');  // Show nav links
    overlay.classList.toggle('active');   // Show overlay
});

// Hide nav when clicking overlay
overlay.addEventListener('click', () => {
    hamburger.classList.remove('active');
    navLinks.classList.remove('active');
    overlay.classList.remove('active');
});

// Toggle dropdown menus inside mobile nav
dropdownBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        btn.parentElement.classList.toggle('active');
    });
});



// Sidebar auto-scroll
const noticeList = document.getElementById("notice-list");
function autoScroll() {
    if (noticeList && noticeList.children.length > 0) {
        const first = noticeList.children[0];
        first.style.marginTop = "-30px";
        setTimeout(() => {
            first.style.marginTop = "0";
            noticeList.appendChild(first);
        }, 600);
    }
}
setInterval(autoScroll, 5000);

// 🔹 Initialize slideshow on page load
document.addEventListener("DOMContentLoaded", () => {
    showSlide(currentSlideIndex);
});
