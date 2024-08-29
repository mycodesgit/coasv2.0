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
            border: 1px solid #bbb;
        }
        th, td {
            padding: 5px;
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
            <img src="{{ public_path('template/img/cashier/reportassessheaderMain.png') }}" width="80%">
        @elseif(Auth::guard('web')->user()->campus == 'VC')
            <img src="{{ public_path('template/img/cashier/reportassessheaderVic.png') }}" width="80%">
        @endif
    </div>

    <div class="studinfolabel" style="margin-top: 25px">
        <span style="font-weight: bold;">STUDENT ID NO.:</span> <span class="studinfoID"><strong> {{ request('stud_id') }}</strong></span>
        <span style="font-weight: bold; text-align: right !important; margin-left: 240px;">DATE:</span> <span><strong>{{ strtoupper(\Carbon\Carbon::now()->format('F j, Y')) }}</strong></span>
    </div> 
    <div class="studinfolabel" style="margin-top: 5px">
        <span style="font-weight: bold;">NAME:</span> <span class="">&nbsp;&nbsp;&nbsp; <strong>{{ $studfees->first()->lname }}, {{ $studfees->first()->fname }} {{ substr($studfees->first()->lname, 0,1) }}.</strong></span>
        <span style="font-weight: bold; text-align: right !important; margin-left: 200px;">COURSE:</span> <span><strong>{{ $studfees->first()->progAcronym }}</strong></span>
    </div>

    <div class="row">
        <div class="col-md-12" style="margin-top: 10px">
            <div class="card card-secondary card-outline">
                <table>
                    <thead>
                        <tr>
                            <th style="text-align: center; background-color: #e9ecef;" colspan="6"><h4>Appraisal</h4></th>
                        </tr>
                        <tr>
                            <th>Code</th>
                            <th>Fund</th>
                            <th>Amount</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAmount = 0;
                        @endphp
                        @foreach($studfees as $datastudfeesview)
                            @php
                                $totalAmount += $datastudfeesview->amount;
                            @endphp
                            <tr>
                                <td>{{ $datastudfeesview->first()->fundID }}</td>
                                <td>{{ $datastudfeesview->first()->account }}</td>
                                <td>{{ $datastudfeesview->first()->amount  }}</td>
                                <td>{{ $datastudfeesview->first()->schlyear }}</td>
                                <td>{{ $datastudfeesview->first()->semester }}</td>
                                <td>{{ Carbon\Carbon::parse($datastudfeesview->first()->dateAssess)->format('M j, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="text-align: right;"><strong>Total Amount:</strong></td>
                            <td><strong style="font-size: 15px">{{ number_format($totalAmount, 2) }}</strong></td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="col-md-12" style="margin-top: 30px">
            <div class="card card-secondary card-outline">
                <table>
                    <thead>
                        <tr>
                            <th style="text-align: center; background-color: #e9ecef;" colspan="5"><h4>Payment</h4></th>
                        </tr>
                        <tr>
                            <th>OR</th>
                            <th>Code</th>
                            <th>Fund</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAmountPaid = 0;
                        @endphp
                        @foreach($studpayment as $datastudpaymentview)
                            @php
                                $totalAmountPaid += $datastudpaymentview->amountpaid;
                            @endphp
                            <tr>
                                <td>{{ $datastudpaymentview->orno }}</td>
                                <td>{{ $datastudpaymentview->fund }}</td>
                                <td>{{ $datastudpaymentview->account }}</td>
                                <td>{{ $datastudpaymentview->amountpaid  }}</td>
                                <td>{{ Carbon\Carbon::parse($datastudpaymentview->datepaid)->format('M j, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: right;"><strong>Total Amount:</strong></td>
                            <td><strong style="font-size: 15px">{{ number_format($totalAmountPaid, 2) }}</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

</body>
</html>