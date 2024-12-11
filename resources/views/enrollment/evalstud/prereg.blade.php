<!DOCTYPE html>
<html>
<head>
    <title>Schedule PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 0px;
        }
        .schedule-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px; /* Reduced font size */
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #000;
            padding: 1px; /* Reduced padding */
        }
        .schedule-table th {
            background-color: #e9ecef;
        }
        .schedule-table td {
            padding: 4px !important;
            text-align: left;
        }
        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 8px;
        }
        .footer span {
            display: inline-block;
            width: 30%;
        }
        .studinfolabel {
            text-align: left;
            font-size: 10pt;
            font-family: sans-serif;
        }
        /* Ensure the table fits within one page */
        @page {
            size: letter portrait;
            margin: 5mm;
            height: 50%; /* Half the height of the page */
            width: 100%;
        }
        @media print {
            html, body {
                width: 297mm;
                height: 210mm;
            }
            .schedule-table {
                width: 100%;
                font-size: 8px; /* Adjust font size as needed */
            }
        }
    </style>
</head>
<body>
    <div align="center" style="margin-top: -15px">
        @if(Auth::guard('web')->user()->campus == 'MC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderMain.png') }}" width="60%">
        @elseif(Auth::guard('web')->user()->campus == 'VC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderVC.png') }}" width="40%">
        @elseif(Auth::guard('web')->user()->campus == 'CC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderCC.png') }}" width="40%">
        @elseif(Auth::guard('web')->user()->campus == 'HinC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderHinC.png') }}" width="40%">
        @endif
    </div>

    <div class="header">
        <h4 class="text-bold">PRE-REGISTRATION FORM/SUBJECT ADVISING FORM</h4>
    </div>

    <div class="studinfolabel" style="padding-left: 20px; padding-right:20px">
        <span style="font-weight: bold;">ID #: </span> <span class="studinfoID" style="text-decoration: underline;">{{ request('stud_id') }}</span>
        <span style="font-weight: bold; margin-left: 10px;">Gender: </span> <span class="studinfoID" style="text-decoration: underline;">{{ $student->gender }}</span>
        <span style="font-weight: bold; margin-left: 10px;">Course: </span> <span class="studinfoID" style="text-decoration: underline;">
            {{ $student->progAcronym }} {{ $student->studYear }}-{{ $student->studSec }}
        </span>
        <span style="font-weight: bold; margin-left: 10px;">Year: </span> <span class="studinfoID" style="text-decoration: underline;">
            {{ $student->studYear }}-{{ $student->studSec }}
        </span>
        <span style="font-weight: bold; margin-left: 10px;">Sem: </span> <span class="studinfoID" style="text-decoration: underline;">
            {{ request('semester') }}
        </span>
        <span style="font-weight: bold; margin-left: 10px;">Civil Status: </span> <span class="studinfoID" style="text-decoration: underline;">
            {{ $student->civil_status }}
        </span>
    </div>

    <div class="studinfolabel" style="padding-left: 20px; padding-right:20px; margin-top: 10px;">
        <span style="font-weight: bold;">Surname: </span> <span class="studinfoID" style="text-decoration: underline;">{{ $student->lname }}</span>
        <span style="font-weight: bold; margin-left: 30px;">First Name: </span> <span class="studinfoID" style="text-decoration: underline;">{{ $student->fname }}</span>
        <span style="font-weight: bold; margin-left: 50px;">Middle Name: </span> <span class="studinfoID" style="text-decoration: underline;">{{ $student->mname }}</span>
    </div>

    <div class="studinfolabel" style="padding-left: 20px; padding-right:20px; margin-top: 10px;">
        <span style="font-weight: bold;">Address: </span> <span class="studinfoID" style="">_______________________</span>
        <span style="font-weight: bold; margin-left: 30px;">Email: </span> <span class="studinfoID" style="">_______________</span>
        <span style="font-weight: bold; margin-left: 30px;">Mobile #: </span> <span class="studinfoID" style="">________________</span>
    </div>

    <div style="margin-top: 50px; font-family: Calibri, sans-serif, arial;">
        <center><p>Subjects to be Taken:</p></center>
    </div>

    <div style="padding-left: 20px; padding-right:20px">
        <table id="table-inside" class="schedule-table">
            <thead>
                <tr>
                    <th width="15%">Code</th>
                    <th>Section</th>
                    <th width="50%">Description</th>
                    <th width="7%">Unit</th>
                    <th width="7%">Time/Day</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalsubUnit = 0;
                    $totalLecFee = 0;
                    $totalLabFee = 0;
                @endphp
                @foreach($studsub as $sub)
                    <tr>
                        <td>{{ $sub->sub_name }}</td>
                        <td>{{ $sub->subSec }}</td>
                        <td>{{ $sub->sub_title }}</td>
                        <td>{{ $sub->subUnit }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <span style="font-size: 9pt">Signature over printed name:</span>
    </div>

    <div class="studinfolabel" style="padding-left: 20px; padding-right:20px; margin-top: 30px;">
        <span style="font-weight: bold;"></span> <span class="studinfoID" style="">___________________________</span>
        <span style="font-weight: bold;"></span> <span class="studinfoID" style="margin-left: 80px;">___________________________</span>
        <span style="font-weight: bold;"></span> <span class="studinfoID" style="margin-left: 30px;">___________________________</span>
    </div>
    <div class="studinfolabel" style="padding-left: 20px; padding-right:20px; margin-left: 80px;">
        <span style="font-weight: bold;"></span> <span class="studinfoID" style="">Student</span>
        <span style="font-weight: bold;"></span> <span class="studinfoID" style="margin-left: 150px;">Dean, College of Computer Studies</span>
        <span style="font-weight: bold;"></span> <span class="studinfoID" style="margin-left: 100px;">Registrar</span>
    </div>

    <div style="margin-top: 15px; text-align: center; font-size: 9pt; font-family: Calibri, sans-serif, arial;">
        Doc Control Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPSU-F-REG-011&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Effective Date::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9/12/2018&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Revision No.:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1 of 1
    </div>
</body>
</html>
