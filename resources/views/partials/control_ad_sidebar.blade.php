@php
    $curr_route = request()->route()->getName();

    $dashAdActive = in_array($curr_route, ['admission-index']) ? 'active' : '';
    $appAddActive = in_array($curr_route, ['applicant-add']) ? 'active' : '';
    $appListActive = in_array($curr_route, ['applicant-list', 'srchappList', 'applicant_edit']) ? 'active' : '';
    $examineeListActive = in_array($curr_route, ['examinee-list', 'srchexamineeList', 'examinee_edit']) ? 'active' : '';
    $resultListActive = in_array($curr_route, ['result-list', 'resultlist_search', 'assignresult', 'confirmResult']) ? 'active' : '';
    $confirmAppListActive = in_array($curr_route, ['examinee-confirm', 'srchconfirmList', 'accept', 'deptInterview', 'pre_enrolment_print']) ? 'active' : '';
    $acceptedAppListActive = in_array($curr_route, ['applicant-accepted', 'srchacceptedList', 'accepted_push_enroll_applicant']) ? 'active' : '';
    $acceptedAppListAllActive = in_array($curr_route, ['applicant-acceptedall', 'srchacceptedListAll']) ? 'active' : '';
    $enrolledAppListActive = in_array($curr_route, ['applicant-enrolled', 'srchacceptedEnrolledList']) ? 'active' : '';
    $slotActive = in_array($curr_route, ['slots', 'slots_search']) ? 'active' : '';
    $confActive = in_array($curr_route, ['configure_admission', 'edit_program', 'edit_strand', 'edit_date', 'edit_time', 'edit_venue']) ? 'active' : '';
    $changecampActive = in_array($curr_route, ['alllistappRead', 'alllistappRead_search']) ? 'active' : '';
    $transferActive = in_array($curr_route, ['transferstud']) ? 'active' : '';

    $appsreportActive = in_array($curr_route, ['applicant_printing', 'applicant_reports']) ? 'active' : '';
    $appschoolreportActive = in_array($curr_route, ['applicantperschool_printing', 'applicantperschool_reports']) ? 'active' : '';
    $schedreportActive = in_array($curr_route, ['schedules_printing', 'schedules_reports']) ? 'active' : '';
    $noschedreportActive = in_array($curr_route, ['nosched_printing', 'nosched_reports']) ? 'active' : '';   
    $examreportActive = in_array($curr_route, ['examination_printing', 'examination_reports']) ? 'active' : '';  
    $qualreportActive = in_array($curr_route, ['qualified_printing', 'qualified_reports']) ? 'active' : ''; 
    $acceptedreportActive = in_array($curr_route, ['accepted_printing', 'accepted_reports']) ? 'active' : ''; 

    $courseprefreportActive = in_array($curr_route, ['indexcoursepref', 'indexcoursepref_search']) ? 'active' : '';   

    $billingreportActive = in_array($curr_route, ['adbillingRead', 'adbillingRead_search']) ? 'active' : '';    
@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
    <li>
        <a class="nav-link {{ $dashAdActive }}" href="{{ route('admission-index') }}">
            <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>
</ul>