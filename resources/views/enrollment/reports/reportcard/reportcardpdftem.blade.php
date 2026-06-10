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
        @if(Auth::guard('web')->user()->campus == 'MC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderMain.png') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'VC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderVic.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'SCC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderSCC.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'HC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderHC.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'MP')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderMP.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'IC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderIC.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'CC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderCC.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'CA')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderCA.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'SC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderSC.jpg') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'HinC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderHinC.jpg') }}" width="80%">
        @endif
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
                <th style="font-weight: bold; font-size: 10pt;">I hereby certify that: &nbsp;&nbsp;&nbsp; {{ $studrepcard->fname }} {{ strtoupper(substr($studrepcard->mname, 0, 1)) }}. {{ $studrepcard->lname }} {{ $studrepcard->ext }}</th>
                <th class="" style="text-align: right !important; font-size: 10pt; font-weight: initial; color: #000 !important;">has completed the following subjects in the</th>
            </thead>
            <thead>
                <th style="font-weight: bold; font-size: 10pt;">{{ $studrepcard->progName }}</th>
                <th class="" style="text-align: right !important; font-size: 10pt; font-weight: initial; color: #000 !important;">curriculum and earned the</th>
            </thead>
            <thead>
                <th colspan="2" style="text-align: left !important; font-size: 10pt; font-weight: initial; color: #000 !important;">credits as indicated below during the School Year: &nbsp;&nbsp;&nbsp;&nbsp; {{ request('schlyear')}}</th>
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
                <tr>
                    <th style="font-weight: bold; font-size: 10pt;"></th>
                    <th class="" style="font-weight: bold; font-size: 10pt;"></th>
                    <th class="" style="font-weight: bold; font-size: 10pt;" width="12%"></th>
                    <th class="" style="font-weight: bold; font-size: 10pt;" width="12%"></th>
                    <th class="" style="font-weight: bold; font-size: 10pt;" width="12%"></th>
                    {{-- <th class="" style="font-weight: bold; font-size: 10pt;" width="12%">Weighted Sum</th> --}}
                </tr>
            </thead>
            <tbody>
                @php 
                    function getEquivalentGPA($grade, $isOldSystem) {
                        if ($grade === 'INC') return ['gpa' => 'INC', 'status' => 'Incomplete'];
                        if ($grade === 'NN') return ['gpa' => 'NN', 'status' => 'No Name'];
                        if ($grade === 'NG') return ['gpa' => 'NG', 'status' => 'No Grade'];
                        if ($grade === 'Drp.') return ['gpa' => 'Drp.', 'status' => 'Drop'];

                        if ($isOldSystem) {
                            // 4-point scale logic
                            if ($grade >= 95 || $grade == 1) return ['gpa' => '1.0', 'status' => 'Passed'];
                            if ($grade >= 94) return ['gpa' => '1.1', 'status' => 'Passed'];
                            if ($grade >= 93) return ['gpa' => '1.2', 'status' => 'Passed'];
                            if ($grade >= 92) return ['gpa' => '1.3', 'status' => 'Passed'];
                            if ($grade >= 91) return ['gpa' => '1.4', 'status' => 'Passed'];
                            if ($grade >= 90) return ['gpa' => '1.5', 'status' => 'Passed'];
                            if ($grade >= 89) return ['gpa' => '1.6', 'status' => 'Passed'];
                            if ($grade >= 88) return ['gpa' => '1.7', 'status' => 'Passed'];
                            if ($grade >= 87) return ['gpa' => '1.8', 'status' => 'Passed'];
                            if ($grade >= 86) return ['gpa' => '1.9', 'status' => 'Passed'];
                            if ($grade >= 85 || $grade == 2) return ['gpa' => '2.0', 'status' => 'Passed'];
                            if ($grade >= 84) return ['gpa' => '2.1', 'status' => 'Passed'];
                            if ($grade >= 83) return ['gpa' => '2.2', 'status' => 'Passed'];
                            if ($grade >= 82) return ['gpa' => '2.3', 'status' => 'Passed'];
                            if ($grade >= 81) return ['gpa' => '2.4', 'status' => 'Passed'];
                            if ($grade >= 80) return ['gpa' => '2.5', 'status' => 'Passed'];
                            if ($grade >= 79) return ['gpa' => '2.6', 'status' => 'Passed'];
                            if ($grade >= 78) return ['gpa' => '2.7', 'status' => 'Passed'];
                            if ($grade >= 77) return ['gpa' => '2.8', 'status' => 'Passed'];
                            if ($grade >= 76) return ['gpa' => '2.9', 'status' => 'Passed'];
                            if ($grade >= 75 || $grade == 3) return ['gpa' => '3.0', 'status' => 'Passed'];
                            if ($grade >= 74) return ['gpa' => '4.0', 'status' => 'Conditional'];
                            if ($grade >= 73) return ['gpa' => '4.0', 'status' => 'Conditional'];
                            if ($grade >= 72) return ['gpa' => '4.0', 'status' => 'Conditional'];
                            if ($grade >= 71) return ['gpa' => '4.0', 'status' => 'Conditional'];
                            if ($grade >= 70) return ['gpa' => '4.0', 'status' => 'Conditional'];
                            return ['gpa' => '5.0', 'status' => 'Failure'];
                        } else {
                            if ($grade >= 97 || $grade == 1) return ['gpa' => '1.00', 'status' => 'Passed'];
                            if ($grade >= 94) return ['gpa' => '1.25', 'status' => 'Passed'];
                            if ($grade >= 91) return ['gpa' => '1.50', 'status' => 'Passed'];
                            if ($grade >= 88) return ['gpa' => '1.75', 'status' => 'Passed'];
                            if ($grade >= 85 || $grade == 2) return ['gpa' => '2.00', 'status' => 'Passed'];
                            if ($grade >= 82) return ['gpa' => '2.25', 'status' => 'Passed'];
                            if ($grade >= 79) return ['gpa' => '2.50', 'status' => 'Passed'];
                            if ($grade >= 76) return ['gpa' => '2.75', 'status' => 'Passed'];
                            if ($grade >= 75 || $grade == 3) return ['gpa' => '3.00', 'status' => 'Passed'];
                            if ($grade >= 70) return ['gpa' => '4.00', 'status' => 'Conditional'];
                            return ['gpa' => '5.00', 'status' => 'Failure'];
                        }
                    }

                    function displayGrade($grade, $isOldSystem = false) {
                        if (is_numeric($grade) && strpos($grade, '.') === false) {
                            $equivalent = getEquivalentGPA($grade, $isOldSystem); // Assuming old system for this example
                            return $equivalent['gpa'];
                        }
                        return $grade;
                    }
                @endphp
                @foreach($subjectsData as $data)
                    <tr>
                        <td>{{ $data['subject']->sub_name }}</td>
                        <td>{{ $data['subject']->sub_title }}</td>
                        <td>{{ displayGrade($data['subject']->subjFgrade) }}</td>
                        <td>{{ displayGrade($data['subject']->subjComp) }}</td>
                        <td>
                            @if($data['isNSTP'])
                                ({{ $data['subject']->creditEarned }})
                            @else
                                {{ $data['subject']->creditEarned }}
                            @endif
                        </td>
                        {{-- <td>{{ $data['weightedSumPerSubject'] }}</td> --}}
                    </tr>
                @endforeach
            </tbody>
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
                    <th colspan="2" style="font-weight: bold; font-size: 11pt; padding-left: 380px;">
                        @if(Auth::guard('web')->user()->campus == 'MC')
                            ELYSA JANE S. ARLOS<br><span style="padding-left: 1px;">OIC - University Registrar</span>
                        @elseif(Auth::guard('web')->user()->campus == 'VC')
                            JUNO E. PAJARILLO, DPA<br><span style="padding-left: 30px;">Campus Registrar</span>
                        @elseif(Auth::guard('web')->user()->campus == 'SCC')
                            JUJE C. RAMADA, LPT, Ph. D., <br><span style="padding-left: 30px;">Asst. Prof. / Campus Registrar</span>
                        @endif
                    </th>
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
