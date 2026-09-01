document.addEventListener("DOMContentLoaded", function () {
    const themeBtn = document.getElementById('themeToggleBtn');
    const themeIcon = document.getElementById('themeIcon');
    const htmlElement = document.documentElement;

    function updateIcon(theme) {
        if (themeIcon) {
            // Sun shows when Dark mode is active (click to switch to light)
            themeIcon.className = theme === 'dark' ? 'ti ti-sun text-warning' : 'ti ti-moon';
        }
    }

    // 1. Get saved theme or default to 'light' on first load
    const savedTheme = localStorage.getItem('theme') || 'light';

    // 2. Apply theme to <html> tag and update icon on load
    htmlElement.setAttribute('data-bs-theme', savedTheme);
    updateIcon(savedTheme);

    // 3. Toggle logic on click
    if (themeBtn) {
        themeBtn.addEventListener('click', function () {
            const activeTheme = htmlElement.getAttribute('data-bs-theme');
            const newTheme = activeTheme === 'dark' ? 'light' : 'dark';

            htmlElement.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
        });
    }
});