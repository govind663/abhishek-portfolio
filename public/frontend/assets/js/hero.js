// hero.js

document.addEventListener('DOMContentLoaded', function() {
    // Select elements
    const overlay = document.querySelector('.hero-overlay');
    const profile = document.getElementById('profile-photo');

    // -----------------------------
    // Overlay opacity on scroll
    // -----------------------------
    if (overlay) {
        window.addEventListener('scroll', function() {
            let opacity = 0.5 - (window.scrollY / 450);
            overlay.style.background = `rgba(0,0,0,${Math.max(opacity, 0.3)})`;
        });
    }

    // -----------------------------
    // Profile float + parallax effect
    // -----------------------------
    if (profile) {
        let start = 0;

        function animateProfile() {
            let scrollY = window.scrollY;

            // Responsive float & scroll parameters
            const amplitude = window.innerWidth > 768 ? 15 : 8; // float amplitude
            const speed = window.innerWidth > 768 ? 0.02 : 0.03; // float speed
            const scrollFactor = window.innerWidth > 768 ? 0.15 : 0.1; // parallax

            // Calculate positions
            const floatY = Math.sin(start) * amplitude; // floating effect
            const parallaxY = scrollY * scrollFactor; // scroll parallax
            const totalY = floatY + parallaxY;

            // Apply transform safely
            profile.style.transform = `translateY(${totalY}px)`;

            start += speed;
            requestAnimationFrame(animateProfile);
        }

        animateProfile();
    }
});