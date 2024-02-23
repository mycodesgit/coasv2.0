@php
    $curr_route = request()->route()->getName();

    $classEnrollActive = in_array($curr_route, ['courseEnroll_list', 'courseEnroll_list_search']) ? 'active' : '';
    $suboffActive = in_array($curr_route, ['subjectsOffered', 'subjectsOffered_search']) ? 'active' : '';
    $facultyActive = in_array($curr_route, ['faculty_list', 'faculty_listsearch']) ? 'active' : '';
    $facDesigActive = in_array($curr_route, ['faculty_design', 'faculty_design_search']) ? 'active' : '';
    $subjectActive = in_array($curr_route, ['subjectsRead']) ? 'active' : '';
    $facultyloadActive = in_array($curr_route, ['facultyloadRead', 'facultyload_search']) ? 'active' : '';
    

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('courseEnroll_list') }}" class="list-group-item {{ $classEnrollActive }}">Classes Enrolled</a>  
        <a href="{{ route('faculty_list') }}" class="list-group-item {{ $facultyActive }}">Faculty</a>
        <a href="{{ route('faculty_design') }}" class="list-group-item {{ $facDesigActive }}">Designation</a>
        <a href="" class="list-group-item">Rooms</a>
        <a href="{{ route('subjectsOffered') }}" class="list-group-item {{ $suboffActive }}">Subject Offered</a>
    </ul>
</div>


<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports</h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('subjectsRead') }}" class="list-group-item {{ $subjectActive }}">Subjects</a>
        <a href="{{ route('facultyloadRead') }}" class="list-group-item {{ $facultyloadActive }}">Faculty Load</a>
    </ul>
</div>