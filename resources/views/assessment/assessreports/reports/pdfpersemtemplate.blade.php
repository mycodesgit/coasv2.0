<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Account Balance</title>

	<style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Calibri !important;
        }
        table, th, td {
            /*border: 1px solid #e9ecef;*/
        }
        th, td {
            padding: 2px;
            text-align: left;
        }
        td {
            font-size: 9pt;
        }
        .studinfolabel {
            text-align: left;
            font-size: 10pt;
            font-family: sans-serif;
        }
        .studinfoID {
            margin-left: 30px;
        }
    </style>
</head>
<body>
	<div align="center" style="margin-top: -30px">
        @if(Auth::guard('web')->user()->campus == 'MC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderMain.png') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'VC')
            <img src="{{ public_path('template/img/reportcard/reportcardheaderVic.jpg') }}" width="80%">
        @endif
    </div>

    <div class="studinfolabel" style="margin-top: 25px">
        <span style="font-weight: bold;">STUDENT ID NO.:</span> <span class="studinfoID"><strong> {{ $studfees->first()->studID }}</strong></span>
        <span style="font-weight: bold; text-align: right !important; margin-left: 300px;">DATE:</span> <span><strong>{{ strtoupper(\Carbon\Carbon::now()->format('F j, Y')) }}</strong></span>
    </div> 

</body>
</html>