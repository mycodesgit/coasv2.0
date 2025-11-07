@extends('layouts.master_student')

@section('title')
CISS V.1.0 || Student Appraisal Accounts
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="content-box">
                <div class="table-responsive">
                    <table class="table table-head-fixed text-nowrap" style="font-size: 10pt">
                        <thead>
                            <tr>
                                <th>School Year</th>
                                <th>Semester</th>
                                <th>Fund</th>
                                <th>Account</th>
                                <th>Total Fee</th>
                                <th>Paid</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $currentYear = '';
                            $currentSemester = '';
                            $subtotal = 0;
                            $grandTotal = 0;
                        @endphp

                        @foreach($studfees as $index => $datastudfees)
                            @if($currentYear != $datastudfees->schlyear || $currentSemester != $datastudfees->semester)
                                @if($index > 0)
                                    <tr class="font-weight-bold bg-warning">
                                        <td colspan="6" class="text-right">
                                            Subtotal for {{ $currentYear }} -
                                            @if($currentSemester == 1) 1st Sem
                                            @elseif($currentSemester == 2) 2nd Sem
                                            @elseif($currentSemester == 3) Summer
                                            @endif
                                        </td>
                                        <td>{{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                @endif
                                @php
                                    $currentYear = $datastudfees->schlyear;
                                    $currentSemester = $datastudfees->semester;
                                    $subtotal = 0;
                                @endphp
                            @endif

                            @php
                                $subtotal += $datastudfees->balance;
                                $grandTotal += $datastudfees->balance;
                            @endphp

                            <tr>
                                <td>{{ $datastudfees->schlyear }}</td>
                                <td>
                                    @if($datastudfees->semester == 1)
                                        1st Sem
                                    @elseif($datastudfees->semester == 2)
                                        2nd Sem
                                    @elseif($datastudfees->semester == 3)
                                        Summer
                                    @endif
                                </td>
                                <td>{{ $datastudfees->fundID }}</td>
                                <td>{{ $datastudfees->account }}</td>
                                <td>{{ number_format($datastudfees->total_fee, 2) }}</td>
                                <td>{{ number_format($datastudfees->total_payment, 2) }}</td>
                                <td>{{ number_format($datastudfees->balance, 2) }}</td>
                            </tr>
                        @endforeach

                        @if(count($studfees) > 0)
                        <tr class="font-weight-bold bg-warning">
                            <td colspan="6" class="text-right">Subtotal for {{ $currentYear }}</td>
                            <td>{{ number_format($subtotal, 2) }}</td>
                        </tr>
                        @endif

                        <tr class="font-weight-bold bg-danger text-white">
                            <td colspan="6" class="text-right">Grand Total Balance</td>
                            <td>{{ number_format($grandTotal, 2) }}</td>
                        </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection