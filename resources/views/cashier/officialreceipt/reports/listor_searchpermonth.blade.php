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
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Cashier</li>
                            <li class="breadcrumb-item active mt-1">Official Receipt Per Month</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                <h4>Official Receipt Per Month</h4>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <form method="GET" action="{{ route('listsearch_orpermonthRead') }}" id="perdayorno">
                                        @csrf

                                        <div class="">
                                            <div class="form-group">
                                                <div class="row g-3">
                                                    <div class="col-md-4">
                                                        <label>Select Date Range: <span class="text-danger">*</span></label>
                                                        <input type="text" name="datepaid" class="form-control form-control-sm" id="reservation">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="page-header" style="border-top: 1px solid #04401f;"></div>
                                    <div class="table-responsive mt-3 p-2">
                                        <table id="permonthORtable" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Date Paid</th>
                                                    <th>OR No</th>
                                                    <th>Student ID No.</th>
                                                    <th>Student Name</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>

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

    <script>
        var permonthOrReadRoute = "{{ route('getlistsearch_orpermonthRead') }}";
    </script>
@endsection
