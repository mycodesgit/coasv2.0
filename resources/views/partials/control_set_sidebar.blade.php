@php
    $curr_route = request()->route()->getName();

    $usersActive = in_array($curr_route, ['usersRead', 'edit_user']) ? 'active' : '';
    $facultyActive = in_array($curr_route, ['facultiesRead']) ? 'active' : '';
    $curconfActive = in_array($curr_route, ['setconfigure']) ? 'active' : '';
    $enrollconfActive = in_array($curr_route, ['setEnrollConf']) ? 'active' : '';
    $gradeconfActive = in_array($curr_route, ['setgradepassconfigure']) ? 'active' : '';
    $usersAccntActive = in_array($curr_route, ['accountRead']) ? 'active' : '';
    $serverActive = in_array($curr_route, ['serverMaintenance']) ? 'active' : '';

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        @auth('web')
            @if(Auth::guard('web')->user()->role == '0')
                <a href="{{ route('usersRead') }}" class="list-group-item {{ $usersActive }}">User's Account</a> 
                <a href="{{ route('facultiesRead') }}" class="list-group-item {{ $facultyActive }}">Faculty Account</a> 
                <a href="{{ route('setconfigure') }}" class="list-group-item {{ $curconfActive }}">Setting Configure</a>
                <a href="{{ route('setEnrollConf') }}" class="list-group-item {{ $enrollconfActive }}">Enrollment Status</a>  
                <a href="{{ route('setgradepassconfigure') }}" class="list-group-item {{ $gradeconfActive }}">Grades Password</a> 
                <a href="{{ route('serverMaintenance') }}" class="list-group-item {{ $serverActive }}">Server Status</a>  
            @endif
            @if(Auth::guard('web')->user()->fname == 'Gmar')
                <a href="{{ route('usersRead') }}" class="list-group-item {{ $usersActive }}">User's Account</a>  
            @endif
            @if(Auth::guard('web')->user()->role == '3')
                <a href="{{ route('setEnrollConf') }}" class="list-group-item {{ $enrollconfActive }}">Enrollment Status</a>  
            @endif
            @if(Auth::guard('web')->user()->role == '3')
                <a href="{{ route('setgradepassconfigure') }}" class="list-group-item {{ $gradeconfActive }}">Grades Password</a>  
            @endif
        @endauth

        @auth('faculty')
            @if(Auth::guard('faculty')->user()->role == '943')
            @endif
        @endauth
        
        <a href="{{ route('accountRead') }}" class="list-group-item {{ $usersAccntActive }}">Accounts</a>
    </ul>
</div>