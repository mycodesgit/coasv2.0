<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title></title>
		<link rel="stylesheet" href="">
	</head>
	<style type="text/css">
		body
		{
			font:14px Arial, sans-serif;
		}
		.container
		{
			display: block;
		margin:5%;
		}
		.image-header
		{
			display: block;
			margin:auto;
			margin-left: auto;
		margin-right: auto;
		width:60%;
		}
		.image-header img
		{
		width:60px;
		}
		.image-header .sch-txt
		{
			position: absolute;
			font:bold 14px Arial, sans-serif;
			margin-top: 5px;
			margin-left: 0px;
		}
		.image-header .address-txt
		{
			position: absolute;
			margin-top: 20px;
			margin-left: 30px;
		}
		.picture-header
		{
		width: 130px;
		height: 120px;
		border: 1px solid #000;
		padding: 1px;
		margin: 1px;
		position: absolute;
		margin-top: -60px;
		margin-left: 510px;
		}
		.slip-txt
		{
		margin-top: 50px;
		margin-left: 300px;
		font-weight: bold;
		}
		.container-info
		{
		margin-top: 30px;
		width:100%;
		}
		.name
		{
			width: 100%;
			padding-bottom:10px;
		}
		.name-p
		{
			display: inline-block;
			padding-right: 10px;
			width:auto;
		}
		.name-p-sex
		{
			display: inline-block;
			padding-right: 10px;
			width:auto;
		}
		.name-p-age
		{
			display: inline-block;
			padding-right: 10px;
			width:auto;
		}
		.name-p-bday
		{
			display: inline-block;
			padding-right: 10px;
			width:auto;
		}
		.name-p-c
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			text-transform: uppercase;
			width: 91%;
		}
		.name-p-c-address
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			text-transform: uppercase;
			width: 89%;
		}
		.name-p-c-sex
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			text-transform: uppercase;
			width: 10%;
		}
		.name-p-c-age
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			text-transform: uppercase;
			width: 5%;
		}
		.name-p-c-bday
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 15%;
		}
		.name-p-c-contact
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			text-transform: uppercase;
			width: 20%;
		}
		.name-p-c-schl
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 76%;
		}
		.name-p-c-schltrack
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 63.5%;
		}
		.name-p-c-sucschl
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 66%;
		}
		.name-p-c-course
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 82.5%;
		}
		.name-p-c-course1
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 30.5%;
		}
		.name-p-c-course1
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 30.2%;
		}
		.name-p-c-signatories
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 37%;
			margin-top: 80px;
			float: right;
		}
		.name-p-c-d_test
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 14%;
		}
		.name-p-c-time
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 10%;
		}
		.name-p-c-venue
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 30%;
		}
		.name-p-c-guidance
		{
			border-bottom: 1px solid #000;
			display: inline-block;
			width: 31%;
			text-align: center;
			margin-top: 30px;
			margin-left: 210px;
			font-style: bold;
		}
		.name-p-c-brokenline
		{
			border-bottom: 1px dotted #000;
			display: inline-block;
			width: 100%;
		}
	</style>
	<body>
		<div class="container">
			<div class="image-header">
				<img src="{{ asset('template/img/CPSU_L.png') }}" alt="">
				<p class="sch-txt"><b>CENTRAL PHILIPPINES STATE UNIVERSITY</b></p>
				<p class="address-txt">Kabankalan City, Negros Occidental</p>
			</div>
			<div class="picture-header">
				<img src="storage/capture_images/{{$applicant->image}}" style="width: 130px;height: 120px" alt="">
			</div>
			<p class="slip-txt">ADMISSION SLIP</p>
			<div class="container-info">
				<div class="container-date">
					<p>Date: {{$applicant->created_at->format('F d, Y')}}</p>
					<p style="text-align: right;margin-top: -40px;">Examinee No: {{$applicant->admission_id}}</p>
				</div>
				<div class="name">
					<div class="name-p">Name:</div>
					<div class="name-p-c"><b>{{$applicant->fname}} @if($applicant->mname == null) @else {{ substr($applicant->mname,0,1) }}.@endif {{$applicant->lname}}  @if($applicant->ext == 'N/A') @else{{$applicant->ext}}@endif</b></div></div>
					<div class="name">
						<div class="name-p">Address:</div>
						<div class="name-p-c-address">{{$applicant->address}}</div></div>
						
						<div class="name">
							<div class="name-p-sex">Sex:</div>
							<div class="name-p-c-sex">{{$applicant->gender}}</div>
							<div class="name-p-bday">Age:</div>
							<div class="name-p-c-age">{{$applicant->age }}</div>
							<div class="name-p-bday">Date of Birth:</div>
							<div class="name-p-c-bday">{{ \Carbon\Carbon::parse($applicant->bday)->format('M. d, Y') }}</div>
							<div class="name-p-bday">Contact Number:</div>
							<div class="name-p-c-contact">{{$applicant->contact }}</div>
							<p>For NEW STUDENT</p>
							<div class="name-p-sex">School Last Attended:</div>
							<div class="name-p-c-schl" style="text-transform: uppercase;">{{$applicant->lstsch_attended }}</div>
							<div class="name-p-sex">Senior High School Track/Strand:</div>
							<div class="name-p-c-schltrack" style="text-transform: uppercase;font-size:12px;">@if ($applicant->strand == 'BAM') Business, Accountancy, Management (ABM) @elseif ($applicant->strand == 'HUMSS') Humanities, Education, Social Sciences (HUMSS) @elseif ($applicant->strand == 'GAS') General, Academic Strand (GAS) @elseif ($applicant->strand == 'HESS') Humanities, Education, Social Sciences (HESS) @elseif ($applicant->strand == 'STEM') Science, Technology, Engineering, Mathematics (STEM) @elseif ($applicant->strand == 'TVL-CHF') TVL - Cookery, Home Economics, & FBS (TVL-CHF) @elseif ($applicant->strand == 'TVL-CIV') TVL - CSS, ICT, & VGD (TVL-CIV) @elseif ($applicant->strand == 'TVL-AFA') TVL - Agricultural & Fisheries Arts (TVL-AFA) @elseif ($applicant->strand == 'TVL-EIM') TVL - Electrical Installation & Maintenance (TVL-EIM) @elseif ($applicant->strand == 'TVL-SMAW') TVL - Shielded Metal Arc Welding (TVL-SMAW) @elseif ($applicant->strand == 'OLD') Old Curriculum @endif </div>
							<p>For TRANSFEREE</p>
							<div class="name-p-sex">College/University last attended:</div>
							<div class="name-p-c-sucschl" style="text-transform: uppercase;">{{$applicant->suc_lst_attended }}</div>
							<div class="name-p-sex">Course & Year:</div>
							<div class="name-p-c-course" style="text-transform: uppercase;">{{$applicant->course }}</div>
							<div class="name-p-sex">Course Choice 1:</div>
							<div class="name-p-c-course1">{{$applicant->preference_1 }}</div>
							<div class="name-p-sex">Course Choice 2:</div>
							<div class="name-p-c-course1">{{$applicant->preference_2 }}</div>
							<div class="name-p-c-signatories"></div>
							<p style="margin-left: 430px;margin-top: 65px;font-style: italic;">Signature over Printed Name</p>
							<div class="name-p-bday">Date of Admission Test:</div>
							<div class="name-p-c-d_test">{{ \Carbon\Carbon::parse($applicant->date)->format('M d, Y') }}</div>
							<div class="name-p-bday">Time:</div>
							<div class="name-p-c-time">@if ($applicant->time == NULL) @else {{\Carbon\Carbon::createFromFormat('H:i:s',$applicant->time)->format('h:i A')}}@endif</div>
							<div class="name-p-bday">Venue:</div>
							<div class="name-p-c-venue">{{$applicant->venue }}</div>
							<div class="name-p-c-guidance">SUNE S. QUINTAB</div>
							<p style="margin-left: 130px;margin-top: 5px;font-style: italic;">Guidance Counselor III/Coordinator, Admission Services</p>
							<div class="name-p-c-brokenline"></div>
							<p style="text-align: center;font-style: bold;margin-top: -10px;margin-bottom: 30px;">ADMISSION TEST PERMIT</p>
							<div class="name-p-bday">Date of Admission Test:</div>
							<div class="name-p-c-d_test">{{ \Carbon\Carbon::parse($applicant->date)->format('M d, Y') }}</div>
							<div class="name-p-bday">Time:</div>
							<div class="name-p-c-time">@if ($applicant->time == NULL) @else {{\Carbon\Carbon::createFromFormat('H:i:s',$applicant->time)->format('h:i A')}}@endif</div>
							<div class="name-p-bday">Venue:</div>
							<div class="name-p-c-venue" tyle="text-transform: uppercase;">{{$applicant->venue }}</div>
							<div class="name-p-c-guidance">SUNE S. QUINTAB</div>
							<p style="margin-left: 130px;margin-top: 5px;font-style: italic;">Guidance Counselor III/Coordinator, Admission Services</p>
							<p style="text-align: center;margin-top: 5px;font-style: italic;"><b>Bring <span style="text-decoration: underline">this Permit</span>, <span style="text-decoration: underline">valid ID</span> and <span style="text-decoration: underline">pencil</span> on the Date of Admission Test.</b></p>
							<p style="text-align: center;margin-top: 70px;font-size: 10px;">Doc Control Code:CPSU-F-CGU-01&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Effective Date:09/12/2018&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page No.:1 of 1</p>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</body>
</html>