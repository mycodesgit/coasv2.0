<div class="row pt-2 card">
    <div class="col-sm-12">
        <div>
            <a id="home-url" class="btn btn-app active">
                <i class="fas fa-home"></i> Home
            </a>
            
            <a @if(!in_array(Auth::guard($guard)->user()->isAdmin, [3, 4, 8, 9, 10, 11, 943])) id="admission-url" @endif class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [3, 4, 8, 9, 10, 11, 943])) disabled @endif ">
                <i class="fas fa-id-card"></i> Admission
            </a>

            <a @if(!in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 5, 6, 7, 8, 9, 10, 11, 943])) id="enrollment-url" @endif class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 5, 6, 7, 8, 9, 10, 11, 943])) disabled @endif ">
                <i class="fas fa-laptop-code"></i> Enrollment
            </a>

            <a @if(!in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 6, 8, 9, 10, 11, 943])) id="scheduler-url" @endif class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 6, 8, 9, 10, 11, 943])) disabled @endif ">
                <i class="fas fa-calendar-alt"></i> Scheduling
            </a>
           
            <a @if(!in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 943])) id="assessment-url" @endif class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 943])) disabled @endif ">
                <i class="fas fa-scale-balanced"></i> Assessment
            </a>

            <a @if(!in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11, 943])) id="cashiering-url" @endif class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11, 943])) disabled @endif ">
                <i class="fas fa-calculator"></i> Cashiering
            </a>

            <a @if(!in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 7, 10, 943])) id="scholarship-url" @endif class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 7, 10, 943])) disabled @endif ">
                <i class="fas fa-users"></i> Scholarship
            </a>

            <a @if(!in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11])) id="grading-url" @endif class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11])) disabled @endif ">
                <i class="fas fa-book-open"></i> Grading
            </a>

            <a @if(!in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11, 943])) id="request-url" @endif class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11, 943])) disabled @endif ">
                <i class="fas fa-file-lines"></i> Request
            </a>

            <a id="setting-url" class="btn btn-app">
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
    var requestRoute = "";
    var settingRoute = "{{ route('settings-index') }}";
    var logoutRoute = "{{ route('logout') }}";
</script>

