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
            border: 1px solid #fff;
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
					<th style="font-size: 10pt; font-family: unset; text-align: left;"><span style="color: #fff !important;">DATE</span><br><br><br></th>
				</tr>
			</thead>
		</table>
		<table>
			<thead>
				<tr>
					<th colspan="2" style="font-weight: thin;"><span style="color: #fff !important;">Campus</span><br><br></th>
					<th style="font-weight: thin;"><span style="color: #fff !important;">Fund</span><br><br></th>
				</tr>
				<tr>
					<th colspan="3" style="font-weight: thin;"><span style="color: #fff !important;">Payor</span><br><br></th>
				</tr>
				<tr>
					<th style="font-size: 10pt; font-family: sans-serif; text-align: center; font-weight: thin; color: #fff !important;">NATURE OF<br> COLLECTION</th>
					<th style="font-size: 10pt; font-family: unset; text-align: center; font-weight: thin; color: #fff !important;">ACCOUNT CODE</th>
					<th width="30%" style="font-size: 10pt; font-family: unset; text-align: center; font-weight: thin; color: #fff !important;"><span class="hidden">AMOUNT</span></th>
				</tr>
			</thead>
			<tbody>
				@foreach($studor as $orfees)
				<tr class="print-only">
					<td style="font-weight: bold; text-align: right; font-family: 'Roboto', sans-serif;">{{ $orfees->account }}</td>
					<td style="font-weight: bold; text-align: center; font-family: 'Roboto', sans-serif;">{{ $orfees->fund }}</td>
					<td style="font-weight: bold; font-family: 'Roboto', sans-serif;">{{ $orfees->amountpaid }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</body>
</html>