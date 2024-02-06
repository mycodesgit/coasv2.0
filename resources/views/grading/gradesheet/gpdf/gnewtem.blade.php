<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>

	<style>
		.text-subjectCode {
			font-family: Calibri, sans-serif, arial;
			font-size: 10pt;
			text-align: left;
		}
		.text-subjectDesc {
			font-family: Calibri, sans-serif, arial;
			font-size: 10pt;
			text-align: center;
		}
		.text-subsection {
			font-family: Calibri, sans-serif, arial;
			font-size: 10pt;
			text-align: left;
		}
		.text-unit {
			font-family: Calibri, sans-serif, arial;
			font-size: 10pt;
			text-align: center;
			margin-right: 100px;
		}
		.text-semester {
			font-family: Calibri, sans-serif, arial;
			font-size: 10pt;
			text-align: right;
			margin-right: 250px;
		}
		.text-labelnote {
			font-family: Calibri, sans-serif, arial;
			font-size: 8pt;
			text-align: center;
		}
		#gradestable {
		  	font-family: sans-serif;
		  	border-collapse: collapse;
		  	width: 100%;
		}
		#gradestable td {
			border: 1px solid #000;
		  	padding: 2px;
		  	height: 18px;
		} 
		#gradestable th {
		  	border: 1px solid #000;
		  	font-weight: normal;
		  	padding: 2px;
		  	font-size: 12pt;
		}
		.text-labeltextbot {
			font-family: Calibri, sans-serif, arial;
			font-size: 9pt;
			text-align: left;
			font-weight: bold;
		}
		.text-equiv {
			font-family: Calibri, sans-serif, arial;
			font-size: 10pt;
			text-align: left;
		}
		.text-midterm {
			font-family: Calibri, sans-serif, arial;
			font-size: 11pt;
			font-weight: bold;
		}
		.text-facultyname {
			font-family: Calibri, sans-serif, arial;
			font-size: 11pt;
			font-weight: bold;
		}
		.text-facnamelabel {
			font-family: Calibri, sans-serif, arial;
			font-size: 9pt;
		}
	</style>
</head>
<body>
	<div align="center" style="margin-top: -20px">
		<img src="{{ asset('template/img/gradesheetheader.png') }}" width="80%">
	</div>
	<div class="text-subjectCode" style="margin-top: 20px">Subject Code: <u><strong>{{ $gradeviewData->first()->sub_name }}</strong></u></div>
	<div class="text-subjectDesc" style="margin-top: -20px;">Descriptive Title: <u><strong>{{ $gradeviewData->first()->sub_desc }}</strong></u></div>

	<div class="text-subsection" style="margin-top: 10px">Course/Year/Section: <u><strong>{{ $gradeviewData->first()->subSec }}</strong></u></div>
	<div class="text-unit" style="margin-top: -20px">Units: <u><strong>{{ $gradeviewData->first()->sub_unit }}</strong></u></div> 
	<div class="text-semester" style="margin-top: -20px">Semester: <u><strong>{{ $gradeviewData->first()->semester }}</strong></u></div>

	<div class="text-labelnote" style="margin-top: 20px"><i>(Grades must be written in <b>BLACK or BLUE</b> ink. <b>CONDITIONAL OR FAILURE</b> in <b>RED</b> ink)</i></div> 

	<div>
		<table id="gradestable">
			<thead>
				<tr>
					<th colspan="2"><strong>NAMES</strong></th>
					<th rowspan="2"><strong>C</strong></th>
					<th rowspan="2"><strong>P</strong></th>
					<th rowspan="2"><strong>A</strong></th>
					<th colspan="1"><strong>Mid</strong></th>
					<th colspan="1"><strong>N</strong></th>
					<th rowspan="2"><strong>C</strong></th>
					<th rowspan="2"><strong>P</strong></th>
					<th rowspan="2"><strong>A</strong></th>
					<th colspan="1"><strong>T.</strong></th>
					<th colspan="1"><strong>N.</strong></th>
					<th colspan="1"><strong>Mid</strong></th>
					<th colspan="1"><strong>Final</strong></th>
					<th colspan="1"><strong>FR%</strong></th>
					<th colspan="1"><strong>N.</strong></th>
					<th rowspan="2"><strong>Credit</strong></th>
					<th rowspan="2"><strong>Remarks</strong></th>
					<th rowspan="2"></th>
				</tr>
				<tr>
					<th colspan="2" style="font-size: 6pt"><i>(Arrange alphabetically regardless of sex)</i></th>
					<th style="font-size: 9pt"><i>Gr.</i></th>
					<th style="font-size: 9pt"><i>Eqv.</i></th>
					<th style="font-size: 9pt"><i>F. Gr.</i></th>
					<th style="font-size: 9pt"><i>Eqv.</i></th>
					<th style="font-size: 9pt"><i>40%</i></th>
					<th style="font-size: 9pt"><i>60%</i></th>
					<th style="font-size: 9pt"><i>Eqv.</i></th>
					<th style="font-size: 9pt"><i>Eqv.</i></th>
				</tr>
			</thead>
			<tbody>
				@php 
					$rowCount = 30; 
					function getEquivalentGrade($grade) {
					    if ($grade >= 97) {
					        return ['gpa' => '1.00', 'status' => 'Passed'];
					    } elseif ($grade >= 94) {
					        return ['gpa' => '1.25', 'status' => 'Passed'];
					    } elseif ($grade >= 91) {
					        return ['gpa' => '1.50', 'status' => 'Passed'];
					    } elseif ($grade >= 88) {
					        return ['gpa' => '1.75', 'status' => 'Passed'];
					    } elseif ($grade >= 85) {
					        return ['gpa' => '2.00', 'status' => 'Passed'];
					    } elseif ($grade >= 82) {
					        return ['gpa' => '2.25', 'status' => 'Passed'];
					    } elseif ($grade >= 79) {
					        return ['gpa' => '2.50', 'status' => 'Passed'];
					    } elseif ($grade >= 76) {
					        return ['gpa' => '2.75', 'status' => 'Passed'];
					    } elseif ($grade >= 75) {
					        return ['gpa' => '3.00', 'status' => 'Passed'];
					    } elseif ($grade >= 70) {
					        return ['gpa' => '4.00', 'status' => 'Conditional'];
					    } else {
					        return ['gpa' => '5.00', 'status' => 'Failure'];
					    }
					}
				@endphp
		        @for ($i = 0; $i < $rowCount; $i++) {
					<tr>
						@if(isset($gradeviewData[$i]))
                			@php $studgrade = $gradeviewData[$i]; @endphp
                			<td style="font-size: 9pt; text-align: center;" width="5px">{{ $i + 1 }}</td>
							<td style="font-size: 9pt;">{{ $studgrade->lname }}, {{ $studgrade->fname }} {{ strtoupper(substr($studgrade->mname, 0, 1)) }}.</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td align="center" style="font-size: 9pt; color: @if($studgrade->gstat == 2 && !empty($studgrade->subjFgrade) && (getEquivalentGrade($studgrade->subjFgrade)['status'] === 'Conditional' || getEquivalentGrade($studgrade->subjFgrade)['status'] === 'Failure')){{ 'red' }}@endif">
								@if($studgrade->gstat == 2 || empty($studgrade->subjFgrade))
										<span>{{ $studgrade->subjFgrade }}</span>
								@endif
							</td>
							<td align="center" style="font-size: 9pt; font-weight: bold; color: @if($studgrade->gstat == 2 && !empty($studgrade->subjFgrade)){{ getEquivalentGrade($studgrade->subjFgrade)['status'] === 'Conditional' || getEquivalentGrade($studgrade->subjFgrade)['status'] === 'Failure' ? 'red' : '' }}@endif">
					            @if($studgrade->gstat == 2 && !empty($studgrade->subjFgrade))
					                <span>{{ getEquivalentGrade($studgrade->subjFgrade)['gpa'] }}</span>
					            @endif
					        </td>
							<td align="center" style="font-size: 9pt; font-weight: bold;">
								@if($studgrade->gstat == 2 && !empty($studgrade->subjFgrade))
					                <span>{{ $studgrade->creditEarned }}</span>
					            @endif
							</td>
							<td align="center" style="font-size: 9pt; font-style: italic; color: @if($studgrade->gstat == 2 && !empty($studgrade->subjFgrade)){{ getEquivalentGrade($studgrade->subjFgrade)['status'] === 'Conditional' || getEquivalentGrade($studgrade->subjFgrade)['status'] === 'Failure' ? 'red' : '' }}@endif">
					            @if($studgrade->gstat == 2 && !empty($studgrade->subjFgrade))
					                <span>{{ getEquivalentGrade($studgrade->subjFgrade)['status'] }}</span>
					            @endif
					        </td>
							<td style="font-size: 9pt; text-align: center;">{{ $i + 1 }}</td>
						@else
							<td style="font-size: 9pt; text-align: center;">{{ $i + 1 }}</td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td></td>
			                <td style="font-size: 9pt; text-align: center;">{{ $i + 1 }}</td>
						@endif
					</tr>
				@endfor
			</tbody>
		</table>
	</div>

	<div class="text-labeltextbot" style="margin-top: 2px; margin-left: 50px"><i>% Equivalent</i></div>
	<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 150px"><i>No. Equivalent</i></div>
	<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 250px"><i>1.0 Excellent</i></div>
	<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 350px"><i>2.0 Thorough</i></div>
	<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 450px"><i>3.0 Lowest Passing Grade</i></div>
	<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 620px"><i>5.0 Failure</i></div>

	<div class="text-midterm" style="margin-top: 20px; text-align: center;">MIDTERM</div>

	<div class="text-equiv" style="margin-top: 10px;">100 - 97 &nbsp;- 1.00</div>
	<div class="text-equiv" style="margin-top: 2px;">96 - 94   &nbsp;&nbsp;&nbsp;- 1.25</div>
	<div class="text-facultyname" style="margin-top: -20px; margin-left: 140px; text-transform: uppercase;">
		<u>
			@auth('faculty')
	            @if(Auth::guard('faculty')->user()->isAdmin == '8')
	                {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
	            @endif
	        @endauth
	    </u>
	</div>
	<div class="text-equiv" style="margin-top: 2px;">93 - 91   &nbsp;&nbsp;&nbsp;- 1.50</div>
	<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 170px;">
		Instructor's Signature
	</div>
	<div class="text-equiv" style="margin-top: 4px;">90 - 88   &nbsp;&nbsp;&nbsp;- 1.75</div>
	<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 175px;">
		Over Printed Name
	</div>
	<div class="text-equiv" style="margin-top: 4px;">87 - 85   &nbsp;&nbsp;&nbsp;- 2.00</div>
	<div class="text-midterm" style="margin-top: -20px; text-align: center;">FINAL</div>
	<div class="text-equiv" style="margin-top: 1px;">84 - 82   &nbsp;&nbsp;&nbsp;- 2.25</div>
	<div class="text-equiv" style="margin-top: 2px;">81 - 79   &nbsp;&nbsp;&nbsp;- 2.50</div>
	<div class="text-facultyname" style="margin-top: -20px; margin-left: 140px; text-transform: uppercase;">
		<u>
			@auth('faculty')
	            @if(Auth::guard('faculty')->user()->isAdmin == '8')
	                {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
	            @endif
	        @endauth
	    </u>
	</div>
	<div class="text-equiv" style="margin-top: 2px;">78 - 76   &nbsp;&nbsp;&nbsp;- 2.75</div>
	<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 170px;">
		Instructor's Signature
	</div>
	<div class="text-equiv" style="margin-top: 4px;">75       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- 3.00</div>
	<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 175px;">
		Over Printed Name
	</div>
	<div class="text-equiv" style="margin-top: 2px;">74 - 70 &nbsp;&nbsp;&nbsp;- 4.0 (Conditional)</div>
	<div class="text-equiv" style="margin-top: 2px;">69 & Below - 5.0 (Failure)</div>

	
</body>
</html>