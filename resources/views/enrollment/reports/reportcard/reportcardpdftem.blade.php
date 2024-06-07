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
        <img src="{{ asset('template/img/reportcardheaderMain.png') }}" width="80%">
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
                <th style="font-weight: bold; font-size: 10pt;">I hereby certify that: &nbsp;&nbsp;&nbsp; JUAN DELA CRUZ</th>
                <th class="" style="text-align: right !important; font-size: 10pt; font-weight: initial; color: #000 !important;">has completed the following subjects in the</th>
            </thead>
            <thead>
                <th style="font-weight: bold; font-size: 10pt;">BACHELOR IN SUGAR TECHNOLOGY</th>
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
                @foreach($studrepcardsub as $datastudrepcardsub)
                <tr>
                    <td>{{ $datastudrepcardsub->sub_name }}</td>
                    <td>{{ $datastudrepcardsub->sub_title }}</td>
                    <td>{{ number_format($datastudrepcardsub->subjFgrade, 1) }}</td>
                    <td>{{ $datastudrepcardsub->subjComp }}</td>
                    <td>{{ $datastudrepcardsub->creditEarned }}</td>
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
