@extends('layouts.master_adminkiosk')

@section('title')
    CISS V.1.0 || Kiosk User
@endsection

@section('sideheader')
    <h4>Kiosk Admin</h4>
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="card">
        <div class="card-body">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li class="breadcrumb-item mt-1">Kiosk Admin</li>
                <li class="breadcrumb-item active mt-1">Kiosk Reports</li>
            </ol>

            <p>
                @if (Session::has('success'))
                    <div class="alert alert-success">{{ Session::get('success') }}</div>
                @elseif (Session::has('fail'))
                    <div class="alert alert-danger">{{ Session::get('fail') }}</div>
                @endif
            </p>

            <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

            <div class="page-header mt-3">
                <div class="row">
                    <div class="col-md-9">
                        <div class="card card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Monthly Kiosk Report for this Year</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="barChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $todayCount }}</h3>
                                <p>Today's Logs</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>

                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $yesterdayCount }}</h3>
                                <p>Yesterday's Logs</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar-minus"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive">
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

    <script>
        const monthlyCounts = @json($monthlyData);
    </script>
    
@endsection
