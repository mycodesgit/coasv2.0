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
	<header >
        <img src="{{ asset('template/img/studrfheader.png') }}" width="72%" style="margin-top: -30px; margin-left: -20px; text-align: left;">
        <div class="textdoccode">
        	Doc Control Code:&nbsp;&nbsp;&nbsp; CPSU-F-REG-13<br>
        	Effective Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 9/12/2018<br>
        	Revision No.:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 00<br>
        	Page No.:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 1 of 1<br>
        </div>
    </header>

    <h3 class="titlecenter">
    	REGISTRATION FORM
    </h3>

    <div class="studinfolabel">
    	<span style="font-weight: bold;">Student ID No.:</span> <span class="studinfoID">{{ $student->studentID }}</span>
    </div>

    <div class="studinfolabel">
    	<span style="font-weight: bold;">Name:</span> <span class="studinfoName">{{ $student->fname }} {{ substr($student->mname, 0, 1) }} {{ $student->lname }}</span>
    	<span style="font-weight: bold; text-align: right !important; margin-left: 10px;">Scholarship:</span> <span class="studinfoScholar">{{ $student->scholar_name}}</span>
    </div>

    <div class="studinfolabel">
    	<span style="font-weight: bold;">Curriculum, Yr. & Section:</span> <span class="studinfoCourse">{{ $student->course }}</span>
    </div>

    <div class="studinfolabel">
    	<span style="font-weight: bold;">Academic Year:</span> <span class="studinfoAcadYear">{{ $student->schlyear }}</span>
    	<span style="font-weight: bold; text-align: right !important; margin-left: 110px;">Enrollment Status:</span>
    	<span class="studinfoenstat">
    		@if(request('semester') == '1')
		        First Semester
		    @elseif(request('semester') == '2')
		        Second Semester
		    @elseif(request('semester') == '3')
		        Summer
		    @else
		        Unknown Semester
		    @endif
    	</span>
    </div>

    <div >
    	<table id="table">
    		<tbody>
    			<tr>
    				<td rowspan="" width="4%"><img src="{{ asset('template/img/subsec.png') }}" width="50%" style="margin-left: 5px; padding-top: 55px;"></td>
    				<td>
    					<table id="table-inside">
    						<thead>
    							<tr>
    								<th width="15%">Subj Code</th>
    								<th>Subject Name</th>
    								<th width="35%" style="padding-left: 10px !important;">Descriptive Title</th>
    								<th width="7%">Credit</th>
    								<th width="7%">Lec Fee</th>
    								<th width="7%">Lab Fee</th>
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
	    								<td>{{ $sub->sub_title }}</td>
	    								<td class="cred-lec-lab">{{ $sub->subUnit }}</td>
	    								<td class="cred-lec-lab">{{ $sub->lecFee }}</td>
	    								<td class="cred-lec-lab">{{ $sub->labFee }}</td>
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
								    <td colspan="3">{{ $totalsubUnit }}</td>
								    <td class="cred-lec-lab">{{ $totalLecFee }}</td>
								    <td class="cred-lec-lab">{{ $totalLabFee }}</td>
								</tr>
    						</tbody>
    					</table>
    					<br><br><br>
    				</td>
    			</tr>
    		</tbody>
    	</table>

    	<div class="studinfolabel">
			<span style="font-weight: bold;">Posted By:</span> {{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }}
		</div>

    	<table id="table" style="margin-top: 40px !important">
    		<tbody>
    			<tr>
    				<td width="4%"><img src="{{ asset('template/img/cashsec.png') }}" width="50%" style="margin-left: 5px; padding-top: 55px;"></td>
    				<td colspan="2" style="border-right: none !important" width="35%">
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
    							<tr>
    								<td></td>
    								<td class="accnt-amount"></td>
    								<td class="accnt-or"></td>
    								<td class="accnt-amount"></td>
    							</tr>
    						</tbody>
    					</table>
    				</td>
    			</tr>
    			<tr>
    				<td colspan="4" style="padding-left: 5px">Balance for the SECOND SEMESTER SY {{ $student->schlyear }} as of {{ \Carbon\Carbon::parse($student->updated_at)->format('d/m/Y'); }} is: {{ number_format($totalBalanceFee, 2) }}</td>
    			</tr>
    		</tbody>
    	</table>

    	<table id="table" style="margin-top: 20px !important">
    		<thead>
    			<tr>
    				<th class="signatories-rf" width="30%">Adviser and Checker/Date</th>
    				<th class="signatories-rf" width="35%">Dean/Date</th>
    				<th class="signatories-rf" width="30%">Accounting/Assessment/Date</th>
    			</tr>
    		</thead>
    		<tbody>
    			<tr>
    				<td class="signatories-rf-sign"></td>
    				<td class="signatories-rf-sign"></td>
    				<td class="signatories-rf-sign"></td>
    			</tr>
    		</tbody>
    	</table>

    	<div class="terms-rf">
    		As an applicant for Central Philippines State University, this term, I hereby promise to abide by the rules and regulations of the University, now in force as well as the riles and regulations that maybe promulgated by the University from time to time. If I violate any of the University rules and standards I shall bind myself to whatever disciplinary action the University may impose upon me.
    	</div>

    	<div class="date-sign-rf">
    		Date: ____________________
    		<span class="rf-stud-sign">Signature: ______________________</span>
    	</div>

    	<div class="footer-logo-container">
		    <img src="{{ asset('template/img/footerLogo.png') }}" class="footer-logo" width="100%">
		</div>
    </div>

</body>
</html>