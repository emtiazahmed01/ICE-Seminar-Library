// ================= HERO SLIDER =================
let currentSlide = 0;
const slides = [
  { src: "./images/slide1.jpg", caption: "Welcome to ICE Seminar Library" },
  { src: "./images/slide2.jpg", caption: "Explore our collection of books" },
  { src: "./images/slide3.jpg", caption: "Join our upcoming events" }
];

const sliderImage = document.getElementById("slider-image");
const sliderCaption = document.getElementById("slider-caption");
const slideNumber = document.getElementById("slide-number");

function showSlide(index) {
  currentSlide = (index + slides.length) % slides.length;
  sliderImage.src = slides[currentSlide].src;
  sliderCaption.textContent = slides[currentSlide].caption;
  slideNumber.textContent = (currentSlide + 1) + " / " + slides.length;
}

function nextSlide() {
  showSlide(currentSlide + 1);
}

function prevSlide() {
  showSlide(currentSlide - 1);
}

// Auto-play every 5s
setInterval(nextSlide, 5000);
showSlide(0);

// ================= HAMBURGER MENU =================
const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('nav-links');
const dropdownBtns = document.querySelectorAll('.dropdown-btn');

hamburger.addEventListener('click', () => {
    navLinks.classList.toggle('active'); // Show/hide nav links
});

// Toggle dropdown menus on mobile
dropdownBtns.forEach(btn => {
    btn.addEventListener('click', () => {
        btn.parentElement.classList.toggle('active');
    });
});

// ================= SCROLL REVEAL =================
const reveals = document.querySelectorAll(".reveal");
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add("active");
    }
  });
}, { threshold: 0.1 });

reveals.forEach((el) => observer.observe(el));
