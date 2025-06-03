function toggleDarkMode() {
    const body = document.querySelector('body');
    body.classList.toggle('dark-mode');

    const icon = document.querySelector('#dark-mode-icon');
    if (icon.classList.contains('fa-moon')) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
}

function setDarkMode(isDarkMode) {
    const body = document.querySelector('body');
    const icon = document.querySelector('#dark-mode-icon');

    if (isDarkMode) {
        body.classList.add('dark-mode');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
    } else {
        body.classList.remove('dark-mode');
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
    }
}

function isDarkModeEnabled() {
    const body = document.querySelector('body');
    return body.classList.contains('dark-mode');
}

document.addEventListener('DOMContentLoaded', function() {
    const icon = document.createElement('i');
    icon.id = 'dark-mode-icon';
    icon.classList.add('fas', 'fa-moon');
    icon.style.cursor = 'pointer';

    const link = document.createElement('a');
    link.classList.add('nav-link', 'dark-mode-toggle');
    link.style.cursor = 'pointer';
    link.appendChild(icon);

    const li = document.createElement('li');
    li.classList.add('nav-item');
    li.appendChild(link);

    const nav = document.querySelector('.main-header .navbar-nav');
    nav.appendChild(li);

    link.addEventListener('click', function() {
        toggleDarkMode();
        localStorage.setItem('dark-mode-enabled', isDarkModeEnabled());
    });

    const isEnabled = JSON.parse(localStorage.getItem('dark-mode-enabled'));
    if (isEnabled) {
        setDarkMode(true);
    }
});
