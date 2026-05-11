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
			margin-left: 80px;
		}
		.studinfoName {
			margin-left: 135px;
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
		.table-responsive{
			width: 100%;
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
		}

		#table{
			margin-top: 10px;
			font-family: Arial;
			border-collapse: collapse;
			width: 100%;
			min-width: 700px; /* prevents columns from collapsing */
		}

		#table td{
			vertical-align: top !important;
			text-align: left;
			border: 1px solid #000;
			font-size: 9pt;
			padding: 4px;
		}

		#table th{
			border: 1px solid #000;
			padding: 4px;
			white-space: nowrap;
		}

		#table-inside{
			margin-top: 10px;
			font-family: Arial;
			border-collapse: collapse;
			width: 100%;
			border: 1px solid #fff;
		}

		#table-inside td,
		#table-inside th{
			border: 1px solid #fff;
		}

		#table-inside td{
			font-family: monospace;
		}

		/* Mobile */
		@media screen and (max-width: 768px){

			#table td,
			#table th{
				font-size: 8pt;
			}

			#table{
				min-width: 650px;
			}
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

    <div>
    	<div class="table-responsive">
    		<table id="table">
				<thead>
					<tr>
						<th width="15%">Subj Code</th>
						<th>Subject Name</th>
						<th @if(Auth::guard('kioskstudent')->user()->role == 15) width="32%" @elseif(Auth::guard('kioskstudent')->user()->role != 15) width="35%" @endif style="padding-left: 10px !important;">Descriptive Title</th>
						<th width="6%">Credit</th>
						<th width="7%">Lec Fee</th>
						<th width="7%">Lab Fee</th>
						@if(Auth::guard('kioskstudent')->user()->role == 15 || Str::contains($student->studentID, '-G')) 
						<th width="">Cycle</th>
						@endif
					</tr>
				</thead>
				<tbody>
					@php
						$totalsubUnit = 0;
						$totalLecFee = 0;
						$totalLabFee = 0;
					@endphp
					@foreach($studsub as $sub)
						<tr>
							<td style="padding-left: 7px;">{{ $sub->subCode }}</td>
							<td>{{ $sub->sub_name }}-{{ $sub->subSec }}</td>
							<td>{{ strlen($sub->sub_title) > 30 ? substr($sub->sub_title, 0, 30) . '...' : $sub->sub_title }}</td>
							<td class="cred-lec-lab">{{ $sub->subUnit }}</td>
							<td class="cred-lec-lab">{{ $sub->lecFee }}</td>
							<td class="cred-lec-lab">{{ $sub->labFee }}</td>
							@if(Auth::guard('kioskstudent')->user()->role == 15 || Str::contains($student->studentID, '-G'))
								<td class="cred-lec-lab">{{ substr($sub->isType, 0, 3) }}</td>
							@endif
						</tr>
						@php
							$totalsubUnit += $sub->subUnit;
							$totalLecFee += $sub->lecFee;
							$totalLabFee += $sub->labFee;
						@endphp
					@endforeach
					<tr>
						<td>************</td>
						<td>************************</td>
						<td>********************************</td>
						<td colspan="3" style="padding-left: 15px">*****************</td>
					</tr>
					<tr>
						<td colspan="3"></td>
						<td class="cred-lec-lab">{{ $totalsubUnit }}</td>
						<td class="cred-lec-lab">{{ $totalLecFee }}</td>
						<td class="cred-lec-lab">{{ $totalLabFee }}</td>
					</tr>
				</tbody>
			</table>
			
			<table id="table">
				<tbody>
					<tr>
						<td colspan="2" style="border-right: none !important" width="40%">
							<div class="studinfolabel">
								<span style="font-weight: normal;">StudID: {{ request('stud_id') }}</span>
							</div>
							<div class="studinfolabel">
								<span style="font-weight: normal;">Appraised Amount:</span>
							</div>
							<table id="table-inside-cash-left">
								<thead>
									<tr>
										<th>Account</th>
										<th width="30%">Amount</th>
									</tr>
								</thead>
								<tbody>
									@php
										$totalBalanceFee = 0;
									@endphp
									@foreach($studfees as $fees)
										<tr>
											<td>{{ $fees->account }}</td>
											<td class="accnt-amount">{{ number_format($fees->amount, 2) }}</td>
										</tr>
										@php
											$totalBalanceFee += $fees->amount;
										@endphp
									@endforeach
									<tr>
										<td colspan="1"></td>
										<td class="accnt-amount">{{ number_format($totalBalanceFee, 2) }}</td>
									</tr>
								</tbody>
							</table>
							<br><br><br>
						</td>
						<td style="border-left: none !important" width="55%">
							<div class="studinfolabel">
								<span style="font-weight: normal;">Amount Paid:</span>
							</div>
							<div class="studinfolabel">
								<span style="font-weight: normal;">Posted By:</span>
							</div>
							<table id="table-inside-cash-right">
								<thead>
									<tr>
										<th>Account</th>
										<th width="20%">Date Paid</th>
										<th width="20%">OR No</th>
										<th width="20%">Amount</th>
									</tr>
								</thead>
								<tbody>
									@php
										$totalPaidFee = 0;
									@endphp
									@foreach($studor as $feesor)
									<tr>
										<td>{{ $feesor->account }}</td>
										<td class="accnt-amount">{{ $feesor->datepaid }}</td>
										<td class="accnt-or">{{ $feesor->orno }}</td>
										<td class="accnt-orpaid">{{ number_format($feesor->amountpaid, 2) }}</td>
									</tr>
									@php
										$totalPaidFee += $feesor->amountpaid;
									@endphp
									@endforeach
									<tr>
										<td colspan="3"></td>
										<td class="accnt-orpaid">{{ number_format($totalPaidFee, 2) }}</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="padding-left: 5px">Balance for the @if($student->semester == 1) <span class="badge badge-primary">FIRST SEMESTER</span> @elseif($student->semester == 2) <span class="badge badge-success">2ND SEMESTER</span> @elseif($student->semester == 3) <span class="badge badge-secondary">SUMMER</span> @endif SY {{ $student->schlyear }} {{ \Carbon\Carbon::now()->format('F j, Y') }}: {{ number_format($totalBalanceFee - $totalPaidFee, 2) }}</td>
					</tr>
				</tbody>
			</table>
		</div>
    </div>

</body>
</html>