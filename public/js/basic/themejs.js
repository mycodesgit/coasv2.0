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

    // 1. Read current attribute set by inline script, or compute same fallback
    const currentTheme = htmlElement.getAttribute('data-bs-theme') || 
        (localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));

    // 2. Sync icon state without resetting data-bs-theme
    updateIcon(currentTheme);

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