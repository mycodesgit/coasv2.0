@php
    $curr_route = request()->route()->getName();

    $dashEnActive = in_array($curr_route, ['enrollment-index']) ? 'active' : '';
    $studAddActive = in_array($curr_route, ['studentCreate']) ? 'active' : '';
    $searchStudActive = in_array($curr_route, ['searchStud', 'searchStudEnroll']) ? 'active' : '';
    $crossStudActive = in_array($curr_route, ['crosstudsearch', 'editcrosstudsearchRead']) ? 'active' : '';
    $searchStudEvalActive = in_array($curr_route, ['loadstudsub', 'loadstudsub_searchview', 'loadstudsubpreenrol_searchview']) ? 'active' : '';
    $editEnrollStudActive = in_array($curr_route, ['editsearchStud', 'editsearchStudRead']) ? 'active' : '';
    $editDupAppEnrollStudActive = in_array($curr_route, ['dupapprslSearch', 'dupapprslSearch_listresult']) ? 'active' : '';
    $stuEnrollmentHisActive = in_array($curr_route, ['studentEnHistory', 'viewsearchenStudHistory']) ? 'active' : '';
    $gradeStudActive = in_array($curr_route, ['studgrade_search', 'studgrade_gradsearch', 'studgrade_searchlist', 'studgradegrad_searchlist', 'geneStudent1']) ? 'active' : '';
    $gradeStudcorrectActive = in_array($curr_route, ['studgradecorrection_search', 'studgradecorrection_resultsearch', 'geneStudentcorrectiongrades']) ? 'active' : '';
    $studtransAllActive = in_array($curr_route, ['list_trans']) ? 'active' : '';
    $subjectAllActive = in_array($curr_route, ['subjectsRead']) ? 'active' : '';


    $studinfoActive = in_array($curr_route, ['studInfo', 'studInfo_search']) ? 'active' : '';
    $studinfogradActive = in_array($curr_route, ['studInfograduated', 'studInfograduated_search']) ? 'active' : '';
    $studRFActive = in_array($curr_route, ['rfstudprint', 'rfstudprintsearch']) ? 'active' : '';
    $studcurrActive = in_array($curr_route, ['studCurr', 'studCurrsearch']) ? 'active' : '';
    $studAttendcurrActive = in_array($curr_route, ['studAttendanceCurr', 'studAttendCurrsearch']) ? 'active' : '';
    $studsubjActive = in_array($curr_route, ['studsubjectsRead', 'listsearch_studsubjectsRead', 'listsearchview_studsubjectsRead']) ? 'active' : '';
    $studviewgrdeActive = in_array($curr_route, ['studviewgradeRead', 'search_studviewgradeRead', 'searchgradschool_studviewgradeRead']) ? 'active' : '';
    $reportcardActive = in_array($curr_route, ['reportCard_list', 'reportCard_listsearch']) ? 'active' : '';
    $elplActive = in_array($curr_route, ['elpl_list', 'elpl_listsearch']) ? 'active' : '';
    $rankActive = in_array($curr_route, ['ranking_list', 'ranking_listsearch']) ? 'active' : '';
    $numenrolledActive = in_array($curr_route, ['studnoenrollee', 'studnoenrollee_searchList']) ? 'active' : '';
    $numnstpenrolledActive = in_array($curr_route, ['studnoNSTPenrollee']) ? 'active' : '';
    $studevalActive = in_array($curr_route, ['studevalRead', 'studevalReadgradschool_listsearch', 'studevalRead_listsearch']) ? 'active' : '';
    $facgdeshtlgbokActive = in_array($curr_route, ['logbookindex', 'logbook_search']) ? 'active' : '';
    $studenpersemActive = in_array($curr_route, ['studenrollRead', 'search_studenrollRead']) ? 'active' : '';
    $updenrlmntlogsActive = in_array($curr_route, ['updateEnrlmntlogsRead', 'search_uptadeEnrlmntlogsRead']) ? 'active' : '';
    $encodeGradelogsActive = in_array($curr_route, ['searchEncode_grade', 'searchEncode_gradeRead']) ? 'active' : '';
    $delenrlmntlogsActive = in_array($curr_route, ['delenrlmntlogsRead', 'search_delenrlmntlogsRead']) ? 'active' : '';

@endphp

<ul class="nav flex-column">
    
    <li class="px-4 py-2"><small class="nav-text text-muted">Main Navigation</small></li>
    <li>
        <a class="nav-link {{ $dashEnActive }}" href="{{ route('enrollment-index') }}">
            <i class="ti ti-home"></i><span class="nav-text">Dashboard</span>
        </a>
    </li>

    @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 15]))
        <li>
            <a class="nav-link {{ $studAddActive }}" href="{{ route('studentCreate') }}">
                <i class="ti ti-user-plus"></i><span class="nav-text">Add Student</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 11, 12, 13, 14, 15]) && Auth::guard('web')->user()->lname != 'Movillion')
        <li>
            <a class="nav-link {{ $searchStudActive }}" href="{{ route('searchStud') }}">
                <i class="ti ti-device-laptop"></i><span class="nav-text">Enroll Student</span>
            </a>
        </li>

        <li>
            <a class="nav-link {{ $editEnrollStudActive }}" href="{{ route('editsearchStud') }}">
                <i class="ti ti-edit"></i><span class="nav-text">Edit Enrollment</span>
            </a>
        </li>
    @endif

    @if(Auth::guard('web')->user()->role == '0' || Auth::guard('web')->user()->lname == 'Gargoles')
        <li>
            <a class="nav-link {{ $editDupAppEnrollStudActive }}" href="{{ route('dupapprslSearch') }}">
                <i class="ti ti-receipt"></i><span class="nav-text">Edit Dup. Fees</span>
            </a>
        </li>
    @endif

    @if(Auth::guard('web')->user()->campus == 'MC')
        @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 5, 6, 15]))
            <li>
                <a class="nav-link {{ $searchStudEvalActive }}" href="{{ route('loadstudsub') }}">
                    <i class="ti ti-scoreboard"></i><span class="nav-text">Evaluated Student</span>
                </a>
            </li>
        @endif
    @endif

    <li>
        <a class="nav-link {{ $stuEnrollmentHisActive }}" href="{{ route('studentEnHistory') }}">
            <i class="ti ti-history"></i><span class="nav-text">Enrollment History</span>
        </a>
    </li>

    @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 13, 14, 15]))
        <li>
            <a class="nav-link {{ $gradeStudActive }}" href="{{ route('studgrade_search') }}">
                <i class="ti ti-file-spreadsheet"></i><span class="nav-text">Gradesheet</span>
            </a>
        </li>
    @endif
    
    @if(Auth::guard('web')->user()->role == '19' && Auth::guard('web')->user()->campus == 'MC')
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-file-spreadsheet"></i><span class="nav-text">Gradesheet</span>
            </a>
        </li>
    @endif
    
    @if(Auth::guard('web')->user()->role == '3' && Auth::guard('web')->user()->campus == 'MC')
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-file-spreadsheet"></i><span class="nav-text">Correction of Grades</span>
            </a>
        </li>
    @endif
    
    @if(Auth::guard('web')->user()->role ==0 || Auth::guard('web')->user()->lname == 'Arlos' || Auth::guard('web')->user()->lname == 'Gallardo' || Auth::guard('web')->user()->fname == 'Regielyn')
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-transfer"></i><span class="nav-text">Transfered Student</span>
            </a>
        </li>
    @endif
    
    @if(Auth::guard('web')->user()->role == 0 || Auth::guard('web')->user()->fname == 'Rosalie' || Auth::guard('web')->user()->lname == 'RAMADA' || Auth::guard('web')->user()->lname == 'Arquero' || Auth::guard('web')->user()->lname == 'Doronila')
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-book"></i><span class="nav-text">Subjects</span>
            </a>
        </li>
    @endif
    
    @if(Auth::guard('web')->user()->role != 19)
    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Reports</small></li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 1, 2, 3, 4, 13, 14]) || Auth::guard('web')->user()->lname == 'Gargoles')
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-user"></i><span class="nav-text">Student Information</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 15]) || Auth::guard('web')->user()->lname == 'Gargoles')
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-user"></i><span class="nav-text">Student Info</span>
            </a>
        </li>
    @endif

    @if(Auth::guard('web')->user()->role != 19)
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-printer"></i><span class="nav-text">Print Student RF</span>
            </a>
        </li>
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-file-type-pdf"></i><span class="nav-text">Student Per Degree</span>
            </a>
        </li>
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-file-type-pdf"></i><span class="nav-text">Attendance Per Degree</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 1, 2, 3, 4, 5, 6, 7, 12, 13, 14, 15, 20]))
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-file-type-pdf"></i><span class="nav-text">Class Attendance</span>
            </a>
        </li>
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-numbers"></i><span class="nav-text">View Student Grades</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 13, 14, 20]) || Auth::guard('web')->user()->lname == 'Gargoles')
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-file-type-pdf"></i><span class="nav-text">Students Report Card</span>
            </a>
        </li>
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-file-excel"></i><span class="nav-text">EL and PL</span>
            </a>
        </li>
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-military-rank"></i><span class="nav-text">Ranking</span>
            </a>
        </li>
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-checklist"></i><span class="nav-text">Number of Enrollees</span>
            </a>
        </li>
    @endif
    
    @if(in_array(Auth::guard('web')->user()->role, [0]))
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-brand-miniprogram"></i><span class="nav-text">Cwts/Lts/Rotc</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 3, 4, 13, 14, 15, 19, 20]))
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-book-2"></i><span class="nav-text">Student Record</span>
            </a>
        </li>
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-address-book"></i><span class="nav-text">Gradesheet Logbook</span>
            </a>
        </li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 3]))
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-registered"></i><span class="nav-text">Students Enrolled</span>
            </a>
        </li>
    @endif
    
    @if(in_array(Auth::guard('web')->user()->role, [0]) || Auth::guard('web')->user()->lname == 'Arlos')
    <li class="nav-text-space"><small class="nav-text"></small></li>
    <li class="px-4 py-2"><small class="nav-text text-muted">Logs</small></li>
    @endif

    @if(in_array(Auth::guard('web')->user()->role, [0, 3]))
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-logs"></i><span class="nav-text">Updated Enroll. Logs</span>
            </a>
        </li>
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-building-store"></i><span class="nav-text">Encoded Grades. Logs</span>
            </a>
        </li>
        <li>
            <a class="nav-link" href="#">
                <i class="ti ti-git-branch-deleted"></i><span class="nav-text">Deleted Enroll. Logs</span>
            </a>
        </li>
    @endif

</ul>