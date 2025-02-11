<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Attendance</title>

	<style>
		.tablehead{
			border: none !important;
		}
	    table {
	        width: 100%;
	        border-collapse: collapse;
	        font-size: 10pt;
	    }
	    th {
	        border: 1px solid black;
	        text-align: center;
	        padding: 8px;
	    }
	    td {
	        border: 1px solid black;
	        text-align: left;
	        padding: 8px;
	    }
	    .studinfolabel {
            text-align: left;
            font-size: 12pt;
            font-family: sans-serif;
            margin-top: 25px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .info-label, .info-data {
            margin-right: 20px;
        }
        .info-label {
            font-weight: normal;
        }
        .info-data {
            text-decoration: underline;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        .footer img {
            width: 70%;
        }
	</style>
</head>
<body>
	<div>
		<div align="center" style="margin-top: -30px">
	        <img src="{{ public_path('template/img/studsub/attendanceheader.png') }}" width="70%">
	    </div>
	    <div class="studinfolabel">
		    <div class="info-row">
		        <span class="info-label">Subjects:</span>
		        <span class="info-data">{{ $substudnowviewpdf->first()->sub_name ?? '' }} - {{ Str::limit($substudnowviewpdf->first()->sub_title ?? '', 16) }}</span>
		        <span class="info-label">Course:</span>
		        <span class="info-data">{{ $substudnowviewpdf->first()->subSec ?? '' }}</span>
		        <span class="info-label">Date:</span>
		        <span class="info-data1">_________</span>
		    </div>
		    <div class="info-row">
		        <span class="info-label">Time Schedule:</span>
		        @if(Auth::guard('faculty')->check() && Auth::guard('faculty')->user()->role == 943)
				    <span class="info-data1" style="text-decoration: underline;">__________</span>
				@elseif(Auth::guard('web')->check() || Auth::guard('web')->user()->role == 15)
				    <span class="info-data1" style="text-decoration: underline;">{{ $substudnowviewpdf->first()->isType ?? ' ' }}</span>
				@else
				    <span class="info-data1" style="text-decoration: underline;">___________</span>
				@endif

		        <span class="info-label" style="margin-left: 20px !important">Faculty in-charge:</span>
		        <span class="info-data1">______________________________</span>
		    </div>
		</div>

	    <div style="margin-top: 15px;">
			<table>
		        <thead>
		            <tr>
		                <th>No.</th>
		                <th>Name <br><span style="font-style: italic !important;">(Last, First, Middle Initial)</span></th>
		                <th>Signature</th>
		                <th>No.</th>
		                <th>Name <br><span style="font-style: italic !important;">(Last, First, Middle Initial)</span></th>
		                <th>Signature</th>
		            </tr>
		        </thead>
		        <tbody>
		            @php
		                $leftNumber = 1; // Counter for the left column
		                $rightNumber = 26; // Counter for the right column
		                $totalRows = 25; // Total rows to display (per side)
		                $studentCount = count($substudnowviewpdf); // Total number of students
		                $halfway = ceil($studentCount / 2); // Midpoint for splitting students
		            @endphp

		            @for ($i = 0; $i < $totalRows; $i++)
		                <tr>
		                    <td>{{ $leftNumber++ }}</td>
		                    @if (isset($substudnowviewpdf[$i]))
		                        @php
		                            $student = $substudnowviewpdf[$i];
		                        @endphp
		                        <td>{{ $student->lname }}, {{ $student->fname }} {{ strtoupper(substr($student->mname, 0, 1)) }} {{ strtoupper(substr($student->ext, 0, 1)) }}</td>
		                    @else
		                        <td></td>
		                    @endif
		                    <td></td>

		                    <td>{{ $rightNumber++ }}</td>
		                    @if (isset($substudnowviewpdf[$i + $totalRows]))
		                        @php
		                            $student = $substudnowviewpdf[$i + $totalRows];
		                        @endphp
		                        <td>{{ $student->lname }}, {{ $student->fname }} {{ strtoupper(substr($student->mname, 0, 1)) }}</td>
		                    @else
		                        <td></td>
		                    @endif
		                    <td></td>
		                </tr>
		            @endfor
		        </tbody>
		    </table>
		</div>
		<div style="margin-top: 10px">
			<span style="font-size: 12pt;">Remarks: __________________________________________________________________________________</span>
			<span style="font-size: 12pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;__________________________________________________________________________________</span>
			<span style="font-size: 12pt;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;__________________________________________________________________________________</span>
		</div>
		<div class="footer">
		    <img src="{{ public_path('template/img/studsub/attendancefooter.png') }}">
		</div>
    </div>
</body>
</html>