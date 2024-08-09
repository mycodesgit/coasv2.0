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
            font-size: 9px; /* Reduced font size */
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #000;
            padding: 1px; /* Reduced padding */
            text-align: center;
        }
        .schedule-table th {
            background-color: #e9ecef;
            padding: 5px !important;
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
            size: Legal portrait;
            margin: 12mm;
        }
        @media print {
            html, body {
                width: 216mm;
                height: 330mm;
            }
            .schedule-table {
                width: 100%;
                font-size: 8px; /* Adjust font size as needed */
            }
        }

        .load-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px; /* Reduced font size */
        }
        .load-table th, .load-table td {
            border: 1px solid #000;
            padding: 1px; /* Reduced padding */
        }
        .load-table th {
            background-color: #fff;
            padding: 5px !important;
        }

        .studinfolabel {
            text-align: left;
            font-size: 10pt;
            font-family: calibri;
        }
        .studinfoID {
            margin-left: 10px;
            font-size: 10pt;
            font-family: calibri;
        }
    </style>
</head>
<body>
    <div align="center" style="margin-top: -5px">
        @if(Auth::guard('web')->user()->campus == 'MC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderMain.png') }}" width="60%">
        @elseif(Auth::guard('web')->user()->campus == 'VC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderVC.png') }}" width="60%">
        @elseif(Auth::guard('web')->user()->campus == 'HinC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderhinC.png') }}" width="60%">
        @endif
    </div>

    <div class="header">
        <h5>INDIVIDUAL FACULTY WORKLOAD</h5>
        @php
            function formatSemester($semester) {
                $number = preg_replace('/[^0-9]/', '', $semester);
                $suffix = preg_replace('/[0-9]/', '', $semester);
                switch($number) {
                    case '1':
                        $suffix = 'st';
                        break;
                    case '2':
                        $suffix = 'nd';
                        break;
                    case '3':
                        $suffix = 'rd';
                        break;
                    default:
                        $suffix = 'th';
                }
                return $number . '<sup>' . $suffix . '</sup>';
            }
            $formattedSemester = formatSemester($semester);
        @endphp

        <h6 style="margin-top: -20px; font-style: italic;">
            {!! $formattedSemester !!} Semester SY <span style="text-decoration: underline;">{{ $schlyear }}</span>
        </h6>
    </div>

    <div class="studinfolabel">
        <span style="font-weight: bold;">Name:</span> <span class="studinfoID" style="font-weight: bold; text-decoration: underline;"> {{ $facultyName }}</span>
        <span style="font-weight: bold; margin-left: 200px;">Academic Rank:</span> <span class="studinfoID" style="font-weight: bold;"> _______________________</span>
    </div>

    <div style="margin-top: 10px">
        {!! $scheduleHtml !!}
    </div>

    <div style="margin-top: 5px">
        <table class="load-table">
            <thead>
                <tr>
                    <th rowspan="2" width="10%">COURSE CODE</th>
                    <th rowspan="2" width="20%">COURSE DESCRIPTION</th>
                    <th rowspan="2" width="15%">COURSE/YEAR & SECTION</th>
                    <th rowspan="2" width="10%">NUMBER of STUDENTS</th>
                    <th rowspan="2" width="10%">NUMBER of UNITS</th>
                    <th colspan="3" width="20%">CONTACT HOURS</th>
                    <th rowspan="2" width="15%">REMARKS</th>
                </tr>
                <tr>
                    <th>LEC</th>
                    <th>LAB</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedFacloadsched as $sub_name => $schedules)
                    @foreach ($schedules as $index => $schedule)
                        <tr>
                            @if ($index === 0)
                                <!-- Only display these columns on the first row of each group -->
                                <td rowspan="{{ $schedules->count() }}">{{ $schedule->sub_name }}</td>
                                <td rowspan="{{ $schedules->count() }}">{{ $schedule->sub_title }}</td>
                            @endif
                            
                            <!-- Display data for each section -->
                            <td>{{ $schedule->subSec }}</td>
                            <td>{{ $schedule->studentCount }}</td>
                            <td>{{ $schedule->sub_unit }}</td>
                            <td>{{ $schedule->sublecredit }}</td>
                            <td>{{ $schedule->sublabcredit }}</td>
                            <td>{{ $schedule->sublecredit + $schedule->sublabcredit }}</td>
                            
                            <!-- Make this  display in one row even i have more sub_name -->
                            {{-- @if ($index === 0)
                                <td rowspan="{{ $schedules->count() }}" class="remarks">
                                    Number of Preparations: <b>{{ $schedules->count() }}</b><br>
                                    <br>
                                    Total Subject Load Units: <b>{{ $schedules->sum('sub_unit') }}</b><br>
                                    Lecture: <b>{{ $schedules->sum('sublecredit') }}</b><br>
                                    Laboratory: <b>{{ $schedules->sum('sublabcredit') }}</b><br>
                                    <br>
                                    Total Contact Hours: <b>{{ $schedules->sum(function($s) { return $s->sublecredit + $s->sublabcredit; }) }}</b><br>
                                    Lecture: <b>{{ $schedules->sum('sublecredit') }}</b><br>
                                    Laboratory: <b>{{ $schedules->sum('sublabcredit') }}</b><br>
                                    <br>
                                    Overtime Load: <b>(Hours/Units)</b><br>
                                    Consultation Hours: <b>1 hr.</b><br>
                                    Extension Coordinator: <b>9</b><br>
                                </td>
                            @endif --}}
                            <td></td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

    </div>
    <div class="footer">
        <span>Prepared By: ____________________</span>
        <span>Recommending Approved: ____________________</span>
        <span>Approved: ____________________</span>
    </div>
    <div class="footer">
        As of: {{ \Carbon\Carbon::now()->format('m/d/Y h:i:s A') }}
    </div>
</body>
</html>
