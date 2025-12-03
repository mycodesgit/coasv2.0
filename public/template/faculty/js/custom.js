/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */

"use strict";

document.addEventListener('DOMContentLoaded', function() {
    const calendarDays = document.getElementById('calendarDays');
    const monthYear = document.getElementById('monthYear');
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');

    const months = [
        'January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    let current = new Date();

    function renderCalendar() {
        const year = current.getFullYear();
        const month = current.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month + 1, 0).getDate();

        monthYear.textContent = `${months[month]}, ${year}`;
        calendarDays.innerHTML = '';

        const daysOfWeek = ['S','M','T','W','T','F','S'];
        daysOfWeek.forEach(d => {
            const div = document.createElement('div');
            div.textContent = d;
            div.classList.add('header');
            calendarDays.appendChild(div);
        });

        for (let i = 0; i < firstDay; i++) {
            const empty = document.createElement('div');
            calendarDays.appendChild(empty);
        }

        const today = new Date();
        for (let day = 1; day <= lastDate; day++) {
            const div = document.createElement('div');
            div.textContent = day;
            div.classList.add('day');

            const date = new Date(year, month, day);
            const weekday = date.getDay();

            // highlight today
            if (
                day === today.getDate() &&
                month === today.getMonth() &&
                year === today.getFullYear()
            ) {
                div.classList.add('today');
            }

            // example active days
            // if ([5, 17, 24].includes(day)) {
            //     div.classList.add('active');
            // }

            if (weekday === 0) {
                div.style.color = '#dc3545'; // red
            } else if (weekday === 6) {
                div.style.color = '#0d6efd'; // blue
            }

            calendarDays.appendChild(div);
        }
    }

    prevBtn.addEventListener('click', () => {
        current.setMonth(current.getMonth() - 1);
        renderCalendar();
    });

    nextBtn.addEventListener('click', () => {
        current.setMonth(current.getMonth() + 1);
        renderCalendar();
    });

    renderCalendar();
});


