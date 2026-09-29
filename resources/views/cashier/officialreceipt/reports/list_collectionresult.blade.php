@extends('layouts.master_cashiering')

@section('title')
CISS V.1.0 || Cashiering
@endsection

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
                            <li class="breadcrumb-item active mt-1">Collection Report</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Collection Report</h1>
                        <p class="text-muted small mb-0">Collection of Official Receipt payments and daily transaction logs.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-file-excel"></i> List of collection report section
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('collectionrep.store') }}" id="perdayorno">
                                            @csrf

                                            <div class="">
                                                <div class="form-group">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Select Date Range: <span class="text-danger">*</span></label>
                                                            <input type="text" name="datepaid" class="form-control form-control-sm" id="reservation" value={{ request('datesearch') }}>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="d-flex flex-column h-100">
                                                                <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">Search</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="page-header" style="border-top: 1px solid #04401f;"></div>
                                        <div class="table-responsive mt-3 p-2">
                                            <table id="orcollectiontable" class="table table-hover" style="width: 100%">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>OR</th>
                                                        <th>Name</th>
                                                        <th>A.Y.</th>
                                                        <th>ID</th>
                                                        <th>Certification</th>
                                                        <th>Tuition</th>
                                                        <th>Admission</th>
                                                        <th>Athletics</th>
                                                        <th>Computer Lab</th>
                                                        <th>Cultural</th>
                                                        <th>Developmental</th>
                                                        <th>Guidance</th>
                                                        <th>Laboratory</th>
                                                        <th>Library</th>
                                                        <th>Medical</th>
                                                        <th>Registration</th>
                                                        <th>Stud ID Card</th>
                                                        <th>OTR/Hon.Dis</th>
                                                        <th>Yearbook</th>
                                                        <th>Total</th>
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
    </div>

    <script>
        var orCollectionReadRoute = "{{ route('collectionrep.show') }}";
    </script>
@endsection
