<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Faculty Load</title>

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
            margin-right: 0px;
        }
        .info-label {
            font-weight: normal;
        }
        .info-data {
            text-decoration: none;
            font-weight: bold;
        }
	</style>
</head>
<body>
	<div align="center" style="margin-top: -30px">
        <img src="{{ public_path('template/img/schedclass/schedclassheaderMain.png') }}" width="50%">
    </div>

    <div align="center" style="margin-top: 10px">
        <h3>FACULTY LOAD for School Year {{ request('schlyear') }} - 
			@if(request('semester') == 1)
                First Semester
            @elseif(request('semester') == 2)
                Second Semester
            @elseif(request('semester') == 3)
                Summer
            @else
                Unknown Semester
            @endif
        </h3>
    </div>

    <div class="studinfolabel">
	    <div class="info-row">
	        <span class="info-label">Faculty Name:</span>
	        <span class="info-data">{{ $facultyName }}</span>
	    </div>
	</div>

	<div class="studinfolabel">
	    <div class="info-row">
	        <span class="info-data">TEACHING UNIT</span>
	    </div>
	</div>

	<div>
		<table>
			<thead>
				<tr>
					<th rowspan="2">Subject Code</th>
					<th rowspan="2">Subject Name</th>
					<th rowspan="2">Subject Section</th>
					<th rowspan="2">Descriptive Title</th>
					<th colspan="3" width="3%">Units</th>
					<th rowspan="2">No. of Stud</th>
				</tr>
				<tr>
					<th>Lec</th>
					<th>Lab</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				@php
				    $totalSublecredit = 0;
				    $totalSublabcredit = 0;
				    $totalSubUnit = 0;
				    $totalStudentCount = 0;
				@endphp
				@foreach($facloadsched as $datafacloadsched)
				@php
			        $totalSublecredit += $datafacloadsched->sublecredit;
			        $totalSublabcredit += $datafacloadsched->sublabcredit;
			        $totalSubUnit += $datafacloadsched->sub_unit;
			        $totalStudentCount += $datafacloadsched->studentCount;
			    @endphp
				<tr>
					<td width="10%">{{ $datafacloadsched->subCode }}</td>
					<td width="13%">{{ $datafacloadsched->sub_name }}</td>
					<td width="13%">{{ $datafacloadsched->displaySection  }}</td>
					<td>{{ $datafacloadsched->sub_title }}</td>
					<td style="text-align: center;" width="5%">{{ $datafacloadsched->sublecredit }}</td>
					<td style="text-align: center;" width="5%">{{ $datafacloadsched->sublabcredit }}</td>
					<td style="text-align: center;" width="5%">{{ $datafacloadsched->sub_unit }}</td>
					<td style="text-align: center;" width="5%">{{ $datafacloadsched->studentCount }}</td>
				</tr>
				@endforeach
				<tr>
				    <td style="text-align: right; border: none;" colspan="4"><strong>TOTAL(Teaching Unit):</strong></td>
				    <td style="text-align: center; border: none;" width="5%"><strong>{{ $totalSublecredit }}</strong></td>
				    <td style="text-align: center; border: none;" width="5%"><strong>{{ $totalSublabcredit }}</strong></td>
				    <td style="text-align: center; border: none;" width="5%"><strong>{{ $totalSubUnit }}</strong></td>
				    <td style="text-align: center; border: none;" width="5%"><strong>{{ $totalStudentCount }}</strong></td>
				</tr>
			</tbody>
		</table>
	</div>
</body>
</html>