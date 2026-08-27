document.addEventListener("DOMContentLoaded", function () {
    const themeBtn = document.getElementById('themeToggleBtn');
    const themeIcon = document.getElementById('themeIcon');
    const htmlElement = document.documentElement;

    function updateIcon(theme) {
        if (themeIcon) {
            // Updated so Sun shows when Dark mode is active (click to switch to light)
            themeIcon.className = theme === 'dark' ? 'ti ti-sun text-warning' : 'ti ti-moon';
        }
    }

    // Sync icon with current attribute on load
    const currentTheme = htmlElement.getAttribute('data-bs-theme') || 'light';
    updateIcon(currentTheme);

    // Toggle logic on click
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