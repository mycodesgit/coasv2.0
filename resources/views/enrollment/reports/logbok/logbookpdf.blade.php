<!DOCTYPE html>
<html>
<head>
    <title>Student Subject Load</title>
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
            size: legal landscape;
            margin: 5mm;
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
            <img src="{{ public_path('template/img/schedclass/schedclassheaderMain.png') }}" width="40%">
        @elseif(Auth::guard('web')->user()->campus == 'VC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderVC.png') }}" width="40%">
        @elseif(Auth::guard('web')->user()->campus == 'CC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderCC.png') }}" width="40%">
        @elseif(Auth::guard('web')->user()->campus == 'HinC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderHinC.png') }}" width="40%">
        @endif
    </div>

    <div class="header">
        <h4 class="text-bold">Gradesheet Logbook {{ request('schlyear') }} - 
            @if(request('semester') == '1')
                FIRST SEMESTER
            @elseif(request('semester') == '2')
                SECOND SEMESTER
            @elseif(request('semester') == '3')
                SUMMER
            @else
                Unknown Semester
            @endif
        </h4>
    </div>

    <div style="padding-left: 20px; padding-right:20px">
        <table id="table-inside" class="schedule-table">
            <thead>
                <tr>
                    <th>Faculty</th>
                    <th>Subject</th>
                    <th>Subject Title</th>
                    <th>Curr/Yr/Sec</th>
                    <th>College</th>
                    <th width="15%">Signature</th>
                </tr>
            </thead>
            <tbody>
                @foreach($gslog as $datalogbook)
                    <tr>
                        <td>{{ $datalogbook->lname }}</td>
                        <td>{{ $datalogbook->sub_name }}</td>
                        <td>{{ $datalogbook->sub_title }}</td>
                        <td>{{ $datalogbook->subSec }}</td>
                        <td>{{ $datalogbook->dept }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
