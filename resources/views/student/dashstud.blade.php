@extends('layouts.master_student')

@section('title')
CISS V.1.0 || Student Dashboard
@endsection

@section('body')
    <style>
        .calendar-widget {
            width: 100%;
            margin: auto;
            font-family: 'Poppins', sans-serif;
        }
        .calendar-header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            margin-top: 10px;
        }
        .calendar-grid div {
            padding: 8px 0;
            border-radius: 8px;
            color: #333;
        }
        .calendar-grid div.header {
            font-weight: 600;
            color: #6c757d;
        }
        .calendar-grid div.day.active {
            background-color: #0d6efd;
            color: #fff;
        }
        .calendar-grid div.day.today {
            border: 1px solid #198754;
            background-color: #c0ffe3;
            color: #000000;
            font-weight: 600;
        }
        .btn-light {
            border-radius: 50%;
            padding: 2px 8px;
        }
    </style>

    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12">
                    <div class="content-box">
                        <h3>
                            Hi, {{ ucwords(strtolower(trim($studauth->fname))) }} 
                            {{ ucfirst(strtolower(trim(substr($studauth->mname, 0, 1)))) }}. 
                            {{ ucfirst(strtolower(trim($studauth->lname))) }}
                        </h3>

                        <p>Manage your academic life with ease — view your grades, update your account, check your class schedule, and prepare for pre-enrollment. Stay organized and take charge of your success!</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="content-box p-4">
                        <h3 class="text-center">Vision</h3>
                        <p>CPSU as the leading technology-driven multi-disciplinary University by 2030.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="content-box p-4">
                        <h3 class="text-center">Mission</h3>
                        <p>CPSU is committed to produce competent graduates who can generate and extend leading technologies in multi-disciplinary areas beneficial to the community.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="content-box p-4">
                        <h3 class="text-center">Goal</h3>
                        <p>To provide efficient, Quality, Technology-driven and Gender-Sensitive Products and Services.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="content-box p-4">
                <div class="calendar-header calendar-widget text-center mb-2">
                    <button id="prevMonth" class="btn btn-light btn-sm">&lt;</button>
                    <span id="monthYear" class="fw-semibold text-success"></span>
                    <button id="nextMonth" class="btn btn-light btn-sm">&gt;</button>
                </div>
                <div id="calendarDays" class="calendar-grid text-center"></div>
            </div>
        </div>
        <div class="col-md-3">
            
        </div>
    </div>

    <script>
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
    </script>
@endsection