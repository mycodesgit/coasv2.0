@php
    $curr_route = request()->route()->getName();

    $studentdashActive = in_array($curr_route, ['index.student']) ? 'active' : '';
    $studentgradesActive = in_array($curr_route, ['show.grades']) ? 'active' : '';
    $studentaccntsActive = in_array($curr_route, ['show.account']) ? 'active' : '';
    $schedviewActive = in_array($curr_route, ['schedclassRead','schedstudentclassShow']) ? 'active' : '';
    $preenrolviewActive = in_array($curr_route, ['pre.index', 'pre.show']) ? 'active' : '';
@endphp

<ul>
    <li>
        <span class="logo d-flex align-items-center">
            <div class="img-circle d-inline-flex align-items-center justify-content-center" style="width: 50px; height: 50px; background-color: #198754; color: #fff; font-weight: bold; font-size: 17px; border-radius: 50%; text-align: center; line-height: 3;">
                {{ substr($studauth->fname, 0, 1) }}{{ substr($studauth->lname, 0, 1) }}
            </div>

            <div class="ms-2" style="line-height: 1.2;">
                <div class="student-id-text" style="font-size: 12px; color: #666;">
                    <span style="font-size: 13px; color: #198754">{{ $studauth->stud_id }}</span><br> <span style="font-size: 10px;">Student ID</span>
                </div>
            </div>
        </span>


        <button onclick=toggleSidebar() id="toggle-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#fff">
                <path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z" />
            </svg>
        </button>
    </li>

    <!-- <hr style="background-color: #dbdee4; border: none; height: 1px; margin-top: -15px;"> -->

    <li class="{{ $studentdashActive }}" style="margin-top: 2px;">
        <a href="{{ route('index.student') }}">
            <i class="fas fa-home"></i>
            <span data-full="Dashboard">Dashboard</span>
        </a>
    </li>
    <li class="{{ $studentgradesActive }}">
        <a href="{{ route('show.grades') }}" style="margin-top: 2px;">
            <i class="fas fa-list-ol"></i>
            <span data-full="View Grades">Grades</span>
        </a>
    </li>
    <li class="{{ $studentaccntsActive }}">
        <a href="{{ route('show.account') }}" style="margin-top: 2px;">
            <i class="fas fa-rectangle-list"></i>
            <span data-full="View Accounts">Accounts</span>
        </a>
    </li>
    <li class="{{ $schedviewActive }}">
        <a href="{{ route('schedclassRead') }}" style="margin-top: 2px;">
            <i class="fas fa-business-time"></i>
            <span data-full="View Schedule"> Schedule</span>
        </a>
    </li>
    <li class="{{ $preenrolviewActive }}">
        <a href="{{ route('pre.index') }}" style="margin-top: 2px;">
            <i class="fas fa-graduation-cap"></i>
            <span data-full="Pre-Enrollment">Pre-Enroll</span>
        </a>
    </li>
</ul>