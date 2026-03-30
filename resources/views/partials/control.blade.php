<div class="menu-container bg-white border-bottom">
    <div class="container-fluid py-3 d-none d-md-block">
        <div class="menu-grid">

            <a id="home-url" class="menu-item">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>

            <a id="admission-url" class="menu-item {{ in_array('admission-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-id-card"></i>
                <span>Admission</span>
            </a>

            <a id="enrollment-url" class="menu-item {{ in_array('enrollment-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-laptop-code"></i>
                <span>Enrollment</span>
            </a>

            <a id="scheduler-url" class="menu-item {{ in_array('scheduler-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-calendar-alt"></i>
                <span>Scheduling</span>
            </a>

            <a id="assessment-url" class="menu-item {{ in_array('assessment-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-clipboard"></i>
                <span>Assessment</span>
            </a>

            <a id="cashiering-url" class="menu-item {{ in_array('cashiering-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-calculator"></i>
                <span>Cashier</span>
            </a>

            <a id="scholarship-url" class="menu-item {{ in_array('scholarship-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-graduation-cap"></i>
                <span>Scholarship</span>
            </a>

            <a id="yearbook-url" class="menu-item {{ in_array('yearbook-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-book"></i>
                <span>Yearbook</span>
            </a>

            <a id="kiosk-url" class="menu-item {{ in_array('kiosk-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-desktop"></i>
                <span>Kiosk</span>
            </a>

            <a id="queue-url" class="menu-item {{ in_array('queue-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-list-ol"></i>
                <span>Queuing</span>
            </a>

            <a id="nstp-url" class="menu-item {{ in_array('nstp-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-person-rifle"></i>
                <span>Nstp</span>
            </a>

            <a id="ossa-url" class="menu-item {{ in_array('ossa-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-user-gear"></i>
                <span>Ossa</span>
            </a>

            <a id="request-url" class="menu-item {{ in_array('request-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-file"></i>
                <span>Request</span>
            </a>

            <a id="setting-url" class="menu-item {{ in_array('setting-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-cog"></i>
                <span>Accounts</span>
            </a>
        </div>
    </div>
</div>

{{-- <li class="nav-item" style="margin-bottom: -10px ">
    <a id="home-url" class="btn btn-app">
        <i class="fas fa-home"></i> Home
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="admission-url" class="btn btn-app {{ in_array('admission-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-id-card"></i> Admission
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="enrollment-url" class="btn btn-app {{ in_array('enrollment-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-laptop-code"></i> Enrollment
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="scheduler-url" class="btn btn-app {{ in_array('scheduler-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-calendar-alt"></i> Scheduling
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="assessment-url" class="btn btn-app {{ in_array('assessment-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-scale-balanced"></i> Assessment
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="cashiering-url" class="btn btn-app {{ in_array('cashiering-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-calculator"></i> Cashiering
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="scholarship-url" class="btn btn-app {{ in_array('scholarship-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-users"></i> Scholarship
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="yearbook-url" class="btn btn-app {{ in_array('yearbook-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-book"></i> YearBook
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="grading-url" class="btn btn-app {{ in_array('grading-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-book-open"></i> Grading
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="kiosk-url" class="btn btn-app {{ in_array('kiosk-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-tv"></i> Kiosk
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="queue-url" class="btn btn-app {{ in_array('queue-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-list-ol"></i> Queueing
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="request-url" class="btn btn-app {{ in_array('request-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-file-lines"></i> Request
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="nstp-url" class="btn btn-app {{ in_array('nstp-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-person-rifle"></i> Nstp
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="setting-url" class="btn btn-app {{ in_array('setting-url', $buttons) ? '' : 'disabled' }}">
        <i class="fas fa-cog"></i> Settings
    </a>
</li>

<li class="nav-item" style="margin-bottom: -10px ">
    <a id="logout-url" class="btn btn-app">
        <i class="fas fa-sign-out-alt"></i> Sign Out
    </a>
</li> --}}

{{-- <div class="row pt-2">
    <div class="col-sm-12">
        <div>
            <a id="home-url" class="btn btn-app">
                <i class="fas fa-home"></i> Home
            </a>

            <a id="admission-url" class="btn btn-app {{ in_array('admission-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-id-card"></i> Admission
            </a>

            <a id="enrollment-url" class="btn btn-app {{ in_array('enrollment-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-laptop-code"></i> Enrollment
            </a>

            <a id="scheduler-url" class="btn btn-app {{ in_array('scheduler-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-calendar-alt"></i> Scheduling
            </a>

            <a id="assessment-url" class="btn btn-app {{ in_array('assessment-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-scale-balanced"></i> Assessment
            </a>

            <a id="cashiering-url" class="btn btn-app {{ in_array('cashiering-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-calculator"></i> Cashiering
            </a>

            <a id="scholarship-url" class="btn btn-app {{ in_array('scholarship-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-users"></i> Scholarship
            </a>

            <a id="grading-url" class="btn btn-app {{ in_array('grading-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-book-open"></i> Grading
            </a>

            <a id="kiosk-url" class="btn btn-app {{ in_array('kiosk-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-tv"></i> Kiosk
            </a>

            <a id="queue-url" class="btn btn-app {{ in_array('queue-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-list-ol"></i> Queueing
            </a>

            <a id="request-url" class="btn btn-app {{ in_array('request-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-file-lines"></i> Request
            </a>

            <a id="setting-url" class="btn btn-app {{ in_array('setting-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-cog"></i> Settings
            </a>

            <a id="logout-url" class="btn btn-app">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </div>
</div> --}}




