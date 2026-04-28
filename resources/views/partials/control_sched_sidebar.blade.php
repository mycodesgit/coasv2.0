@php
    $curr_route = request()->route()->getName();

    $dashSchedActive = in_array($curr_route, ['scheduler-index']) ? 'active' : '';
    $collegeActive = in_array($curr_route, ['collegeRead']) ? 'active' : '';
    $classProgActive = in_array($curr_route, ['programsRead']) ? 'active' : '';
    $roomActive = in_array($curr_route, ['roomsRead']) ? 'active' : '';
    $classEnrollActive = in_array($curr_route, ['courseEnroll_list', 'courseEnroll_list_search']) ? 'active' : '';
    $suboffActive = in_array($curr_route, ['subjectsOffered', 'subjectsOffered_search']) ? 'active' : '';
    $facultyActive = in_array($curr_route, ['faculty_list', 'faculty_listsearch']) ? 'active' : '';
    $curriActive = in_array($curr_route, ['curRead', 'curRead_search']) ? 'active' : '';
    $facDesigActive = in_array($curr_route, ['faculty_design', 'faculty_design_search']) ? 'active' : '';
    $classSchedActive = in_array($curr_route, ['classSchedRead', 'classSchedSetRead']) ? 'active' : '';
    $facultySchedActive = in_array($curr_route, ['facultySchedRead']) ? 'active' : '';
    $roomSchedActive = in_array($curr_route, ['roomSchedRead']) ? 'active' : '';
    $subjectActive = in_array($curr_route, ['subjectsRead']) ? 'active' : '';
    $facultyloadActive = in_array($curr_route, ['facultyloadRead', 'facultyload_search']) ? 'active' : '';
    $subjectloadActive = in_array($curr_route, ['reportsuboffer', 'reportsuboffer_search']) ? 'active' : '';
    

@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
    <li>
        <a class="nav-link {{ $dashSchedActive }}" href="{{ route('scheduler-index') }}">
            <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>

    @if(in_array(Auth::guard('web')->user()->role, [0]))
        <li>
            <a class="nav-link {{ $collegeActive }}" href="{{ route('collegeRead') }}">
                <i class="ti ti-building"></i><span class="nav-text">Colleges</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0]))
        <li>
            <a class="nav-link {{ $classProgActive }}" href="{{ route('programsRead') }}">
                <i class="ti ti-book"></i><span class="nav-text">Programs</span>
            </a>
        </li>
    @endif
    
    @if(in_array(Auth::guard('web')->user()->role, [0, 12, 14]))
        <li>
            <a class="nav-link {{ $roomActive }}" href="{{ route('roomsRead') }}">
                <i class="ti ti-route-square"></i><span class="nav-text">Class Rooms</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 3, 12, 14, 15]))
        <li>
            <a class="nav-link {{ $classEnrollActive }}" href="{{ route('courseEnroll_list') }}">
                <i class="ti ti-bookmark-plus"></i><span class="nav-text">Classes Enrolled</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0]) || Auth::guard('web')->user()->fname == 'Rosalie')
        <li>
            <a class="nav-link {{ $curriActive }}" href="{{ route('curRead') }}">
                <i class="ti ti-notes"></i><span class="nav-text">Curriculum Programs</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 12, 14]))
        <li>
            <a class="nav-link {{ $facDesigActive }}" href="{{ route('faculty_design') }}">
                <i class="ti ti-assembly"></i><span class="nav-text">Faculty Designation</span>
            </a>
        </li>
    @endif
    
    @if(in_array(Auth::guard('web')->user()->role, [0, 5, 6, 7, 12, 14]))
    {{-- @if(in_array(Auth::guard('web')->user()->role, [0])) --}}
        <li>
            <a class="nav-link {{ $facultyActive }}" href="{{ route('faculty_list') }}">
                <i class="ti ti-users"></i><span class="nav-text">Faculty List</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 3, 12, 14, 15]))
        <li>
            <a class="nav-link {{ $suboffActive }}" href="{{ route('subjectsOffered') }}">
                <i class="ti ti-book"></i><span class="nav-text">Subject Offered</span>
            </a>
        </li>
    @endif
    
    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Scheduler</small></li>

    <li>
        <a class="nav-link {{ $classSchedActive }}" href="{{ route('classSchedRead') }}">
            <i class="ti ti-calendar-check"></i><span class="nav-text">Class Schedule</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $facultySchedActive }}" href="{{ route('facultySchedRead') }}">
            <i class="ti ti-calendar-star"></i><span class="nav-text">Faculty Schedule</span>
        </a>
    </li>

    <li>
        <a class="nav-link {{ $roomSchedActive }}" href="{{ route('roomSchedRead') }}">
            <i class="ti ti-calendar-stats"></i><span class="nav-text">Room Schedule</span>
        </a>
    </li>
</ul>