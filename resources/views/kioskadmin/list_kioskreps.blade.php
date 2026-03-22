@extends('layouts.master_adminkiosk')

@section('title')
CISS V.1.0 || Kiosk Admin
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Kiosk</li>
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">Monthly Kiosk Report for this Year</h6>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="barChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-12">
                        <div class="card mb-3">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $todayCount }}</h3>
                                        <span>Today's Logs</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-users fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card mb-3">
                            <div class="card-body p-6">
                                <div class="d-flex justify-content-between pb-2">
                                    <div>
                                        <h3 class="fw-bold h1">{{ $yesterdayCount }}</h3>
                                        <span>Yesterday's Logs</span>
                                    </div>
                                    <div>
                                        <i class="ti ti-users fs-1 text-success"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive p-2">
                                    @php
                                        $logsexport = $logsexport->sortKeysDesc();
                                        $year = now()->year;
                                    @endphp
                                    <table id="example3" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Number of  Students</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($logsexport as $row)
                                                @php
                                                    $orderValue = sprintf('%04d-%02d', $row->year, $row->month);
                                                @endphp
                                                <tr>
                                                    <td data-order="{{ $orderValue }}">
                                                        {{ DateTime::createFromFormat('!m', $row->month)->format('F') }}
                                                        {{ $row->year }}
                                                    </td>
                                                    <td>{{ $row->total }}</td>
                                                </tr>
                                            @endforeach
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

@endsection
