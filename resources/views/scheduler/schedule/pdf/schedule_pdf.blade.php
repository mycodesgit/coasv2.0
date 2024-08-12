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
            font-size: 10px; /* Reduced font size */
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #000;
            padding: 1px; /* Reduced padding */
            text-align: center;
        }
        .schedule-table th {
            background-color: #e9ecef;
        }
        .schedule-table td {
            padding: 4px !important;
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
        /* Ensure the table fits within one page */
        @page {
            size: A4 landscape;
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
        @elseif(Auth::guard('web')->user()->campus == 'HinC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderhinC.png') }}" width="40%">
        @endif
    </div>
    <div class="header">
        <h5>Class Schedule: {{ $progAcronym }} {{ $progCodSuffix }}, School Year: {{ $schlyear }}, Semester: {{ $semester }}</h5>
    </div>
    <div>
        {!! $scheduleHtml !!}
    </div>
    <div class="footer">
        <span>Prepared By: ____________________</span>
        <span>Recommending Approval: ____________________</span>
        <span>Approved: ____________________</span>
    </div>
    <div class="footer">
        As of: {{ \Carbon\Carbon::now()->format('m/d/Y h:i:s A') }}
    </div>
</body>
</html>
