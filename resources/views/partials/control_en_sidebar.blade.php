@php
    $curr_route = request()->route()->getName();

    $dashEnActive = in_array($curr_route, ['enrollment-index']) ? 'active' : '';
    $searchStudActive = in_array($curr_route, ['searchStud', 'searchStudEnroll']) ? 'active' : '';
    $editEnrollStudActive = in_array($curr_route, ['editsearchStud', 'editsearchStudRead']) ? 'active' : '';
    $stuEnrollmentHisActive = in_array($curr_route, ['studentEnHistory', 'viewsearchenStudHistory']) ? 'active' : '';
    $gradeStudActive = in_array($curr_route, ['studgrade_search', 'studgrade_searchlist', 'geneStudent1']) ? 'active' : '';
    $subjectAllActive = in_array($curr_route, ['subjectsRead']) ? 'active' : '';
    $studinfoActive = in_array($curr_route, ['studInfo', 'studInfo_search']) ? 'active' : '';
    $studcurrActive = in_array($curr_route, ['studCurr', 'studCurrsearch']) ? 'active' : '';

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('enrollment-index') }}" class="list-group-item {{ $dashEnActive }}">Dashboard</a>
    </ul>
    <ul class="list-group mt-1">
        <a href="{{ route('searchStud') }}" class="list-group-item {{ $searchStudActive }}">Enroll Student</a>  
        <a href="{{ route('editsearchStud') }}" class="list-group-item {{ $editEnrollStudActive }}">Edit Enrollment</a>
        <a href="{{ route('studentEnHistory') }}" class="list-group-item {{ $stuEnrollmentHisActive }}">Enrollment History</a>
        <a href="{{ route('studgrade_search') }}" class="list-group-item {{ $gradeStudActive }}">Grade Sheet</a>
        <a href="{{ route('subjectsRead') }}" class="list-group-item {{ $subjectAllActive }}">Subjects</a>
    </ul>
</div>


<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports</h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('studInfo') }}" class="list-group-item {{ $studinfoActive }}">Students Information</a>
        <a href="{{ route('studCurr') }}" class="list-group-item {{ $studcurrActive }}">Students Per Curriculum</a>
        <a href="" class="list-group-item">Students Report Card</a>
        <a href="" class="list-group-item">Number of Enrollees</a>
        <a href="" class="list-group-item">Transcript</a>
    </ul>
</div>