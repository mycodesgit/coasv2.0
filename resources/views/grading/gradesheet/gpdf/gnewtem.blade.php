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
			margin-right: 150px;
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
		  	height: 15px;
		} 
		#gradestable th {
		  	border: 1px solid #000;
		  	font-weight: normal;
		  	padding: 2px;
		  	font-size: 12pt;
		}
		.text-labeltextbot {
			font-family: Calibri, sans-serif, arial;
			font-size: 8pt;
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

		body {
			font-family: Calibri, sans-serif, Arial;
			font-size: 10pt;
		}
		.header {
			margin-top: -60px !important;
			text-align: center;
		}
		
		footer {
			margin-top: 50px;
			bottom: 0px;
			left: 0;
			right: 0;
			height: 50px;
			text-align: center;
			font-size: 8pt;
			font-family: Calibri, sans-serif, Arial;
			font-weight: normal !important;
        }
		.page-break {
			page-break-after: always;
			font-weight: normal !important;
		}
	</style>
</head>
<body>
	@php
	    use Illuminate\Support\Str;
	@endphp
	@php 
		$rowsPerPage = 30; 
		$totalPages = ceil(count($gradeviewData) / $rowsPerPage);

		if(Str::contains($gradeviewData->first()->subSec, '4-'))
		{
			function getEquivalentGrade($grade) {
			    if ($grade === 'INC') {
		            return ['gpa' => 'INC', 'status' => 'Incomplete'];
		        } elseif ($grade === 'NN') {
			        return ['gpa' => 'NN', 'status' => 'No Name'];
			    } elseif ($grade === 'NG') {
			        return ['gpa' => 'NG', 'status' => 'No Grade'];
			    } elseif ($grade === 'Drp.') {
			        return ['gpa' => 'Drp.', 'status' => 'Drop'];
		        } elseif ($grade >= 95) {
			        return ['gpa' => '1.0', 'status' => 'Passed'];
			    } elseif ($grade >= 94) {
			        return ['gpa' => '1.1', 'status' => 'Passed'];
			    } elseif ($grade >= 93) {
			        return ['gpa' => '1.2', 'status' => 'Passed'];
			    } elseif ($grade >= 92) {
			        return ['gpa' => '1.3', 'status' => 'Passed'];
			    } elseif ($grade >= 91) {
			        return ['gpa' => '1.4', 'status' => 'Passed'];
			    } elseif ($grade >= 90) {
			        return ['gpa' => '1.5', 'status' => 'Passed'];
			    } elseif ($grade >= 89) {
			        return ['gpa' => '1.6', 'status' => 'Passed'];
			    } elseif ($grade >= 88) {
			        return ['gpa' => '1.7', 'status' => 'Passed'];
			    } elseif ($grade >= 87) {
			        return ['gpa' => '1.8', 'status' => 'Passed'];
			    } elseif ($grade >= 86) {
			        return ['gpa' => '1.9', 'status' => 'Passed'];
			    } elseif ($grade >= 85) {
			        return ['gpa' => '2.0', 'status' => 'Passed'];
			    } elseif ($grade >= 84) {
			        return ['gpa' => '2.1', 'status' => 'Passed'];
			    } elseif ($grade >= 83) {
			        return ['gpa' => '2.2', 'status' => 'Passed'];
			    } elseif ($grade >= 82) {
			        return ['gpa' => '2.3', 'status' => 'Passed'];
			    } elseif ($grade >= 81) {
			        return ['gpa' => '2.4', 'status' => 'Passed'];
			    } elseif ($grade >= 80) {
			        return ['gpa' => '2.5', 'status' => 'Passed'];
			    } elseif ($grade >= 79) {
			        return ['gpa' => '2.6', 'status' => 'Passed'];
			    } elseif ($grade >= 78) {
			        return ['gpa' => '2.7', 'status' => 'Passed'];
			    } elseif ($grade >= 77) {
			        return ['gpa' => '2.8', 'status' => 'Passed'];
			    } elseif ($grade >= 76) {
			        return ['gpa' => '2.9', 'status' => 'Passed'];
			    } elseif ($grade >= 75) {
			        return ['gpa' => '3.0', 'status' => 'Passed'];
			    } elseif ($grade >= 74) {
			        return ['gpa' => '4.0', 'status' => 'Conditional'];
			    } elseif ($grade >= 73) {
			        return ['gpa' => '4.0', 'status' => 'Conditional'];
			    } elseif ($grade >= 72) {
			        return ['gpa' => '4.0', 'status' => 'Conditional'];
			    } elseif ($grade >= 71) {
			        return ['gpa' => '4.0', 'status' => 'Conditional'];
			    } elseif ($grade >= 70) {
			        return ['gpa' => '4.0', 'status' => 'Conditional'];
			    } elseif ($grade >= 69) {
			        return ['gpa' => '5.0', 'status' => 'Failure'];
			    } else {
			        return ['gpa' => '5.0', 'status' => 'Failure'];
			    }
			}
		} else {
			function getEquivalentGrade($grade) {
			    if ($grade === 'INC') {
		            return ['gpa' => 'INC', 'status' => 'Incomplete'];
		        } elseif ($grade === 'NN') {
			        return ['gpa' => 'NN', 'status' => 'No Name'];
			    } elseif ($grade === 'NG') {
			        return ['gpa' => 'NG', 'status' => 'No Grade'];
			    } elseif ($grade === 'Drp.') {
			        return ['gpa' => 'Drp.', 'status' => 'Drop'];
		        } elseif ($grade >= 97) {
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
		}
	@endphp

	@for ($page = 0; $page < $totalPages; $page++)
		@php
			$revision = $page + 1; // so page 0 = revision 1
			$revisionFormatted = str_pad($revision, 2, '0', STR_PAD_LEFT);
		@endphp

		<div class="header">
			<img src="{{ asset('template/img/gradesheetheader.png') }}" width="80%">
		</div>
		<div class="text-subjectCode" style="margin-top: 15px">Subject Code: <u><strong>{{ $gradeviewData->first()->sub_name }}</strong></u></div>
		<div class="text-subjectDesc" style="margin-top: -20px;">Descriptive Title: <u><strong>{{ $gradeviewData->first()->sub_title }}</strong></u></div>

		<div class="text-subsection" style="margin-top: 10px">Course/Year/Section: <u><strong>{{ $gradeviewData->first()->subSec ?? '' }}</strong></u></div>
		<div class="text-unit" style="margin-top: -20px">Units: <u><strong>{{ $gradeviewData->first()->sub_unit }}</strong></u></div>
		
		{{-- <div class="text-semester" style="margin-top: -20px">Semester: <u><strong>{{ $gradeviewData->first()->semester }}  {{ $gradeviewData->first()->schlyear }}</strong></u></div> --}}
		<div class="text-semester" style="margin-top: -20px">
			Semester: 
			<u>
				<strong>
					@if($gradeviewData->first()->semester == 1)
						1st
					@elseif($gradeviewData->first()->semester == 2)
						2nd
					@elseif($gradeviewData->first()->semester == 3)
						Summer
					@else
						{{ $gradeviewData->first()->semester }}
					@endif
					{{ $gradeviewData->first()->schlyear }}
				</strong>
			</u>
		</div>

		<div class="text-labelnote" style="margin-top: 15px"><i>(Grades must be written in <b>BLACK or BLUE</b> ink. <b>CONDITIONAL OR FAILURE</b> in <b>RED</b> ink)</i></div> 

		
		<div class="">
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
			    	@for ($i = 0; $i < $rowsPerPage; $i++)
				       	@php
		                    $rowIndex = $page * $rowsPerPage + $i; // Calculate the current row index
		                @endphp
						<tr>
							@if(isset($gradeviewData[$rowIndex]))
	                			@php $studgrade = $gradeviewData[$rowIndex]; @endphp
	                			<td style="font-size: 9pt; text-align: center;" width="5px">{{ $rowIndex + 1 }}</td>
								<td style="font-size: 9pt;">{{ $studgrade->lname }}, {{ $studgrade->fname }} {{-- {{ strtoupper(substr($studgrade->mname, 0, 1)) }}. --}}</td>
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
								        @if(is_numeric($studgrade->subjFgrade) && $studgrade->subjFgrade <= 69)
								            <span>0</span>
								        @elseif(in_array($studgrade->subjFgrade, ['INC', 'NN', 'NG']))
								            <span>0</span>
								        @else
								            <span>{{ $studgrade->creditEarned }}</span>
								        @endif
								    @endif
								</td>
								<td align="center" style="font-size: 9pt; font-style: italic; color: @if($studgrade->gstat == 2 && !empty($studgrade->subjFgrade)){{ getEquivalentGrade($studgrade->subjFgrade)['status'] === 'Conditional' || getEquivalentGrade($studgrade->subjFgrade)['status'] === 'Failure' ? 'red' : '' }}@endif">
						            @if($studgrade->gstat == 2 && !empty($studgrade->subjFgrade))
						                <span>{{ getEquivalentGrade($studgrade->subjFgrade)['status'] }}</span>
						            @endif
						        </td>
								<td style="font-size: 9pt; text-align: center;">{{ $rowIndex + 1 }}</td>
							@else
								<td style="font-size: 9pt; text-align: center; margin-top: -50px">{{ $rowIndex + 1 }}</td>
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
				                <td style="font-size: 9pt; text-align: center;">{{ $rowIndex + 1 }}</td>
							@endif
						</tr>
					@endfor
				</tbody>
			</table>
			@if ($page < $totalPages)
		        <!-- Add a page break for all but the last page -->
		    @endif
		</div>
		

		<div class="text-labeltextbot" style="margin-top: 2px; margin-left: 50px"><i>% Equivalent</i></div>
		<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 150px"><i>No. Equivalent</i></div>
		<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 250px"><i>1.0 Excellent</i></div>
		<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 350px"><i>2.0 Thorough</i></div>
		<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 450px"><i>3.0 Lowest Passing Grade</i></div>
		<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 620px"><i>5.0 Failure</i></div>

		<div class="text-midterm" style="margin-top: 15px; text-align: center;">MIDTERM</div>

		@if(Str::contains($gradeviewData->first()->subSec, '4-'))
			<div class="text-equiv" style="margin-top: 2px;">Above &nbsp;&nbsp;&nbsp; 95- 1.0</div>
			<div class="text-equiv" style="margin-top: 2px;">94 - 1.1   &nbsp;&nbsp;&nbsp; 83 - 2.2</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 120px; text-transform: uppercase;">
				<u>
					@auth('faculty')
			            @if(Auth::guard('faculty')->user()->role == '943')
			                {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
			            @endif
			        @endauth
			    </u>
			</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 320px; text-transform: uppercase;">
				<u>
					@php
						$schlyear = request('schlyear');
						$sem = request('semester');

			            $dean = App\Models\ScheduleDB\FacDesignation::join('faculty', 'fac_designation.fac_id', 'faculty.id')
			            		->where('fac_designation.facdept', '=', Auth::guard('faculty')->user()->dept)
			            		->where('fac_designation.semester', '=', '1')
			            		->where('fac_designation.schlyear', '=', '2024-2025')
			            		->select('faculty.fname', 'faculty.lname', 'fac_designation.rankcomma')
			            		->first();
			        @endphp
					@if($dean)
		            	{{ $dean->fname }} {{ $dean->lname }},  {{ $dean->rankcomma }}
		            @endif
			    </u>
			</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 540px; text-transform: uppercase;">
				<u>
					@php
			            $dean = App\Models\ScheduleDB\FacDesignation::join('faculty', 'fac_designation.fac_id', 'faculty.id')->where('facdept', '=', 'ADM')->first();
			        @endphp
					@if($dean)
		            	{{ $dean->fname }} {{ $dean->lname }}
		            @endif
			    </u>
			</div>

			<div class="text-equiv" style="margin-top: 2px;">93 - 1.2   &nbsp;&nbsp;&nbsp;&nbsp;82 - 2.3</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 170px;">
				Instructor's Signature
			</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 400px;">
				Dept. Head
			</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 550px;">
				Registrar's Signature
			</div>
			<div class="text-equiv" style="margin-top: 4px;">92 - 1.3   &nbsp;&nbsp;&nbsp;&nbsp;81 - 2.4</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 175px;">
				Over Printed Name
			</div>
			<div class="text-equiv" style="margin-top: 4px;">91 - 1.4   &nbsp;&nbsp;&nbsp;&nbsp;80 - 2.5</div>
			<div class="text-midterm" style="margin-top: -20px; text-align: center;">FINAL</div>
			<div class="text-equiv" style="margin-top: 1px;">90 - 1.5   &nbsp;&nbsp;&nbsp;&nbsp;79 - 2.6</div>
			<div class="text-equiv" style="margin-top: 2px;">89 - 1.6   &nbsp;&nbsp;&nbsp;&nbsp;78 - 2.7</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 120px; text-transform: uppercase;">
				<u>
					@auth('faculty')
			            @if(Auth::guard('faculty')->user()->role == '943')
			                {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
			            @endif
			        @endauth
			    </u>
			</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 320px; text-transform: uppercase;">
				<u>
					@php
						$schlyear = request('schlyear');
						$sem = request('semester');

			            $dean = App\Models\ScheduleDB\FacDesignation::join('faculty', 'fac_designation.fac_id', 'faculty.id')
			            		->where('fac_designation.facdept', '=', Auth::guard('faculty')->user()->dept)
			            		->where('fac_designation.semester', '=', '1')
			            		->where('fac_designation.schlyear', '=', '2024-2025')
			            		->select('faculty.fname', 'faculty.lname', 'fac_designation.rankcomma')
			            		->first();
			        @endphp
					@if($dean)
		            	{{ $dean->fname }} {{ $dean->lname }}, {{ $dean->rankcomma }}
		            @endif
			    </u>
			</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 540px; text-transform: uppercase;">
				<u>
					@php
			            $dean = App\Models\ScheduleDB\FacDesignation::join('faculty', 'fac_designation.fac_id', 'faculty.id')->where('facdept', '=', 'ADM')->first();
			        @endphp
					@if($dean)
		            	{{ $dean->fname }} {{ $dean->lname }}
		            @endif
			    </u>
			</div>
			<div class="text-equiv" style="margin-top: 2px;">88 - 1.7   &nbsp;&nbsp;&nbsp;&nbsp;77 - 2.8</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 170px;">
				Instructor's Signature
			</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 400px;">
				Dept. Head
			</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 550px;">
				Registrar's Signature
			</div>
			<div class="text-equiv" style="margin-top: 4px;">87 - 1.8   &nbsp;&nbsp;&nbsp;&nbsp;76 - 2.9</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 175px;">
				Over Printed Name
			</div>
			<div class="text-equiv" style="margin-top: 2px;">86 - 1.9 &nbsp;&nbsp;&nbsp;&nbsp;75 - 3.0</div>
			<div class="text-equiv" style="margin-top: 2px;">85 - 2.0 &nbsp;&nbsp;&nbsp;&nbsp;74 & 70 - 4.0 (Conditional)</div>

			<div class="" style="margin-top: -20px; margin-left: 350px;">
				________________
			</div>

			<div class="" style="margin-top: -20px; margin-left: 550px;">
				________________
			</div>

			<div style="margin-top: 0px; margin-left: 370px; font-size: 9pt; font-family: Calibri, sans-serif, arial;">
				Date Received
			</div>

			<div style="margin-top: -12px; margin-left: 590px; font-size: 9pt; font-family: Calibri, sans-serif, arial;">
				Posted by
			</div>

			<div class="text-equiv" style="margin-top: -23px;">84 - 2.1 &nbsp;&nbsp;&nbsp;&nbsp;69 & Below - 5.0 (Failure)</div>
		@else
			<div class="text-equiv" style="margin-top: 10px;">100 - 97 &nbsp;- 1.00</div>
			<div class="text-equiv" style="margin-top: 2px;">96 - 94   &nbsp;&nbsp;&nbsp;- 1.25</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 120px; text-transform: uppercase;">
				<u>
					@auth('faculty')
			            @if(Auth::guard('faculty')->user()->role == '943')
			                {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
			            @endif
			        @endauth
			    </u>
			</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 320px; text-transform: uppercase;">
				<u>
					@php
						$schlyear = request('schlyear');
						$sem = request('semester');

			            $dean = App\Models\ScheduleDB\FacDesignation::join('faculty', 'fac_designation.fac_id', 'faculty.id')
			            		->where('fac_designation.facdept', '=', Auth::guard('faculty')->user()->dept)
			            		->where('fac_designation.semester', '=', '1')
			            		->where('fac_designation.schlyear', '=', '2024-2025')
			            		->select('faculty.fname', 'faculty.lname', 'fac_designation.rankcomma')
			            		->first();
			        @endphp
					@if($dean)
		            	{{ $dean->fname }} {{ $dean->lname }},  {{ $dean->rankcomma }}
		            @endif
			    </u>
			</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 540px; text-transform: uppercase;">
				<u>
					@php
			            $dean = App\Models\ScheduleDB\FacDesignation::join('faculty', 'fac_designation.fac_id', 'faculty.id')->where('facdept', '=', 'ADM')->first();
			        @endphp
					@if($dean)
		            	{{ $dean->fname }} {{ $dean->lname }}
		            @endif
			    </u>
			</div>

			<div class="text-equiv" style="margin-top: 2px;">93 - 91   &nbsp;&nbsp;&nbsp;- 1.50</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 170px;">
				Instructor's Signature
			</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 400px;">
				Dept. Head
			</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 550px;">
				Registrar's Signature
			</div>
			<div class="text-equiv" style="margin-top: 4px;">90 - 88   &nbsp;&nbsp;&nbsp;- 1.75</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 175px;">
				Over Printed Name
			</div>
			<div class="text-equiv" style="margin-top: 4px;">87 - 85   &nbsp;&nbsp;&nbsp;- 2.00</div>
			<div class="text-midterm" style="margin-top: -20px; text-align: center;">FINAL</div>
			<div class="text-equiv" style="margin-top: 1px;">84 - 82   &nbsp;&nbsp;&nbsp;- 2.25</div>
			<div class="text-equiv" style="margin-top: 2px;">81 - 79   &nbsp;&nbsp;&nbsp;- 2.50</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 120px; text-transform: uppercase;">
				<u>
					@auth('faculty')
			            @if(Auth::guard('faculty')->user()->role == '943')
			                {{ Auth::guard('faculty')->user()->fname }} {{ Auth::guard('faculty')->user()->lname }}
			            @endif
			        @endauth
			    </u>
			</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 320px; text-transform: uppercase;">
				<u>
					@php
						$schlyear = request('schlyear');
						$sem = request('semester');

			            $dean = App\Models\ScheduleDB\FacDesignation::join('faculty', 'fac_designation.fac_id', 'faculty.id')
			            		->where('fac_designation.facdept', '=', Auth::guard('faculty')->user()->dept)
			            		->where('fac_designation.semester', '=', '1')
			            		->where('fac_designation.schlyear', '=', '2024-2025')
			            		->select('faculty.fname', 'faculty.lname', 'fac_designation.rankcomma')
			            		->first();
			        @endphp
					@if($dean)
		            	{{ $dean->fname }} {{ $dean->lname }}, {{ $dean->rankcomma }}
		            @endif
			    </u>
			</div>
			<div class="text-facultyname" style="margin-top: -20px; margin-left: 540px; text-transform: uppercase;">
				<u>
					@php
			            $dean = App\Models\ScheduleDB\FacDesignation::join('faculty', 'fac_designation.fac_id', 'faculty.id')->where('facdept', '=', 'ADM')->first();
			        @endphp
					@if($dean)
		            	{{ $dean->fname }} {{ $dean->lname }}
		            @endif
			    </u>
			</div>
			<div class="text-equiv" style="margin-top: 2px;">78 - 76   &nbsp;&nbsp;&nbsp;- 2.75</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 170px;">
				Instructor's Signature
			</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 400px;">
				Dept. Head
			</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 550px;">
				Registrar's Signature
			</div>
			<div class="text-equiv" style="margin-top: 4px;">75       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- 3.00</div>
			<div class="text-facnamelabel" style="margin-top: -20px; margin-left: 175px;">
				Over Printed Name
			</div>
			<div class="text-equiv" style="margin-top: 2px;">74 - 70 &nbsp;&nbsp;&nbsp;- 4.0 (Conditional)</div>
			<div class="text-equiv" style="margin-top: 2px;">69 & Below - 5.0 (Failure)</div>

			<div class="" style="margin-top: -20px; margin-left: 350px;">
				________________
			</div>

			<div class="" style="margin-top: -20px; margin-left: 550px;">
				________________
			</div>

			<div style="margin-top: 0px; margin-left: 370px; font-size: 9pt; font-family: Calibri, sans-serif, arial;">
				Date Received
			</div>

			<div style="margin-top: -12px; margin-left: 590px; font-size: 9pt; font-family: Calibri, sans-serif, arial;">
				Posted by
			</div>
		@endif

		<div class="text-labeltextbot" style="margin-top: 2px; margin-left: 50px"><i>INC - Incomplete</i></div>
		<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 200px"><i>NN - No Name</i></div>
		<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 350px"><i>NG - No Grade</i></div>
		<div class="text-labeltextbot" style="margin-top: -20px; margin-left: 500px"><i>NGS -  No Grading Sheet</i></div>

		{{-- <div style="margin-top: 8px; text-align: center; font-size: 8pt; font-family: Calibri, sans-serif, arial;">
			Doc Control Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPSU-F-REG-08&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Effective Date::&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9/12/2018&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Revision No.:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;00
		</div> --}}
		<footer >
			Doc Control Code:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CPSU-F-REG-08
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Effective Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9/12/2018
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Revision No.:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $revisionFormatted }}
		</footer>
		@if ($page < $totalPages - 1)
			<div class="page-break"></div>
		@endif

	@endfor
	
</body>
</html>