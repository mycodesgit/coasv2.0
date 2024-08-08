@php
    $curr_route = request()->route()->getName();

    $dashEnActive = in_array($curr_route, ['enrollment-index']) ? 'active' : '';
    $searchStudActive = in_array($curr_route, ['searchStud', 'searchStudEnroll']) ? 'active' : '';
    $editEnrollStudActive = in_array($curr_route, ['editsearchStud', 'editsearchStudRead']) ? 'active' : '';
    $stuEnrollmentHisActive = in_array($curr_route, ['studentEnHistory', 'viewsearchenStudHistory']) ? 'active' : '';
    $gradeStudActive = in_array($curr_route, ['studgrade_search', 'studgrade_searchlist', 'geneStudent1']) ? 'active' : '';
    $subjectAllActive = in_array($curr_route, ['subjectsRead']) ? 'active' : '';
    $studinfoActive = in_array($curr_route, ['studInfo', 'studInfo_search']) ? 'active' : '';
    $studinfogradActive = in_array($curr_route, ['studInfograduated', 'studInfograduated_search']) ? 'active' : '';
    $studcurrActive = in_array($curr_route, ['studCurr', 'studCurrsearch']) ? 'active' : '';
    $studsubjActive = in_array($curr_route, ['studsubjectsRead', 'listsearch_studsubjectsRead', 'listsearchview_studsubjectsRead']) ? 'active' : '';
    $studviewgrdeActive = in_array($curr_route, ['studviewgradeRead', 'search_studviewgradeRead']) ? 'active' : '';
    $reportcardActive = in_array($curr_route, ['reportCard_list', 'reportCard_listsearch']) ? 'active' : '';
    $elplActive = in_array($curr_route, ['elpl_list', 'elpl_listsearch']) ? 'active' : '';
    $numenrolledActive = in_array($curr_route, ['studnoenrollee', 'studnoenrollee_searchList']) ? 'active' : '';
    $studevalActive = in_array($curr_route, ['studevalRead', 'studevalRead_listsearch']) ? 'active' : '';

@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('enrollment-index') }}" class="list-group-item {{ $dashEnActive }}">Dashboard</a>
    </ul>
    <ul class="list-group mt-1">
        @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 12, 13, 14, 15]))
        <a href="{{ route('searchStud') }}" class="list-group-item {{ $searchStudActive }}">Enroll Student</a>  
        <a href="{{ route('editsearchStud') }}" class="list-group-item {{ $editEnrollStudActive }}">Edit Enrollment</a>
        @endif
        <a href="{{ route('studentEnHistory') }}" class="list-group-item {{ $stuEnrollmentHisActive }}">Enrollment History</a>
        @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 13, 14, 15]))
        <a href="{{ route('studgrade_search') }}" class="list-group-item {{ $gradeStudActive }}">Grade Sheet</a>
        @endif
        @if(Auth::guard('web')->user()->role == 0 || Auth::guard('web')->user()->fname == 'Rosalie')
        <a href="{{ route('subjectsRead') }}" class="list-group-item {{ $subjectAllActive }}">Subjects</a>
        @endif
    </ul>
</div>


<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports</h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 13, 14]))
        <a href="{{ route('studInfo') }}" class="list-group-item {{ $studinfoActive }}">Students Information</a>
        <a href="{{ route('studInfograduated') }}" class="list-group-item {{ $studinfogradActive }}">Graduated Students</a>
        @endif
        <a href="{{ route('studCurr') }}" class="list-group-item {{ $studcurrActive }}">Students Per Course</a>
        <a href="{{ route('studsubjectsRead') }}" class="list-group-item {{ $studsubjActive }}">Students Attendance</a>
        <a href="{{ route('studviewgradeRead') }}" class="list-group-item {{ $studviewgrdeActive }}">View Student Grades</a>
        @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 13, 14]))
        <a href="{{ route('reportCard_list') }}" class="list-group-item {{ $reportcardActive }}">Students Report Card</a>
        <a href="{{ route('elpl_list') }}" class="list-group-item {{ $elplActive }}">EL and PL</a>
        @endif
        <a href="{{ route('studnoenrollee') }}" class="list-group-item {{ $numenrolledActive }}">Number of Enrollees</a>
        <a href="{{ route('studevalRead') }}" class="list-group-item {{ $studevalActive }}">Student Record</a>
    </ul>
</div>