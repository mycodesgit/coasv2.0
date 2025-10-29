@extends('layouts.master_student')

@section('title')
CISS V.1.0 || Student Grades
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
                                <th>Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $currentYear = '';
                                $currentSemester = '';
                                $currentColor = '';
                                $colorClasses = ['bg-light', 'bg-secondary'];
                                $colorIndex = 0;
                                $subtotal = 0;
                                $grandTotal = 0;
                            @endphp

                            @foreach($studfees as $index => $datastudfees)
                                @if($currentYear != $datastudfees->schlyear || $currentSemester != $datastudfees->semester)
                                    @if($index > 0)
                                        <!-- Display subtotal row for previous group -->
                                        <tr class="font-weight-bold bg-warning">
                                            <td colspan="4" class="text-right">Subtotal for {{ $currentYear }} - 
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
                                        $currentColor = $colorClasses[$colorIndex % count($colorClasses)];
                                        $colorIndex++;
                                        $subtotal = 0;
                                    @endphp
                                @endif

                                @php
                                    $subtotal += $datastudfees->amount;
                                    $grandTotal += $datastudfees->amount;
                                @endphp

                                <tr class="{{ $currentColor }}">
                                    <td>{{ $datastudfees->schlyear }}</td>
                                    <td>
                                        @if($datastudfees->semester == 1)
                                            <span class="badge badge-primary">1st Sem</span>
                                        @elseif($datastudfees->semester == 2)
                                            <span class="badge badge-success">2nd Sem</span>
                                        @elseif($datastudfees->semester == 3)
                                            <span class="badge badge-secondary">Summer</span>
                                        @endif
                                    </td>
                                    <td>{{ $datastudfees->fundID }}</td>
                                    <td>{{ $datastudfees->account }}</td>
                                    <td>{{ number_format($datastudfees->amount, 2) }}</td>
                                </tr>
                            @endforeach

                            <!-- Last subtotal row -->
                            @if(count($studfees) > 0)
                                <tr class="font-weight-bold bg-warning">
                                    <td colspan="4" class="text-right">Subtotal for {{ $currentYear }} - 
                                        @if($currentSemester == 1) 1st Sem
                                        @elseif($currentSemester == 2) 2nd Sem
                                        @elseif($currentSemester == 3) Summer
                                        @endif
                                    </td>
                                    <td>{{ number_format($subtotal, 2) }}</td>
                                </tr>
                            @endif

                            <!-- Grand Total Row -->
                            <tr class="font-weight-bold bg-danger text-white">
                                <td colspan="4" class="text-right">Grand Total</td>
                                <td>{{ number_format($grandTotal, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection