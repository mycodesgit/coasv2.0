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
			@php
				$perPage = 50; // 25 left + 25 right
				$totalRows = 25;
				$totalStudents = count($substudnowviewpdf);
				$totalPages = ceil($totalStudents / $perPage);
			@endphp

			@for ($page = 0; $page < $totalPages; $page++)

				@php
					$offset = $page * $perPage;
					$leftNumber = $offset + 1;
					$rightNumber = $offset + 26;
				@endphp

				<table width="100%" border="1" cellspacing="0">
					<thead>
						<tr>
							<th>No.</th>
							<th>Name<br><i>(Last, First, Middle Initial)</i></th>
							<th>Signature</th>
							<th>No.</th>
							<th>Name<br><i>(Last, First, Middle Initial)</i></th>
							<th>Signature</th>
						</tr>
					</thead>

					<tbody>
						@for ($i = 0; $i < $totalRows; $i++)
							<tr>
								{{-- LEFT --}}
								<td>{{ $leftNumber <= $totalStudents ? $leftNumber++ : '' }}</td>
								@if (isset($substudnowviewpdf[$offset + $i]))
									@php $student = $substudnowviewpdf[$offset + $i]; @endphp
									<td>
										{{ $student->lname }},
										{{ $student->fname }}
										{{ strtoupper(substr($student->mname, 0, 1)) }}
										{{ ($student->ext && $student->ext !== 'N/A') ? strtoupper(substr($student->ext, 0, 2)) . '.' : '' }}
									</td>
								@else
									<td></td>
								@endif
								<td></td>

								{{-- RIGHT --}}
								<td>{{ $rightNumber <= $totalStudents ? $rightNumber++ : '' }}</td>
								@if (isset($substudnowviewpdf[$offset + $i + $totalRows]))
									@php $student = $substudnowviewpdf[$offset + $i + $totalRows]; @endphp
									<td>
										{{ $student->lname }},
										{{ $student->fname }}
										{{ strtoupper(substr($student->mname, 0, 1)) }}
										{{ ($student->ext && $student->ext !== 'N/A') ? strtoupper(substr($student->ext, 0, 3)) . '.' : '' }}
									</td>
								@else
									<td></td>
								@endif
								<td></td>
							</tr>
						@endfor
					</tbody>
				</table>

				{{-- PAGE BREAK --}}
				@if ($page + 1 < $totalPages)
					<div style="page-break-after: always;"></div>
				@endif

			@endfor

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