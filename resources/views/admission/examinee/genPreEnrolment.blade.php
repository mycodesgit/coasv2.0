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
  margin-top: -10px;
  margin-left: 250px;
  font-weight: bold;
}
.status
{
	display: inline-block;
	font-weight: bold;
	margin-left: 160px;
	word-spacing: 10px;
	margin-top: 20px;
}
.name .checkbox
{
	margin:auto;
	margin:10px;
	display: inline;
}
input.checkbox 
{
	 transform: scale(1.1);
     margin: 10px;
     display: inline;
}
.name
{
	width: 100%;
	padding-bottom:10px;
	margin-top: 20px;
	font-size: 12px;
}
.name-p
{
	 display: inline-block;
	 padding-right: 10px;
}
.name-sex
{
	 display: inline-block;
	 width:8%;
}
.name-p-c
{
	border-bottom: 1px solid #000;
	display: inline-block;
	text-transform: uppercase;
	width: 56%;
}
.name-p-sex
{
	border-bottom: 1px solid #000;
	display: inline-block;
	text-transform: uppercase;
}
.name-p-s
{
	 display: inline-block;
	 padding-right: 10px;
	 margin-left: 20px;
	 font-style: italic;
}
.name-p-s-left
{
	 margin-left: 50px;
}
.name-p-address
{
	border-bottom: 1px solid #000;
	display: inline-block;
	width:70%;
}
.name-p-signatories
{
	border-bottom: 1px solid #000;
	display: inline-block;
	text-transform: uppercase;
}
</style>

<body>
	<div class="container">
		<div class="image-header">
	     <img src="{{ asset('template/img/CPSU_L.png') }}" alt="">
	    	<p class="sch-txt"><b>CENTRAL PHILIPPINES STATE UNIVERISTY</b></p>
	    	<p class="address-txt">Kabankalan City, Negros Occidental</p>
	    </div>
	    <div class="picture-header">
	    	<img src="storage/capture_images/{{$examinee->image}}" style="width: 130px;height: 120px;" alt="">
	    </div>
	    <p class="slip-txt">PRE-ENROLLMENT FORM</p>

	    <label class="status">
	      <input type="checkbox" class="checkbox" value="1" {{ old('type', $examinee->type) === '1' ? 'checked' : '' }}> <span>New</span>
	      <input type="checkbox" class="checkbox" value="2" {{ old('type', $examinee->type) === '2' ? 'checked' : '' }}> <span>Returnee</span>
	      <input type="checkbox" class="checkbox" value="3" {{ old('type', $examinee->type) === '3' ? 'checked' : '' }}> <span>Transferee</span>
	    </label>

	    <div class="name">
	    	<div class="name-p">Name:</div>
	    	<div class="name-p-c">
	    	    <b style="margin-left: 20px; margin-right: 15px;">
                    {{$examinee->lname}} 
	    	    </b>
	    	    <b style="margin-left: 30px; margin-right: 20px;">
                    {{$examinee->fname}}
	    	    </b>
	    	    <b style="margin-left: 40px">	
	    			@if($examinee->mname == null) 
	    				@else {{ substr($examinee->mname,0,1) }}.
	    			@endif   

	    			@if($examinee->ext == 'N/A') 
	    				@else{{$examinee->ext}}
	    			@endif 
	    	    </b>
	    	</div>
	    	<div class="name-sex">Sex:</div>
	    	<div class="name-p-sex"><b>{{$examinee->gender}}</b></div>
	   	 	<div class="name-p" style="margin-left: 10px;">Age:</div>
	   	 	<div class="name-p-sex"><b>{{$examinee->age }}</b></div>
	   	 	<div class="name-p-s-left">
		   	 	<div class="name-p-s">Family Name</div>
		   	 	<div class="name-p-s">Given Name</div>
		   	 	<div class="name-p-s">Middle Name</div>
		   	 	<div class="name-p" style="margin-left: 40px;">Date of Birth:</div>
		   	 	<div class="name-p-sex" style="width:19%;"><b>{{$examinee->bday}}</b></div>
	   	 	</div>
            <br>
	   	 	<div class="name-p">Complete Home Address:</div>
	    	<div class="name-p-address" style="text-transform: uppercase;"><b>{{$examinee->address}}</b></div>
            <br><br>
	    	<div class="result">
	    		<div class="name-p"><b>Admission Test Result:</b></div>
	    		<div class="name-p" style="margin-left: 0px;">Test Date:</div>
	    		<div class="name-p" style="margin-left: -10px;text-decoration: underline;">{{$examinee->d_admission}}</div>
	    		<div class="name-p" style="margin-left: 10px;">Test Venue:</div>
	    		<div class="name-p" style="margin-left: -10px; text-decoration: underline;">{{$examinee->venue}}</div><br>
	    		<div class="name-p" style="margin-left: 150px;">Raw Score:</div>
	    		<div class="name-p" style="margin-left: -10px; text-decoration: underline;">{{$examinee->result->raw_score}}</div>
	    		<div class="name-p" style="margin-left: 50px;">Percentile:</div>
	    		<div class="name-p" style="margin-left: -10px;text-decoration: underline;">{{$examinee->result->percentile}}</div>
	    	</div>
	    </div>
	    	<div class="name-p-signatories" style="width: 32%;text-align: center;font-size: 10px;"><b>SUNE S. QUINTAB</b></div>
	    	<div class="name-p-signatories" style="width: 32%;text-align: center;font-size: 10px;"><b>COROLD A. ROMANO</b></div>
	    	<div class="name-p-signatories" style="width: 32%;text-align: center;font-size: 10px;"><b>KRISTINE C. DURAN, MAN</b></div>
	    	<div class="name-p-signatories" style="width: 32%;text-align: center;border-bottom: none;margin-top: -60px;font-size: 10px;"><b>Guidance Counselor III</b></div>
	    	<div class="name-p-signatories" style="width: 32%;text-align: center;border-bottom: none;margin-top: -60px;font-size: 10px;"><b>Chief Security Officer</b></div>
			<div class="name-p-signatories" style="width: 32%;text-align: center;border-bottom: none;margin-top: -60px;font-size: 10px;"><b>Nurse II</b></div>
	 	<br>	
	 		<div style="text-align: center;border-bottom: none;margin-top: 10px;font-size: 10px;"><b>APPLICATION FOR ENROLLMENT TO:</b></div>
	 		<div style="text-align: left;border-bottom: none;margin-top: 10px;font-size: 10px;"><b>COLLEGE OF AGRICULTURE and FORESTRY (CAF)</b></div>


	      <ul style="list-style-type: none;margin: 0;padding: 0;font-size: 10px;">
		      <li><input type="checkbox" class="checkbox" value="BSAB" {{ old('type', $examinee->interview->course) === 'BSAB' ? 'checked' : '' }}><span> Bachelor of Science in Agribusiness (BSAB)</span></li>
		      <li><input type="checkbox" class="checkbox" value="BSAGRI-As" {{ old('type', $examinee->interview->course) === 'BSAGRI-As' ? 'checked' : '' }}> <span>Bachelor of Science in Agriculture major in Animal Science</span></li>
		      <li><input type="checkbox" class="checkbox" value="BSAGRI-Cs" {{ old('type', $examinee->interview->course) === 'BSAGRI-Cs' ? 'checked' : '' }}> <span>Bachelor of Science in Agriculture major in Crop Science</span></li>
		      <li><input type="checkbox" class="checkbox" value="BSF" {{ old('type', $examinee->interview->course) === 'BSF' ? 'checked' : '' }}> <span>Bachelor of Science in Forestry (BSF)</span></li>
		      <li><input type="checkbox" class="checkbox" value="BST" {{ old('type', $examinee->interview->course) === 'BST' ? 'checked' : '' }}> <span>Bachelor in Sugar Technology (BST)</span></li>
	      </ul>

	      <div style="text-align: left;border-bottom: none;margin-top: 10px;font-size: 10px;"><b>COLLEGE OF ARTS AND SCIENCES (CAS)</b></div>
	      <ul style="list-style-type: none;margin: 0;padding: 0;font-size: 10px;">
		      <li><input type="checkbox" class="checkbox" value="BS-Stat" {{ old('type', $examinee->interview->course) === 'BS-Stat' ? 'checked' : '' }}> <span> Bachelor of Science in Statistics (BS Stat)</span></li>
		      <li><input type="checkbox" class="checkbox" value="ABEL" {{ old('type', $examinee->interview->course) === 'ABEL' ? 'checked' : '' }}> <span> Bachelor of Arts in English Language (ABEL)</span></li>
		      <li><input type="checkbox" class="checkbox" value="BASS" {{ old('type', $examinee->interview->course) === 'BASS' ? 'checked' : '' }}> <span> Bachelor of Arts in Social Science (AB Soc Sci)</span></li>
	      </ul>

	      <div style="text-align: left;border-bottom: none;margin-top: 10px;font-size: 10px;"><b>COLLEGE OF BUSINESS AND MANAGEMENT (CBM)</b></div>
	      <ul style="list-style-type: none;margin: 0;padding: 0;font-size: 10px;">
		      <li><input type="checkbox" class="checkbox" value="BSHM" {{ old('type', $examinee->interview->course) === 'BSHM' ? 'checked' : '' }}><span> Bachelor of Science in Hospitality Management (BSHM)</span></li>
	      </ul>

	      <div style="text-align: left;border-bottom: none;margin-top: 10px;font-size: 10px;"><b>COLLEGE OF COMPUTER STUDIES (CCS)</b></div>
	      <ul style="list-style-type: none;margin: 0;padding: 0;font-size: 10px;">
		      <li><input type="checkbox" class="checkbox" value="BSIT" {{ old('type', $examinee->interview->course) === 'BSIT' ? 'checked' : '' }}><span> Bachelor of Science in Information Technology (BSIT)</span></li>
	      </ul>

	      <div style="text-align: left;border-bottom: none;margin-top: 10px;font-size: 10px;"><b>COLLEGE OF CRIMINAL JUSTICE EDUCATION (CCJE)</b></div>
	      <ul style="list-style-type: none;margin: 0;padding: 0;font-size: 10px;">
		      <li><input type="checkbox" class="checkbox" value="BSCrim" {{ old('type', $examinee->interview->course) === 'BSCrim' ? 'checked' : '' }}><span> Bachelor of Science in Criminology (BSCrim)</span></li>
	      </ul>

	      <div style="text-align: left;border-bottom: none;margin-top: 10px;font-size: 10px;"><b>COLLEGE OF ENGINEERING (COE)</b></div>
	      <ul style="list-style-type: none;margin: 0;padding: 0;font-size: 10px;">
		      <li><input type="checkbox" class="checkbox" value="BSABE" {{ old('type', $examinee->interview->course) === 'BSABE' ? 'checked' : '' }}><span> Bachelor of Science in Agricultural and Biosystems Engineering (BSABE)</span></li>
		      <li><input type="checkbox" class="checkbox" value="BSEE" {{ old('type', $examinee->interview->course) === 'BSEE' ? 'checked' : '' }}><span> Bachelor of Science in Electrical Engineering (BSEE)</span></li>
		      <li><input type="checkbox" class="checkbox" value="BSME" {{ old('type', $examinee->interview->course) === 'BSME' ? 'checked' : '' }}><span> Bachelor of Science in Mechanical Engineering (BSME)</span></li>
	      </ul>

	      <div style="text-align: left;border-bottom: none;margin-top: 10px;font-size: 10px;"><b>COLLEGE OF TEACHER EDUCATION (COTED)</b></div>
	      <ul style="list-style-type: none;margin: 0;padding: 0;font-size: 10px;">
		      <li><input type="checkbox" class="checkbox" value="BECED" {{ old('type', $examinee->interview->course) === 'BECED' ? 'checked' : '' }}><span> Bachelor of Early Childhood Education (BECED)</span></li>
		      <li><input type="checkbox" class="checkbox" value="BEED-GE" {{ old('type', $examinee->interview->course) === 'BEED-GE' ? 'checked' : '' }}><span> Bachelor of Elementary Education Major in General Education (BEED-GE)</span></li>
		      <li><input type="checkbox" class="checkbox" value="BPED" {{ old('type', $examinee->interview->course) === 'BPED' ? 'checked' : '' }}> <span>Bachelor of Physical Education (BPED) major in Physical Education</span></li>
		      <li><input type="checkbox" class="checkbox" value="BSED" {{ old('type', $examinee->interview->course) === 'BSED' ? 'checked' : '' }}><span> Bachelor of Secondary Education major in:</span></li>
		      <li style="margin-left: 20px;"> <input type="checkbox" class="checkbox" value="BSED - English" {{ old('type', $examinee->interview->course) === 'BSED - English' ? 'checked' : '' }}> <span> English</span> <input type="checkbox" class="checkbox" value="BSED - Filipino" {{ old('type', $examinee->interview->course) === 'BSED - Filipino' ? 'checked' : '' }}> <span>Filipino</span> <input type="checkbox" class="checkbox" value=""> <span>Math</span> <input type="checkbox" class="checkbox" value="BSED - Science" {{ old('type', $examinee->interview->course) === 'BSED - Science' ? 'checked' : '' }}> <span>Science</span></li>
	      </ul>

	      <br />
	      <div class="name-p" style="font-size: 12px;">Interviewed by: <span style="text-decoration: underline;">{{$examinee->interview->interviewer }}</span></div>
	      <div class="name-p" style="font-size: 12px;margin-left:100px;">Rating: <span style="text-decoration: underline;">{{$examinee->interview->rating }}</span></div>
	      <div class="name-p" style="font-size: 12px;margin-left:104px;">Date: <span style="text-decoration: underline;">{{ Carbon\Carbon::parse($examinee->interview->created_at)->format('F j, Y') }}</span></div>
	      <br />
	      <div class="name-p" style="font-size: 12px;"><b>APPROVED FOR ENROLLMENT:</b> _______________________________________</div>
	      <div class="name-p" style="font-size: 12px;">Date: _________</div>
	      <div class="name-p" style="margin-left:225px;font-size: 10px;">Name and Signature of the College Dean</div>

	      <p style="text-align: center;margin-top: 20px;font-size: 10px;">Doc Control Code:CPSU-F-CGU-01&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Effective Date:09/12/2018&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page No.:1 of 1</p>
	</div>
    </div>
    </div>
	</div>
</body>
</html>