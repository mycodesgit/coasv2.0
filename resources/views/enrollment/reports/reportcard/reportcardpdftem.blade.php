<!DOCTYPE html>
<html>
<head>
    <title>Report Card</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Calibri !important;
        }
        table, th, td {
            /*border: 1px solid #e9ecef;*/
        }
        th, td {
            padding: 2px;
            text-align: left;
        }
        td {
            font-size: 9pt;
        }
        .studinfolabel {
            text-align: left;
            font-size: 10pt;
            font-family: sans-serif;
        }
        .studinfoID {
            margin-left: 30px;
        }
    </style>
</head>
<body>
    <div align="center" style="margin-top: -30px">
        <img src="{{ public_path('template/img/reportcardheaderMain.png') }}" width="80%">
    </div>

    <div class="studinfolabel" style="margin-top: 25px">
        <span style="font-weight: bold;">STUDENT ID NO.:</span> <span class="studinfoID"><strong> {{ $studrepcard->studentID }}</strong></span>
        <span style="font-weight: bold; text-align: right !important; margin-left: 300px;">DATE:</span> <span><strong>{{ strtoupper(\Carbon\Carbon::now()->format('F j, Y')) }}</strong></span>
    </div> 
    <div class="studinfolabel" style="margin-top: 5px">
        <span style="font-weight: bold;">COURSE:</span> <span class="">&nbsp;&nbsp;&nbsp; <strong>{{ $studrepcard->progName }}</strong></span>
    </div>
    <div class="studinfolabel" style="margin-top: 5px">
        <span style="font-weight: bold;">YEAR  & SECTION:</span> <span class="">&nbsp;&nbsp;&nbsp; <strong>{{ $studrepcard->studYear }}-{{ $studrepcard->studSec }}</strong></span>
    </div>

    {{-- <div class="studinfolabel" style="margin-top: 35px">
        <span style="font-weight: bold;">I hereby certify that:</span> <span class="studinfoID"> <strong>JUAN DELA CRUZ</strong></span>
        <span style="text-align: right !important; margin-left: 100px;">has completed the following subjects in the</span> <span class=""></span>
    </div>
    <div class="studinfolabel" style="">
        <span class=""> <strong>BACHELOR IN SUGAR TECHNOLOGY</strong></span>
        <span style="text-align: right !important; margin-left: 200px;" class="float-right">curriculum and earned the</span> <span class=""></span>
    </div> --}}

    <div class="" style="margin-top: 35px">
        <table>
            <thead>
                <th style="font-weight: bold; font-size: 10pt;">I hereby certify that: &nbsp;&nbsp;&nbsp; {{ $studrepcard->fname }} {{ strtoupper(substr($studrepcard->mname, 0, 1)) }}. {{ $studrepcard->lname }}</th>
                <th class="" style="text-align: right !important; font-size: 10pt; font-weight: initial; color: #000 !important;">has completed the following subjects in the</th>
            </thead>
            <thead>
                <th style="font-weight: bold; font-size: 10pt;">{{ $studrepcard->progName }}</th>
                <th class="" style="text-align: right !important; font-size: 10pt; font-weight: initial; color: #000 !important;">curriculum and earned the</th>
            </thead>
            <thead>
                <th colspan="2" style="text-align: left !important; font-size: 10pt; font-weight: initial; color: #000 !important;">creadits as indicated below during the School Year: &nbsp;&nbsp;&nbsp;&nbsp; {{ request('schlyear')}}</th>
            </thead>
        </table>
    </div>

    <div style="border-top: 1px solid #000; margin-top: 20px;"></div>
        <center style="margin-top: 10px;">
            <span>
                @if(request('semester') == '1')
                    FIRST SEMESTER
                @elseif(request('semester') == '2')
                    SECOND SEMESTER
                @elseif(request('semester') == '3')
                    SUMMER
                @else
                    Unknown Semester
                @endif
            </span>
        </center>
    <div style="border-top: 1px solid #000; margin-top: 10px;"></div>

    <div style="margin-top: 10px">
        <table>
            <thead>
                <th style="font-weight: bold; font-size: 10pt;" width="62%"><center>SUBJECTS</center></th>
                <th class="" style="font-weight: bold; font-size: 10pt;">FINAL<br> GRADE</th>
                <th class="" style="font-weight: bold; font-size: 10pt;">COMPL<br> GRADE</th>
                <th class="" style="font-weight: bold; font-size: 10pt;">CREDITS</th>
            </thead>
        </table>
    </div>
    <div style="border-top: 1px solid #000; margin-top: 10px;"></div>

    <div style="margin-top: 10px">
        <table>
            <thead>
                <th style="font-weight: bold; font-size: 10pt;"></th>
                <th class="" style="font-weight: bold; font-size: 10pt;"></th>
                <th class="" style="font-weight: bold; font-size: 10pt;" width="12%"></th>
                <th class="" style="font-weight: bold; font-size: 10pt;" width="12%"></th>
                <th class="" style="font-weight: bold; font-size: 10pt;" width="12%"></th>
            </thead>
            <tbody>
                @php 
                    function getEquivalentGrade($grade) {
                        if ($grade === 'INC') {
                            return ['gpa' => 'INC', 'status' => 'Incomplete'];
                        } elseif ($grade === 'NN') {
                            return ['gpa' => 'NN', 'status' => 'No Name'];
                        } elseif ($grade === 'NG') {
                            return ['gpa' => 'NG', 'status' => 'No Grade'];
                        } elseif ($grade === 'Drp..') {
                            return ['gpa' => 'Drp.', 'status' => 'Drop'];
                        } elseif ($grade >= 97 || $grade == 1) {
                            return ['gpa' => '1.0', 'status' => 'Passed'];
                        } elseif ($grade >= 94) {
                            return ['gpa' => '1.2', 'status' => 'Passed'];
                        } elseif ($grade >= 91) {
                            return ['gpa' => '1.5', 'status' => 'Passed'];
                        } elseif ($grade >= 88) {
                            return ['gpa' => '1.7', 'status' => 'Passed'];
                        } elseif ($grade >= 85 || $grade == 2) {
                            return ['gpa' => '2.0', 'status' => 'Passed'];
                        } elseif ($grade >= 82) {
                            return ['gpa' => '2.2', 'status' => 'Passed'];
                        } elseif ($grade >= 79) {
                            return ['gpa' => '2.5', 'status' => 'Passed'];
                        } elseif ($grade >= 76) {
                            return ['gpa' => '2.7', 'status' => 'Passed'];
                        } elseif ($grade >= 75 || $grade == 3) {
                            return ['gpa' => '3.0', 'status' => 'Passed'];
                        } elseif ($grade >= 70) {
                            return ['gpa' => '4.0', 'status' => 'Conditional'];
                        } else {
                            return ['gpa' => '5.0', 'status' => 'Failure'];
                        }
                    }

                    function displayGrade($grade) {
                        if (is_numeric($grade) && strpos($grade, '.') === false) {
                            $equivalent = getEquivalentGrade($grade);
                            return $equivalent['gpa'];
                        }
                        return $grade;
                    }
                    $totalCredits = 0;
                    $totalWeightedGrades = 0;
                @endphp
                @foreach($studrepcardsub as $datastudrepcardsub)
                @php
                // Accumulate totals
                $creditEarned = (float)$datastudrepcardsub->creditEarned;
                $subjFgrade = (float)$datastudrepcardsub->subjFgrade;
                $totalCredits += $creditEarned;
                $totalWeightedGrades += $subjFgrade * $creditEarned;
            @endphp
                <tr>
                    <td>{{ $datastudrepcardsub->sub_name }}</td>
                    <td>{{ $datastudrepcardsub->sub_title }}</td>
                    <td>{{ displayGrade($datastudrepcardsub->subjFgrade) }}</td>
                    <td>{{ displayGrade($datastudrepcardsub->subjComp) }}</td>
                    <td>{{ $datastudrepcardsub->creditEarned }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
        <tr>
            <th colspan="4" style="text-align:right;">Total Credits:</th>
            <th>{{ $totalCredits }}</th>
        </tr>
        <tr>
            <th colspan="4" style="text-align:right;">Total Weighted Grades:</th>
            <th>{{ $totalWeightedGrades }}</th>
        </tr>
    </tfoot>
        </table>

        <div style="margin-top: 10px">
            **********************************Nothing Follows**********************
        </div>
        <div>
            AVERAGE: {{ number_format($average, 6) }}
        </div>
        <div style="border-top: 1px solid #000; margin-top: 80px">
            <span style="font-size: 10pt; margin-top: 30px;"> Remarks: This is a system generated report. Valid for evaluation purposes only. </span>
        </div>

        <div style="margin-top: 60px;">
            <table>
                <thead>
                    <th style="font-weight: bold; font-size: 10pt;">NOT VALID WITHOUT</th>
                    <th style="font-weight: bold; font-size: 10pt; padding-left: 50px;">CERTIFIED CORRECT:</th>
                </thead>
                <thead>
                    <th colspan="2" style="font-weight: bold; font-size: 10pt;">SCHOOL SEAL</th>
                </thead>
                <thead>
                    <th colspan="2" style="font-weight: bold; font-size: 11pt; padding-left: 355px;">LOBRIQUE, RHONELO M., MPA<br><span style="padding-left: 30px;">University Registrar</span></th>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <div style="margin-top: 80px">
            <span style="font-size: 12pt; margin-top: 60px;"> Prepared By : {{ strtoupper(Auth::guard()->user()->fname) }} {{ strtoupper(substr(Auth::guard()->user()->mname, 0, 1)) }}. {{ strtoupper(Auth::guard()->user()->lname) }} </span>
        </div>
    </div>
</body>
</html>
