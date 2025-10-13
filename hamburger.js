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

