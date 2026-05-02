@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Assessment of Fees
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('show.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Assessment Fees</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-receipt"></i> Assessment of Fees
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
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
                                                                <span class="badge bg-primary">1st Sem</span>
                                                            @elseif($datastudfees->semester == 2)
                                                                <span class="badge bg-success">2nd Sem</span>
                                                            @elseif($datastudfees->semester == 3)
                                                                <span class="badge bg-secondary">Summer</span>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection