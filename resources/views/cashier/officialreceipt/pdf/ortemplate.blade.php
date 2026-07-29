<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title></title>

	<style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Calibri !important;
            margin: 1mm;
        }
        table, th, td {
            border: 1px solid #ffffff;
        }
        th, td {
            padding: 2px;
            text-align: left;
        }
        td {
            font-size: 10pt;
        }
        @page {
            margin: 3mm;
        }
        .studinfolabel {
            text-align: left;
            font-size: 10pt;
            font-family: sans-serif;
        }
        .studinfoID {
            margin-left: 30px;
        }
        /* Styling for screen view */
        .screen-only {
            display: block;
        }

        /* Styling for print view */
        @media print {
            body * {
                visibility: hidden;
            }

            /* Elements to print */
            .print-only, .print-only * {
                visibility: visible;
            }

            /* Ensure only the necessary data is printed */
            .print-only {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                background-color: white; /* Ensure background is white */
            }

            /* Hide all borders and layout elements */
            table, th, td {
                border: none !important;
            }
        }
    </style>
</head>
<body>
	<div class="no-print">
		<table>
			<thead>
				<tr>
					<th>
						<div align="center" style="margin-top:">
				        	{{-- <img src="{{ public_path('template/img/cashier/orheader.png') }}" width="95%" height="13%"> --}}
				        	<br><br><br><br><br>
				    	</div>
				    </th>
				</tr>
			</thead>
		</table>
		<table>
			<thead>
				<tr>
					<th style="font-size: 10pt; font-family: unset; text-align: center; color: #fff !important;" width="50%">Accountable From No. 51<br>(Revised January 1992)</th>
					<th rowspan="2"><center><span style="font-size: 10pt; font-family: unset; text-align: center !important; color: #fff !important;">ORIGINAL</span></center><br><span style="text-align: left; color: #fff !important;">No.</span></th>
				</tr>
				<tr>
					<th style="font-family: 'monospace'; font-weight: bold; text-align: left;"><span style="color: #fff !important;">DATE</span><span style="margin-left: 60px; margin-top: 30px">{{ \Carbon\Carbon::now()->format('M j, Y') }}</span><br><br><br></th>
				</tr>
			</thead>
		</table>
		<table>
			<thead>
				<tr>
					<th colspan="2" style="font-weight: bold; font-family: 'monospace';"><span style="color: #fff !important; margin-left: 30px;">Campus</span>{{ Auth::guard('web')->user()->campus }} 
						@if($studor->first()->semester == 1)
						    1st Sem
						@elseif($studor->first()->semester == 2)
						    2nd Sem
						@elseif($studor->first()->semester == 3)
						    Summer
						@endif
						- {{ $studor->first()->schlyear }}
						<br><br>
					</th>
					<th style="font-weight: bold; font-family: 'monospace';"><span style="color: #fff !important; margin-left: -40px;">Fund</span>@if($studor->first()->account == 'YEARBOOK FEE')TF @else IGF @endif<br><br></th>
				</tr>
				<tr>
					<th colspan="3" style="font-weight: bold; margin-left: ; font-family: 'monospace';"><span style="color: #fff !important;">Payor</span><span style="margin-left: 60px;">@if(request('r3') === 'off') {{ request('mynames') }} @else{{ $studor->first()->fname }} {{ $studor->first()->lname }}@endif</span><br><br></th>
				</tr>
				<tr>
					<th style="font-size: 1pt; font-family: sans-serif; text-align: center; font-weight: thin; color: #fff !important;">NATURE OF<br> COLLECTION</th>
					<th style="font-size: 1pt; font-family: unset; text-align: left !important; font-weight: thin; color: #fff !important;">ACCOUNT CODE</th>
					<th width="35%" style="font-size: 1pt; font-family: unset; text-align: left !important; font-weight: thin; color: #fff !important;"><span class="hidden">AMOUNT</span></th>
				</tr>
			</thead>
			<tbody>
				@php
					use NumberToWords\NumberToWords;

				    $totalAmount = $studor->sum('amountpaid');
				    $numberToWords = new NumberToWords();
				    $numberTransformer = $numberToWords->getNumberTransformer('en');

				    $totalInWords = $numberTransformer->toWords($totalAmount);
				    $rowCount = 0; 
    				$maxRows = 12;
    				$totalamnt = 0;
				@endphp
				@foreach($studor as $orfees)
				    @if($rowCount < $maxRows)
				        <tr class="">
				            <td style="font-weight: bold; text-align: right; font-family: 'monospace;', sans-serif;">{{ $orfees->account }}</td>
				            <td style="font-weight: bold; text-align: center; font-family: 'monospace;', sans-serif;">{{ $orfees->fund }}</td>
				            <td style="font-weight: bold; font-family: 'monospace;', sans-serif; text-align: left !important;"><span style="text-align: left;">{{ number_format($orfees->amountpaid, 2) }}</span></td>
				        </tr>
				        @php
				            $rowCount++;
				            $totalamnt += $orfees->amountpaid; 
				        @endphp
				    @endif
			    @endforeach

			    @while($rowCount < $maxRows)
				    <tr class="print-only">
				        <td style="font-weight: bold; text-align: right; font-family: 'monospace;', sans-serif;">&nbsp;</td>
				        <td style="font-weight: bold; text-align: center; font-family: 'monospace;', sans-serif;">&nbsp;</td>
				        <td style="font-weight: bold; font-family: 'monospace;', sans-serif;">&nbsp;</td>
				    </tr>
				    @php
				        $rowCount++;
				    @endphp
			    @endwhile
			</tbody>
		</table>
	</div>
	<div style="font-weight: bold; margin-left: 350px !important; margin-top: -30px; text-align: left !important; font-family: 'monospace';">{{ number_format($totalamnt, 2) }}</div>
	<div style="font-weight: bold; margin-left: 100px; margin-top: 10px; text-align: left; font-family: 'sans-serif;'">{{ ucfirst($totalInWords) }} pesos only</div>
</body>
</html>