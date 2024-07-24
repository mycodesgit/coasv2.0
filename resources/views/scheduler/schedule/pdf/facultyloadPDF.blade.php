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
		            $prevSubCode = '';
		            $prevSubName = '';
		            $prevSubSec = '';
		            $prevSubTitle = '';
		        @endphp

		        @foreach($facloadsched as $datafacloadsched)
		            <tr>
		                @if ($datafacloadsched->subCode != $prevSubCode || $datafacloadsched->sub_name != $prevSubName || $datafacloadsched->subSec != $prevSubSec || $datafacloadsched->sub_title != $prevSubTitle)
		                    <td>{{ $datafacloadsched->subCode }}</td>
		                    <td>{{ $datafacloadsched->sub_name }}</td>
		                    <td>{{ $datafacloadsched->subSec }}</td>
		                    <td>{{ $datafacloadsched->sub_title }}</td>
		                    <td>{{ $datafacloadsched->sublecredit }}</td>
			                <td>{{ $datafacloadsched->sublabcredit }}</td>
			                <td>{{ $datafacloadsched->sub_unit }}</td>
			                <td>{{ $datafacloadsched->studentCount }}</td>
		                    @php
		                        $prevSubCode = $datafacloadsched->subCode;
		                        $prevSubName = $datafacloadsched->sub_name;
		                        $prevSubSec = $datafacloadsched->subSec;
		                        $prevSubTitle = $datafacloadsched->sub_title;
		                    @endphp
		                @endif
		            </tr>
		        @endforeach
			</tbody>
		</table>
	</div>
</body>
</html>