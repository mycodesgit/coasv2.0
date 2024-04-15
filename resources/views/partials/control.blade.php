<div class="row pt-2 card">
    <div class="col-sm-12">
        <div>
            <a href="{{ route('home') }}" class="btn btn-app active">
                <i class="fas fa-home"></i> Home
            </a>
            
            <a href="{{ route('admission-index') }}" class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [3, 4, 5, 6, 7, 8, 9, 10, 11])) disabled @endif ">
                <i class="fas fa-id-card"></i> Admission
            </a>

            <a href="{{ route('enrollment-index') }}" class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 5, 6, 7, 8, 9, 10, 11])) disabled @endif ">
                <i class="fas fa-laptop-code"></i> Enrollment
            </a>

            <a href="{{ route('scheduler-index') }}" class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11])) disabled @endif ">
                <i class="fas fa-calendar-alt"></i> Scheduling
            </a>
           
            <a href="{{ route('assessment-index') }}" class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9,])) disabled @endif ">
                <i class="fas fa-scale-balanced"></i> Assessment
            </a>

            <a href="" class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11])) disabled @endif ">
                <i class="fas fa-calculator"></i> Cashiering
            </a>

            <a href="{{ route('scholarship-index') }}" class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, ])) disabled @endif ">
                <i class="fas fa-users"></i> Scholarship
            </a>

            <a href="{{ route('grading-index') }}" class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11])) disabled @endif ">
                <i class="fas fa-book-open"></i> Grading
            </a>

            <a href="" class="btn btn-app @if(in_array(Auth::guard($guard)->user()->isAdmin, [1, 2, 3, 5, 6, 8, 9, 10, 11])) disabled @endif ">
                <i class="fas fa-file-lines"></i> Request
            </a>

            <a href="{{ route('settings-index') }}" class="btn btn-app">
                <i class="fas fa-cog"></i> Settings
            </a>

            <a href="{{ route('logout') }}" class="btn btn-app float-right">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </div>
</div>