@php
    $curr_route = request()->route()->getName();

    $usersActive = in_array($curr_route, ['usersRead', 'edit_user']) ? 'active' : '';
    $curconfActive = in_array($curr_route, ['setconfigure']) ? 'active' : '';
    $gradeconfActive = in_array($curr_route, ['setgradepassconfigure']) ? 'active' : '';
    $usersAccntActive = in_array($curr_route, ['accountRead']) ? 'active' : '';
    

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        @auth('web')
            @if(Auth::guard('web')->user()->isAdmin == '0')
                <a href="{{ route('usersRead') }}" class="list-group-item {{ $usersActive }}">User's Account</a> 
                <a href="{{ route('setconfigure') }}" class="list-group-item {{ $curconfActive }}">Setting Configure</a>
                <a href="{{ route('setgradepassconfigure') }}" class="list-group-item {{ $gradeconfActive }}">Grades Password</a>  
            @endif
            @if(Auth::guard('web')->user()->isAdmin == '3')
                <a href="{{ route('setgradepassconfigure') }}" class="list-group-item {{ $gradeconfActive }}">Grades Password</a>  
            @endif
        @endauth

        @auth('faculty')
            @if(Auth::guard('faculty')->user()->isAdmin == '5')
            @endif
        @endauth
        
        <a href="{{ route('accountRead') }}" class="list-group-item {{ $usersAccntActive }}">Accounts</a>
    </ul>
</div>