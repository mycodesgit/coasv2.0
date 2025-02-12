@extends('layouts.master_kiosk')

@section('body')
<div class="container-fluid">
    <div class="row" style="padding-top: 0px;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <span class="" style="border-radius: 5px; font-size: 15pt;">Student ID No.: {{ $studauth->stud_id }} &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; Name: {{ $studauth->lname }}, {{ $studauth->fname }} {{ substr($studauth->mname,0,1) }}.</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header text-center">
                    <div class="h3"><b>Student</b> Appraisal</div>
                </div>
                <div class="card-body">
                    <div class="mt-2 row">
                        <div class="col-md-12">
                            <div class="tab-content" id="vert-tabs-right-tabContent">
                                <div class="tab-pane fade show active" id="vert-tabs-right-one" role="tabpanel">
                                    <div class="card-body table-responsive p-0" style="height: 800px;">
                                        <table class="table table-head-fixed text-nowrap">
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
                                <div class="tab-pane fade" id="vert-tabs-right-two" role="tabpanel" aria-labelledby="vert-tabs-right-two-tab">
                                    sdsdsd
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-2">
                            <div class="card" style="background-color: #e9ecef !important">
                                <div class="ml-2 mr-2 mt-3 mb-1">
                                    <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                        
                                    </div>
                                    <div class="mt-3" style="font-size: 13pt;">
                                        <div class="nav flex-column nav-pills nav-stacked nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                            <a class="nav-link active" id="vert-tabs-right-one-tab" data-toggle="pill" href="#vert-tabs-right-one" role="tab" aria-controls="vert-tabs-right-one" aria-selected="true">View Grades</a>
                                            <a class="nav-link" id="vert-tabs-right-two-tab" data-toggle="pill" href="#vert-tabs-right-two" role="tab" aria-controls="vert-tabs-right-two" aria-selected="true">View Account</a>
                                            <a class="nav-link" href="{{ route('logout') }}">Logout</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection