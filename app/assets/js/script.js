// script.js Main Application

// Contoh Interaktivitas: Sidebar Toggle (untuk Mobile) logic akan ada di sini
document.addEventListener('DOMContentLoaded', () => {
    console.log("Kasir Pro App Loaded");

    // Efek Ripple pada button (Opsional untuk estetika)
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            let x = e.clientX - e.target.offsetLeft;
            let y = e.clientY - e.target.offsetTop;
            // logic ripple effect custom
        });
    });

    // Load saved theme
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.body.setAttribute('data-theme', savedTheme);
        updateToggleIcon(savedTheme);
    }
});

function toggleTheme(e) {
    if (e) e.preventDefault();
    const currentTheme = document.body.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    updateToggleIcon(newTheme);
}

function updateToggleIcon(theme) {
    const btn = document.getElementById('themeToggle');
    if (btn) {
        const icon = btn.querySelector('i');
        if (theme === 'light') {
            btn.innerHTML = '<i class="fas fa-moon"></i> Mode Gelap';
        } else {
            btn.innerHTML = '<i class="fas fa-sun"></i> Mode Terang';
        }
    }
}
