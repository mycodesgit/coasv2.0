@php
$user = Auth::user();
$buttonAccess = $user->buttonAccess;
$buttons = $buttonAccess ? $buttonAccess->buttons : [];
@endphp

<div class="row pt-2 card">
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

            <a id="request-url" class="btn btn-app {{ in_array('request-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-file-lines"></i> Request
            </a>

            <a id="setting-url" class="btn btn-app {{ in_array('setting-url', $buttons) ? '' : 'disabled' }}">
                <i class="fas fa-cog"></i> Settings
            </a>

            <a id="logout-url" class="btn btn-app float-right">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </div>
</div>


<script>
    var homeRoute = "{{ route('home') }}";
    var admissionRoute = "{{ route('admission-index') }}";
    var enrollmentRoute = "{{ route('enrollment-index') }}";
    var schedulerRoute = "{{ route('scheduler-index') }}";
    var assessmentRoute = "{{ route('assessment-index') }}";
    var cashierRoute = "";
    var scholarshipRoute = "{{ route('scholarship-index') }}";
    var gradingRoute = "{{ route('grading-index') }}";
    var kioskRoute = "{{ route('adminkioskRead') }}";
    var requestRoute = "";
    var settingRoute = "{{ route('settings-index') }}";
    var logoutRoute = "{{ route('logout') }}";
</script>

