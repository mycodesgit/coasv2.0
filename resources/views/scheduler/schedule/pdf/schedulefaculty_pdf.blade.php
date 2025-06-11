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

        .details {			
			margin-left: 10px;
			text-align: left;
			font-size: 11pt;
		}
        .details-sm {			
			margin-left: 10px;
			text-align: left;
			font-size: 9pt;
		}
    </style>
</head>
<body>
    <div align="center" style="margin-top: -15px">
        @if(Auth::guard('web')->user()->campus == 'MC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderMain.png') }}" width="60%">
        @elseif(Auth::guard('web')->user()->campus == 'VC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderVC.png') }}" width="60%">
        @elseif(Auth::guard('web')->user()->campus == 'HinC')
            <img src="{{ public_path('template/img/schedclass/schedclassheaderHinC.png') }}" width="60%">
        @endif
    </div>

    <div class="header" style="margin-top: -15px">
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

            $vpres = $vice->fulname . ', ' . $vice->titledeg;
            $dean = $facDesignateId->fname . ' ' . substr($facDesignateId->mname, 0, 1) . '. ' . $facDesignateId->lname;
            $rankdean = $facDesignateId->rankcomma;
            $offdean = $facDesignateId->college_name = str_replace(' Of ', ' of ', ucwords(strtolower($facDesignateId->college_name)));
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

            @php $firstRow = true; @endphp

            <tbody>
                @foreach ($groupedFacloadsched as $sub_name => $schedules)
                    @foreach ($schedules as $index => $schedule)
                        <tr>
                            {{-- Subject Title and Description --}}
                            @if ($index === 0)
                                <td rowspan="{{ $schedules->count() }}">{{ $schedule->sub_name }}</td>
                                <td rowspan="{{ $schedules->count() }}">{{ $schedule->sub_title }}</td>
                            @endif

                            {{-- Section & Details --}}
                            <td>{{ $schedule->subSec }}</td>
                            <td>{{ $schedule->studentCount }}</td>
                            <td>{{ $schedule->sub_unit }}</td>
                            {{-- <td>{{ $schedule->sublecredit }}</td>
                            <td>{{ $schedule->sublabcredit }}</td>
                            <td>{{ $schedule->sublecredit + $schedule->sublabcredit }}</td> --}}
                            <td></td>
                            <td></td>
                            <td></td>

                            {{-- Show REMARKS only ONCE --}}
                            @if ($firstRow)
                                @php $firstRow = false; @endphp
                                <td rowspan="{{ collect($groupedFacloadsched)->flatten(1)->count() }}" class="remarks">
                                    Number of Preparations: <b>{{ count($groupedFacloadsched) }}</b><br><br>
                                    Total Subject Load Units: <br>
                                    Lecture: <b>{{ collect($groupedFacloadsched)->flatten(1)->sum('sub_unit') }}</b><br>
                                    Laboratory: <b>____</b><br><br>
                                    Total Contact Hours: <b>{{ collect($groupedFacloadsched)->flatten(1)->sum(fn($s) => $s->sublecredit + $s->sublabcredit) }}</b><br>
                                    Lecture: <b>{{ collect($groupedFacloadsched)->flatten(1)->sum('sublecredit') }}</b><br>
                                    Laboratory: <b>{{ collect($groupedFacloadsched)->flatten(1)->sum('sublabcredit') }}</b><br><br>
                                    Overtime Load: <b>(Hours/Units)</b><br>
                                    Consultation Hours: <b>___</b><br>
                                    {{-- Extension Coordinator: <b>9</b><br> --}}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endforeach
                @php
                    $flattened = collect($groupedFacloadsched)->flatten(1);
                    $totalStudents = $flattened->sum('studentCount');
                    $totalUnits = $flattened->sum('sub_unit');
                    $totalLec = $flattened->sum('sublecredit');
                    $totalLab = $flattened->sum('sublabcredit');
                    $totalContact = $totalLec + $totalLab;
                @endphp

                <tr>
                    <td colspan="3" class="text-right font-weight-bold">Total:</td>
                    <td><b>{{ $totalStudents }}</b></td>
                    <td><b>{{ $totalUnits }}</b></td>
                    {{-- <td><b>{{ $totalLec }}</b></td>
                    <td><b>{{ $totalLab }}</b></td>
                    <td><b>{{ $totalContact }}</b></td> --}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td> {{-- Leave Remarks column empty or put summary if needed --}}
                </tr>

            </tbody>

        </table>

    </div>
    {{-- <div style="margin-top: 10px;">
        <span style="font-size: 8pt;">Prepared By:</span>
    </div>
    <div style="margin-left: 20px; margin-top: 25px;">
        <span style="font-size: 9pt; font-weight: bold;">{{ $facultysigName }}</span>
    </div>
    <div style="margin-left: 20px; margin-top: -10px;">
        <span style="font-size: 8pt; font-weight: normal;">Signature over printed name</span>
    </div> --}}

    <div class="details-sm" style="margin-top: 15px;">
        <span style="display: inline-block; width: 80px; vertical-align: top;">Prepared by:</span>
        <span style="display: inline-block; width: 80px; vertical-align: top; margin-left: 150px;">Noted:</span>
        <span style="display: inline-block; width: 80px; vertical-align: top; margin-left: 190px;">Reveiwed by:</span>
    </div>

    <div class="details-sm" style="margin-top: 30px;">
        <div style="display: inline-block; margin-left: 5px; vertical-align: top; text-align: center; border-bottom: 1px solid black; width: 195px; font-size: 9pt !important">
            <span style="font-weight: bold; font-size: 9pt !important; text-transform: uppercase;">{{ $facultysigName }}</span>
        </div>
        <div style="text-align: center; width: 200px;">
            <span>Signature over printed name</span>
        </div>
    </div>

    <div class="details-sm" style="margin-top: -30px; margin-left: 240px;">
        <div style="display: inline-block; margin-left: 5px; vertical-align: top; text-align: center; border-bottom: 1px solid black; width: 195px; font-size: 9pt !important">
            <span style="font-weight: bold">&nbsp;</span>
        </div>
        <div style="text-align: center; width: 200px;">
            <span>Program Head</span>
        </div>
    </div>

    <div class="details-sm" style="margin-top: -30px; margin-left: 490px;">
        <div style="display: inline-block; margin-left: 5px; vertical-align: top; text-align: center; border-bottom: 1px solid black; width: 195px; font-size: 9pt !important">
            <span style="font-weight: bold">&nbsp;</span>
        </div>
        <div style="text-align: center; width: 200px;">
            <span>Dean for Instruction/Authorized Person</span>
        </div>
    </div>

    <div class="details-sm" style="margin-top: 15px;">
        <span style="display: inline-block; width: 150px; vertical-align: top;">Recommending Approval:</span>
        <span style="display: inline-block; width: 150px; vertical-align: top; margin-left: 150px;">Approved:</span>
    </div>

    <div class="details-sm" style="margin-top: 30px;">
        <div style="display: inline-block; margin-left: 5px; vertical-align: top; text-align: center; border-bottom: 1px solid black; width: 195px; font-size: 9pt !important">
            <span style="font-weight: bold; font-size: 9pt !important; text-transform: uppercase;">
                @if(Auth::guard('web')->user()->campus == 'MC')
                    {{ $dean }}
                @else
                    &nbsp;
                @endif
            </span>, 
            <span style="font-weight: bold; font-size: 9pt !important">
                @if(Auth::guard('web')->user()->campus == 'MC')
                    {{ $rankdean }}
                @else
                    &nbsp;
                @endif
            </span>
        </div>
        <div style="text-align: center; width: 200px;">
            <span style="font-size: 9pt !important">Dean, {{ $offdean }}</span>
        </div>
    </div>

    <div class="details-sm" style="margin-top: -30px; margin-left: 240px;">
        <div style="display: inline-block; margin-left: 5px; vertical-align: top; text-align: center; border-bottom: 1px solid black; width: 195px; font-size: 9pt !important">
            <span style="font-weight: bold">{{ $vpres }}</span>
        </div>
        <div style="text-align: center; width: 200px;">
            <span>Vice President for Academic Affairs</span>
        </div>
    </div>

    <div style="text-align: center !important; font-size: 8pt; margin-top: 15px; display: flex; justify-content: space-between; width: 100%;">
		<span>Doc Control Code: CPSU-F-VPAA-03 REV</span>
		<span style="margin-left: 40px;">Effective Date: 08/16/2022</span>
		<span style="margin-left: 40px;">Page No.: <b>1</b> of <b>1</b></span>
	</div>

    {{-- <div style=" margin-top: -30px; margin-right: 0px;">
        <div class="details-sm">
            <div style="display: inline-block; vertical-align: top; text-align: center; border-bottom: 1px solid black; width: 195px;">
                <span style="font-weight: bold">&nbsp;</span>
            </div>
            <div style="text-align: center; width: 200px;">
                <span>Signature over printed name</span>
            </div>
        </div>
    </div> --}}


    {{-- <div class="footer">
        As of: {{ \Carbon\Carbon::now()->format('m/d/Y h:i:s A') }}
    </div> --}}
</body>
</html>
