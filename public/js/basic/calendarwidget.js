document.addEventListener('DOMContentLoaded', function () {
    // 2. Interactive Client-side Calendar Engine
    function renderLiveCalendar() {
        const container = document.getElementById('calendarDaysContainer');
        const title = document.getElementById('calendarMonthYear');
        const now = new Date();

        const year = now.getFullYear();
        const month = now.getMonth();
        const today = now.getDate();

        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        title.textContent = `${monthNames[month]} ${year}`;

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const prevMonthDays = new Date(year, month, 0).getDate();

        let html = '<div class="row g-1 text-center">';
        let cellCount = 0;

        // Previous month padding days
        for (let i = firstDay - 1; i >= 0; i--) {
            html += `<div class="col"><div class="shadcn-calendar-day muted">${prevMonthDays - i}</div></div>`;
            cellCount++;
        }

        // Current month days
        for (let day = 1; day <= daysInMonth; day++) {
            if (cellCount % 7 === 0 && cellCount !== 0) {
                html += '</div><div class="row g-1 text-center mt-1">';
            }
            const isActive = day === today ? 'active' : '';
            html += `<div class="col"><div class="shadcn-calendar-day ${isActive}">${day}</div></div>`;
            cellCount++;
        }

        // Next month padding days
        let nextDay = 1;
        while (cellCount % 7 !== 0) {
            html += `<div class="col"><div class="shadcn-calendar-day muted">${nextDay}</div></div>`;
            nextDay++;
            cellCount++;
        }

        html += '</div>';
        container.innerHTML = html;
    }

    renderLiveCalendar();
});