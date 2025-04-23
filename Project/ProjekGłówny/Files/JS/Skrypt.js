document.addEventListener("DOMContentLoaded", function() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
    link.addEventListener('click', function(event) {
    event.preventDefault(); 
    const targetId = this.getAttribute('href').substring(1); 
    const targetSection = document.getElementById(targetId);
    const offsetTop = targetSection.offsetTop; 
    const headerHeight = document.querySelector("header").offsetHeight;
    const offsetY = offsetTop - headerHeight;
    window.scrollTo({
    top: offsetY,
    behavior: 'smooth'
    });
    navLinks.forEach(link => link.classList.remove('active'));
    this.classList.add('active');
    });
    });
    });