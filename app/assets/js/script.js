// script.js Main Application

// Contoh Interaktivitas: Sidebar Toggle (untuk Mobile) logic akan ada di sini
document.addEventListener('DOMContentLoaded', () => {
    console.log("Kasir Pro App Loaded");

    // Efek Ripple pada button (Opsional untuk estetika)
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;
            // logic ripple effect custom
        });
    });
});
