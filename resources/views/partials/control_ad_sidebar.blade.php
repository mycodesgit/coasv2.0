@php
    $curr_route = request()->route()->getName();

    $appAddActive = in_array($curr_route, ['applicant-add']) ? 'active' : '';
    $appListActive = in_array($curr_route, ['applicant-list', 'srchappList', 'applicant_edit']) ? 'active' : '';
    $examineeListActive = in_array($curr_route, ['examinee-list', 'srchexamineeList', 'examinee_edit']) ? 'active' : '';
    $resultListActive = in_array($curr_route, ['result-list', 'srchexamineeResultList', 'assignresult', 'pre_enrolment_print', 'confirmResult']) ? 'active' : '';
    $confirmAppListActive = in_array($curr_route, ['examinee-confirm', 'srchconfirmList', 'accept', 'deptInterview']) ? 'active' : '';
    $acceptedAppListActive = in_array($curr_route, ['applicant-accepted', 'srchacceptedList', 'accepted_push_enroll_applicant']) ? 'active' : '';
    $enrolledAppListActive = in_array($curr_route, ['applicant-enrolled', 'srchacceptedEnrolledList']) ? 'active' : '';
    $slotActive = in_array($curr_route, ['slots', 'slots_search']) ? 'active' : '';
    $confActive = in_array($curr_route, ['configure_admission', 'edit_program', 'edit_strand', 'edit_date', 'edit_time', 'edit_venue']) ? 'active' : '';

    $appsreportActive = in_array($curr_route, ['applicant_printing', 'applicant_reports']) ? 'active' : '';
    $schedreportActive = in_array($curr_route, ['schedules_printing', 'schedules_reports']) ? 'active' : '';
    $noschedreportActive = in_array($curr_route, ['nosched_printing', 'nosched_reports']) ? 'active' : '';   
    $examreportActive = in_array($curr_route, ['examination_printing', 'examination_reports']) ? 'active' : '';  
    $qualreportActive = in_array($curr_route, ['qualified_printing']) ? 'active' : '';    
@endphp

<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        @if(in_array(Auth::user()->isAdmin, [0, 1, 2]))
            <a href="{{ route('applicant-add') }}" class="list-group-item {{ $appAddActive }}">Add Applicants</a>
            <a href="{{ route('applicant-list') }}" class="list-group-item {{ $appListActive }}">Applicants</a>  
            <a href="{{ route('examinee-list') }}" class="list-group-item {{ $examineeListActive }}">List of Examinees</a>
            <a href="{{ route('result-list') }}" class="list-group-item {{ $resultListActive }}">Examination Results</a>
        @endif
        <a href="{{ route('examinee-confirm') }}" class="list-group-item {{ $confirmAppListActive }}">Confirmed Applicants</a>  
        <a href="{{ route('applicant-accepted') }}" class="list-group-item {{ $acceptedAppListActive }}">Accepted Applicants</a>
        {{-- <a href="{{ route('applicant-enrolled') }}" class="list-group-item {{ $enrolledAppListActive }}">Enrolled Applicants</a> --}}
        <a href="{{ route('slots') }}" class="list-group-item {{ $slotActive }}">Availability/Slots</a>
        <a href="{{ route('configure_admission') }}" class="list-group-item {{ $confActive }}">Configure Admission</a>
    </ul>
</div>


<div class="page-header ml-2 mr-2 mt-3" style="border-bottom: 1px solid #04401f;">
    <h5>Reports</h5>
</div>
<div class="ml-2 mr-2 mt-3 mb-3">
    <ul class="list-group">
        <a href="{{ route('applicant_printing') }}" class="list-group-item {{ $appsreportActive }}">Applicants</a>
        <a href="{{ route('schedules_printing') }}" class="list-group-item {{ $schedreportActive }}">Admission Schedules</a>
        <a href="{{ route('nosched_printing') }}" class="list-group-item {{ $noschedreportActive}}">Applicants No Sched</a>
        <a href="{{ route('examination_printing') }}" class="list-group-item {{ $examreportActive }}">Examination Results</a>
        <a href="{{ route('qualified_printing') }}" class="list-group-item {{ $qualreportActive }}">Qualified Applicants</a>
        <a href="" class="list-group-item">Confirmed Applicants</a> 
        <a href="" class="list-group-item">Accepted Applicants</a>
    </ul>
</div>