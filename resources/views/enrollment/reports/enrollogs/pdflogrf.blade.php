<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>

	<style type="text/css">
		.textdoccode {
			margin-top: -50px;
			text-align: right;
			font-size: 9pt;
			font-family: sans-serif;
		}
		.titlecenter {
			text-align: center;
			font-family: sans-serif;
		}
		.studinfolabel {
			text-align: left;
			font-size: 10pt;
			font-family: sans-serif;
		}
		.studinfoID {
			margin-left: 20px;
		}
		.studinfoName {
			margin-left: 75px;
		}
		.studinfoScholar {
			margin-left: 5px;
		}
		.studinfoCourse {
			margin-left: 10px;
		}
		.studinfoAcadYear {
			margin-left: 76px;
		}
		.studinfoenstat {
			margin-left: 10px;
		}
		#table {
            margin-top: 10px;
            font-family: Arial;
            border-collapse: collapse;
            width: 100%;
        }
        #table td {
        	vertical-align: top !important;
    		text-align: left;
            border: 1px solid #000;
            font-size: 9pt;
        } 
        #table th {
            border: 1px solid #000;
            padding: 1px;
        }
        #table-inside {
            margin-top: 10px;
            font-family: Arial;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #fff;
		}

		#table-inside td, #table-inside th {
		    border: 1px solid #fff;
		}
		#table-inside td {
		    font-family: monospace;
		}
		.cred-lec-lab {
			text-align: center !important;
		}
		#table-container {
		    display: flex;
		    align-items: flex-start;
		}
		#table-inside-cash-left {
            margin-top: 10px;
            font-family: monospace;
            border-collapse: collapse;
            width: 95%;
            padding-left: 9px;
            border: 1px solid #e9ecef;
		}

		#table-inside-cash-left td, #table-inside-cash-left th {
		    border: 1px solid #e9ecef;
		}
		#table-inside-cash-right {
            font-family: monospace;
            margin-top: 10px;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #e9ecef;
            margin-left: auto;
		}

		#table-inside-cash-right td, #table-inside-cash-right th {
		    border: 1px solid #e9ecef;
		}
		.accnt-amount {
			text-align: right !important;
		}
		.accnt-or {
			text-align: center !important;
		}
		.accnt-orpaid {
			text-align: right !important;
		}
		.signatories-rf {
			font-size: 10pt !important;
			font-family: Arial !important;
		}
		.signatories-rf-sign {
			height: 25px;
		}
		.terms-rf {
			margin-top: 20px;
			font-size: 10pt;
			font-family: Arial;
			justify-content: center;
			text-indent: 25px;
		}
		.date-sign-rf {
			margin-top: 20px;
			font-size: 10pt;
			font-family: Arial;
		}
		.rf-stud-sign {
			margin-left: 350px;
		}
		.footer-logo {
	        max-width: 100%;
	        max-height: 60%;
	        display: inline-block;
	        position: fixed;
		    bottom: 25;
		    left: 0;
	    }
	</style>
</head>
<body>
	<header style="text-align: center;">
		@if(Auth::guard('web')->user()->campus == 'MC')
			<img src="{{ public_path('template/img/studrf/studrfheader.png') }}" width="72%" style="margin-top: -30px;">
		@elseif(Auth::guard('web')->user()->campus == 'VC')
			<img src="{{ public_path('template/img/studrf/studrfheader-VC.png') }}" width="72%" style="margin-top: -30px;">
		@elseif(Auth::guard('web')->user()->campus == 'SCC')
			<img src="{{ public_path('template/img/studrf/studrfheader-SCC.png') }}" width="72%" style="margin-top: -30px;">
		@elseif(Auth::guard('web')->user()->campus == 'HC')
			<img src="{{ public_path('template/img/studrf/studrfheader-HC.png') }}" width="72%" style="margin-top: -30px;">
		@elseif(Auth::guard('web')->user()->campus == 'MP')
			<img src="{{ public_path('template/img/studrf/studrfheader-MP.png') }}" width="72%" style="margin-top: -30px;">
		@elseif(Auth::guard('web')->user()->campus == 'IC')
			<img src="{{ public_path('template/img/studrf/studrfheader-IC.png') }}" width="72%" style="margin-top: -30px;">
		@elseif(Auth::guard('web')->user()->campus == 'CA')
			<img src="{{ public_path('template/img/studrf/studrfheader-CA.png') }}" width="72%" style="margin-top: -30px;">
		@elseif(Auth::guard('web')->user()->campus == 'CC')
			<img src="{{ public_path('template/img/studrf/studrfheader-CC.png') }}" width="72%" style="margin-top: -30px;">
		@elseif(Auth::guard('web')->user()->campus == 'SC')
			<img src="{{ public_path('template/img/studrf/studrfheader-SC.png') }}" width="72%" style="margin-top: -30px;">
		@elseif(Auth::guard('web')->user()->campus == 'HinC')
			<img src="{{ public_path('template/img/studrf/studrfheader-HinC.png') }}" width="72%" style="margin-top: -30px;">
		@endif
	</header>

    <h3 class="titlecenter">
    	Enrollment Log Report
    </h3>

    <div>
    	@foreach($student as $encode => $students)
			<br>
			<div class="studinfolabel">
				<span style="font-weight: bold;">Student ID No.:</span> <span class="studinfoID">{{ $students->first()->studentID }}</span>
			</div>

			<div class="studinfolabel">
				<span style="font-weight: bold;">Name:</span> <span class="studinfoName">{{ $students->first()->lname }}, {{ $students->first()->fname }} {{ substr($students->first()->mname, 0, 1) }}. @if ($students->first()->ext !== 'N/A'){{ $students->first()->ext }} @endif
				</span>
				<span style="font-weight: bold; text-align: right !important; margin-left: 10px;">Scholarship:</span> <span class="studinfoScholar">{{ $students->first()->scholar_name}}</span>
			</div>

			<div class="studinfolabel">
				<span style="font-weight: bold;">
					Posted by:</span> <span style="margin-left: 48px">{{ $students->first()->postedBy }}
				</span>
			</div>

			<div class="studinfolabel">
				<span style="font-weight: bold;">Date:</span> <span style="margin-left: 82px">
					{{ \Carbon\Carbon::parse($students->first()->updated_ats)->format('F d, Y h:i A') }}
				</span>
			</div>

			<div style="margin-top: 15px; font-weight: bold;">
				Encode: <span style="color:brown; font-size: 11pt">{{ $encode }}</span>
			</div>

			<table id="table" border="1" cellpadding="5" cellspacing="0" width="100%" style="margin-top: 5px; font-size: 10pt;">
				<thead>
					<tr>
						<th>Subj Code</th>
						<th>Subject Name</th>
						<th>Description</th>
						<th>Credit</th>
						<th>Lec Fee</th>
						<th>Lab Fee</th>
						<th>Cycle</th>
					</tr>
				</thead>
				<tbody>
					@foreach($studsub[$encode] ?? [] as $sub)
						<tr>
							<td>{{ $sub->subCode }}</td>
							<td>{{ $sub->sub_name }}</td>
							<td>{{ $sub->sub_title }}</td>
							<td>{{ $sub->subUnit }}</td>
							<td>{{ $sub->lecFee }}</td>
							<td>{{ $sub->labFee }}</td>
							<td>{{ $sub->isType }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
			----------------------------------------------------------------------------------------------------------------------------------------
			<br>
		@endforeach
    </div>

</body>
</html>