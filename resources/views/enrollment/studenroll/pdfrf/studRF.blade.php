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
			margin-left: 30px;
		}
		.studinfoCourse {
			margin-left: 10px;
		}
		.studinfoAcadYear {
			margin-left: 76px;
		}
		.studinfoenstat {
			margin-left: 30px;
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
    	<span style="font-weight: bold;">Student ID No.:</span> <span class="studinfoID">2023-0001-K</span>
    </div>

    <div class="studinfolabel">
    	<span style="font-weight: bold;">Name:</span> <span class="studinfoName">JOSHUA KYLE L. DALMACIO</span>
    	<span style="font-weight: bold; text-align: right !important; margin-left: 100px;">Scholarship:</span> <span class="studinfoScholar">None</span>
    </div>

    <div class="studinfolabel">
    	<span style="font-weight: bold;">Curriculum, Yr. & Section:</span> <span class="studinfoCourse">BSIT 1-A</span>
    </div>

    <div class="studinfolabel">
    	<span style="font-weight: bold;">Academic Year:</span> <span class="studinfoAcadYear">2023-2024</span>
    	<span style="font-weight: bold; text-align: right !important; margin-left: 110px;">Enrollment Status:</span> <span class="studinfoenstat">Second Semester</span>
    </div>

    <div >
    	<table id="table">
    		<tbody>
    			<tr>
    				<td rowspan="" width="7%"><img src="{{ asset('template/img/subsec.png') }}" width="50%" style="margin-left: 10px;"></td>
    				<td>
    					<table id="table-inside">
    						<thead>
    							<tr>
    								<th width="15%">Subj Code</th>
    								<th>Subject Name</th>
    								<th width="30%">Descriptive Title</th>
    								<th width="8%">Credit</th>
    								<th width="8%">Lec Fee</th>
    								<th width="8%">Lab Fee</th>
    							</tr>
    						</thead>
    						<tbody>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-123</td>
    								<td>Prof. Ed. 4 - BSED-ENG 1-A</td>
    								<td>Special Topic 1</td>
    								<td class="cred-lec-lab">3</td>
    								<td class="cred-lec-lab">540</td>
    								<td class="cred-lec-lab">0</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    							<tr>
    								<td style="padding-left: 10px;">KAB-EDU-153</td>
    								<td>ST 1 - BSED-ENG 1-A</td>
    								<td>The Teaching Profession</td>
    								<td class="cred-lec-lab">6</td>
    								<td class="cred-lec-lab">1080</td>
    								<td class="cred-lec-lab">500</td>
    							</tr>
    						</tbody>
    					</table>
    					<br><br><br>
    				</td>
    			</tr>
    		</tbody>
    	</table>

    	<div class="studinfolabel">
			<span style="font-weight: bold;">Posted By:</span> Admin Admin
		</div>

    	<table id="table" style="margin-top: 40px !important">
    		<tbody>
    			<tr>
    				<td width="7%"><img src="{{ asset('template/img/cashsec.png') }}" width="50%" style="margin-left: 10px;"></td>
    				<td colspan="2" style="border-right: none !important" width="35%">
    					<div class="studinfolabel">
							<span style="font-weight: bold;">StudID:</span>
						</div>
						<div class="studinfolabel">
							<span style="font-weight: bold;">Appraised Amount:</span>
						</div>
    					<table id="table-inside-cash-left">
    						<thead>
    							<tr>
    								<th>Account</th>
    								<th width="30%">Amount</th>
    							</tr>
    						</thead>
    						<tbody>
    							<tr>
    								<td>ATHLETIC FEE</td>
    								<td class="accnt-amount">730.00</td>
    							</tr>
    							<tr>
    								<td>COMPUTER FEE</td>
    								<td class="accnt-amount">500.00</td>
    							</tr>
    							<tr>
    								<td>CULTURAL FEE</td>
    								<td class="accnt-amount">200.00</td>
    							</tr>
    							<tr>
    								<td>DEVELOPMENT FEE</td>
    								<td class="accnt-amount">1,725.00</td>
    							</tr>
    							<tr>
    								<td>GUIDANCE FEE</td>
    								<td class="accnt-amount">70.00</td>
    							</tr>
    							<tr>
    								<td>LIBRARY FEE</td>
    								<td class="accnt-amount">300.00</td>
    							</tr>
    							<tr>
    								<td>MEDICAL FEE</td>
    								<td class="accnt-amount">200.00</td>
    							</tr>
    							<tr>
    								<td>REGISTRATION FEE FEE</td>
    								<td class="accnt-amount">100.00</td>
    							</tr>
    							<tr>
    								<td>TUITION FEE</td>
    								<td class="accnt-amount">4,320.00</td>
    							</tr>
    						</tbody>
    					</table>
    					<br><br><br>
    				</td>
    				<td style="border-left: none !important" width="55%">
    					<div class="studinfolabel">
							<span style="font-weight: bold;">Amount Paid:</span>
						</div>
						<div class="studinfolabel">
							<span style="font-weight: bold;">Posted By:</span>
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
    								<td>ATHLETIC FEE</td>
    								<td class="accnt-amount">2024-20-05</td>
    								<td class="accnt-or">5412581</td>
    								<td class="accnt-amount">730.00</td>
    							</tr>
    						</tbody>
    					</table>
    				</td>
    			</tr>
    			<tr>
    				<td colspan="4" style="padding-left: 5px">Balance for the SECOND SEMESTER SY 2023-2024 as of 23/02/2024 is: Php 8, 145.00</td>
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