<!DOCTYPE html>
<html>
<head>
    <title>Curriculumn</title>
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
            font-size: 9pt; /* Reduced font size */
        }
        .schedule-table th, .schedule-table td {
            border: 1px solid #000;
            padding: 1px; /* Reduced padding */
        }
        .schedule-table th {
            background-color: #cdd6df;
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
    </style>
</head>
<body>
    <div class="header">
        <h4 class="text-bold">
            {{ $cpn->progName }}
        </h4>
    </div>

    <div style="padding-left: 20px; padding-right:20px">
        @php
            $yearLevels = $groupedCurr->keys()->toArray();
            sort($yearLevels); // Ensure years are in order (e.g., 1,2,3)
        @endphp

        @foreach ($yearLevels as $yearLevel)
            <br>
            <div class="year-header">
                {{ $yearLevel == 1 ? 'FIRST YEAR' : ($yearLevel == 2 ? 'SECOND YEAR' : ($yearLevel == 3 ? 'THIRD YEAR' : ($yearLevel == 4 ? 'FOURTH YEAR' : 'YEAR LEVEL ' . $yearLevel))) }}
            </div>

            {{-- First Semester --}}
            @if ($groupedCurr[$yearLevel]->has(1))
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="semester-header">FIRST SEMESTER</th>
                        </tr>
                        <tr>
                            <th>Grade/Course No</th>
                            <th>Descriptive Title</th>
                            <th>Units</th>
                            <th style="width: 90px">PR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedCurr[$yearLevel][1] as $item)
                            <tr>
                                <td>{{ $item->sub_name }}</td>
                                <td>{{ $item->sub_title }}</td>
                                <td>{{ $item->sub_unit }}</td>
                                <td style="text-align: right">{{ $item->prerequisite_name ?? $item->prerequisite ?? '' }}</td> 
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"><strong>TOTAL</strong></td>
                            <td>{{ $groupedCurr[$yearLevel][1]->sum('sub_unit') }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @endif
            <br>
            {{-- Second Semester --}}
            @if ($groupedCurr[$yearLevel]->has(2))
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th colspan="4" class="semester-header">SECOND SEMESTER</th>
                        </tr>
                        <tr>
                            <th>Grade/Course No</th>
                            <th>Descriptive Title</th>
                            <th>Units</th>
                            <th style="width: 90px">PR</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($groupedCurr[$yearLevel][2] as $item)
                            <tr>
                                <td>{{ $item->sub_name }}</td>
                                <td>{{ $item->sub_title }}</td>
                                <td>{{ $item->sub_unit }}</td>
                                <td style="text-align: right">{{ $item->prerequisite_name ?? $item->prerequisite ?? '' }}</td> 
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="2"><strong>TOTAL</strong></td>
                            <td>{{ $groupedCurr[$yearLevel][2]->sum('sub_unit') }}</td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            @endif
        @endforeach
    </div>
</body>
</html>
