@extends('layouts.master_cashiering')

@section('title')
CISS V.1.0 || Cashiering
@endsection

@yield('sidemenu')

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Cashier</li>
                            <li class="breadcrumb-item active mt-1">Official Receipt Per Date</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Official Receipt Per Date</h1>
                        <p class="text-muted small mb-0">List of student Official Receipt payments and daily transaction logs.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-list"></i> List of Data
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('listsearch_orperdayRead') }}" id="perdayorno">
                                            @csrf

                                            <div class="form-group">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label class="form-label fw-semibold">Select Date: <span class="text-danger">*</span></label>
                                                        <input type="date" name="datepaid" class="form-control form-control-sm">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="d-flex flex-column h-100">
                                                            <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                        <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="page-header" style="border-top: 1px solid #04401f;"></div>
                                        <div class="table-responsive mt-3 p-2">
                                            <table id="example1" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>OR No</th>
                                                        <th>Date Paid</th>
                                                        <th>Schlyear</th>
                                                        <th>Semester</th>
                                                        <th>Student ID No.</th>
                                                        <th>Student Name</th>
                                                        <th>Amount</th>
                                                        <th>PostedBy</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($data as $d)
                                                        <tr>
                                                            <td>{{ $d->orno }}</td>
                                                            <td>{{ $d->datepaid }}</td>
                                                            <td>{{ $d->schlyear }}</td>
                                                            <td>
                                                                @if($d->semester == 1)
                                                                    1st Semester
                                                                @elseif($d->semester == 2)
                                                                    2nd Semester
                                                                @elseif($d->semester == 3)
                                                                    Summer
                                                                @else
                                                                    Unknown Semester
                                                                @endif
                                                            </td>
                                                            <td>{{ $d->studID }}</td>
                                                            <td>{{ $d->slname }}, {{ $d->sfname }} {{ substr($d->smname, 0,1) }}.</td>
                                                            <td>{{ number_format($d->total_amount, 2) }}</td>
                                                            <td>{{ $d->fname }} {{ $d->lname }}</td>
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
        </div>
    </div>
@endsection
