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
    
    @if(in_array(Auth::user()->role, [0, 1, 2]))
        <li>
            <a class="nav-link {{ $appListActive }}" href="{{ route('applicant-list') }}">
                <i class="ti ti-user-star"></i><span class="nav-text">List of Applicants</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $examineeListActive }}" href="{{ route('examinee-list') }}">
                <i class="ti ti-user-bolt"></i><span class="nav-text">List of Examinees</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $resultListActive }}" href="{{ route('result-list') }}">
                <i class="ti ti-user-up"></i><span class="nav-text">Examination Results</span>
            </a>
        </li>
    @endif
    
    <li>
        <a class="nav-link {{ $confirmAppListActive }}" href="{{ route('examinee-confirm') }}">
            <i class="ti ti-user-check"></i><span class="nav-text">Confirmed Applicants</span>
        </a>
    </li>
    
    @if(in_array(Auth::user()->role, [5, 6, 7, 14]))  
        <li>
            <a class="nav-link {{ $acceptedAppListActive }}" href="{{ route('applicant-accepted') }}">
                <i class="ti ti-user-code"></i><span class="nav-text">Accepted Applicants</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::user()->role, [0, 1, 2]))
        <li>
            <a class="nav-link {{ $acceptedAppListAllActive }}" href="{{ route('applicant-acceptedall') }}">
                <i class="ti ti-user-code"></i><span class="nav-text">Accepted Applicants</span>
            </a>
        </li>
    @endif
    
    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Configuration</small></li>
    @if(in_array(Auth::user()->role, [0, 1, 2]))
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-id-badge"></i><span class="nav-text">Availability Slots</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $confActive }}" href="#">
                <i class="ti ti-settings"></i><span class="nav-text">Manage Admission</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $changecampActive }}" href="#">
                <i class="ti ti-brand-google-maps"></i><span class="nav-text">Change Campus</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $transferActive }}" href="#">
                <i class="ti ti-transfer"></i><span class="nav-text">Transfer Students</span>
            </a>
        </li>
    @endif

    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Reports</small></li>
    @if(!in_array(Auth::user()->role, [5, 6, 7]))
        <li>
            <a class="nav-link {{ $confActive }}" href="#">
                <i class="ti ti-file-star"></i><span class="nav-text">Applicants</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $appschoolreportActive }}" href="#">
                <i class="ti ti-file-barcode"></i><span class="nav-text">Applicants per School</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $schedreportActive }}" href="{{ route('schedules_printing') }}">
                <i class="ti ti-file-analytics"></i><span class="nav-text">Admission Schedules</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $noschedreportActive }}" href="#">
                <i class="ti ti-file-delta"></i><span class="nav-text">Applicants No Sched</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $examreportActive }}" href="#">
                <i class="ti ti-file-description"></i><span class="nav-text">Examination Results</span>
            </a>
        </li>
        
        <li>
            <a class="nav-link {{ $qualreportActive }}" href="#">
                <i class="ti ti-file-check"></i><span class="nav-text">Qualified Applicants</span>
            </a>
        </li>
    @endif

    <li>
        <a class="nav-link {{ $acceptedreportActive }}" href="#">
            <i class="ti ti-file-code"></i><span class="nav-text">Accepted Applicants</span>
        </a>
    </li>
    
    @if(in_array(Auth::user()->id, [1, 36]))  
        <li>
            <a class="nav-link {{ $courseprefreportActive }}" href="#">
                <i class="ti ti-file-code"></i><span class="nav-text">Course Preferences</span>
            </a>
        </li>
    @endif
    
    @if(!in_array(Auth::user()->role, [5, 6, 7]))  
        <li>
            <a class="nav-link {{ $billingreportActive }}" href="#">
                <i class="ti ti-file-code"></i><span class="nav-text">Billing</span>
            </a>
        </li>
    @endif
</ul>