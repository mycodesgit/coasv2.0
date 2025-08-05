@php
    $curr_route = request()->route()->getName();

    $dashSchedActive = in_array($curr_route, ['scheduler-index']) ? 'active' : '';
    $collegeActive = in_array($curr_route, ['collegeRead']) ? 'active' : '';
    $classProgActive = in_array($curr_route, ['programsRead']) ? 'active' : '';
    $roomActive = in_array($curr_route, ['roomsRead']) ? 'active' : '';
    $classEnrollActive = in_array($curr_route, ['courseEnroll_list', 'courseEnroll_list_search']) ? 'active' : '';
    $suboffActive = in_array($curr_route, ['subjectsOffered', 'subjectsOffered_search']) ? 'active' : '';
    $facultyActive = in_array($curr_route, ['faculty_list', 'faculty_listsearch']) ? 'active' : '';
    $curriActive = in_array($curr_route, ['curRead']) ? 'active' : '';
    $facDesigActive = in_array($curr_route, ['faculty_design', 'faculty_design_search']) ? 'active' : '';
    $classSchedActive = in_array($curr_route, ['classSchedRead', 'classSchedSetRead']) ? 'active' : '';
    $facultySchedActive = in_array($curr_route, ['facultySchedRead']) ? 'active' : '';
    $roomSchedActive = in_array($curr_route, ['roomSchedRead']) ? 'active' : '';
    $subjectActive = in_array($curr_route, ['subjectsRead']) ? 'active' : '';
    $facultyloadActive = in_array($curr_route, ['facultyloadRead', 'facultyload_search']) ? 'active' : '';
    $subjectloadActive = in_array($curr_route, ['reportsuboffer', 'reportsuboffer_search']) ? 'active' : '';
    

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('scheduler-index') }}" class="list-group-item {{ $dashSchedActive }}">Dashboard</a>
    </ul>
    <ul class="list-group mt-1">
        @if(in_array(Auth::guard('web')->user()->role, [0]))
            <a href="{{ route('collegeRead') }}" class="list-group-item {{ $collegeActive }}">College</a>
        @endif
        @if(in_array(Auth::guard('web')->user()->role, [0]))
            <a href="{{ route('programsRead') }}" class="list-group-item {{ $classProgActive }}">Programs</a>
        @endif
        @if(in_array(Auth::guard('web')->user()->role, [0, 12, 14]))
            <a href="{{ route('roomsRead') }}" class="list-group-item {{ $roomActive }}">Rooms</a>
        @endif
        @if(in_array(Auth::guard('web')->user()->role, [0, 3, 12, 14, 15]))
            <a href="{{ route('courseEnroll_list') }}" class="list-group-item {{ $classEnrollActive }}">Classes Enrolled</a>  
        @endif
        @if(in_array(Auth::guard('web')->user()->role, [0, 12, 14]))
            <a href="{{ route('faculty_design') }}" class="list-group-item {{ $facDesigActive }}">Designation</a>
        @endif
        @if(in_array(Auth::guard('web')->user()->role, [0, 3, 12, 14, 15]))
            <a href="{{ route('subjectsOffered') }}" class="list-group-item {{ $suboffActive }}">Subject Offered</a>
        @endif
        @if(in_array(Auth::guard('web')->user()->role, [0, 5, 6, 7, 12, 14]))
            <a href="{{ route('faculty_list') }}" class="list-group-item {{ $facultyActive }}">Faculty</a>
        @endif
        @if(in_array(Auth::guard('web')->user()->role, [0]))
            <a href="{{ route('curRead') }}" class="list-group-item {{ $curriActive }}">Curriculumn</a>
        @endif
    </ul>
</div>


<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Scheduler</h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('classSchedRead') }}" class="list-group-item {{ $classSchedActive }}">Class Schedule</a>
        <a href="{{ route('facultySchedRead') }}" class="list-group-item {{ $facultySchedActive }}">Faculty Schedule</a>
        <a href="{{ route('roomSchedRead') }}" class="list-group-item {{ $roomSchedActive }}">Room Schedule</a>
    </ul>
</div>


@if(Auth::guard('web')->user()->role == 0 || Auth::guard('web')->user()->lname == 'Gargoles')
<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports</h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('facultyloadRead') }}" class="list-group-item {{ $facultyloadActive }}">Faculty Load</a>
        <a href="{{ route('reportsuboffer') }}" class="list-group-item {{ $subjectloadActive }}">Subject Load</a>
    </ul>
</div>
@endif