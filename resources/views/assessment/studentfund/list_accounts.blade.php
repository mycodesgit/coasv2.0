@extends('layouts.master_assessment')

@section('title')
CISS V.1.0 || Assessment
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
                            <li class="breadcrumb-item mt-1">Assessment</li>
                            <li class="breadcrumb-item active mt-1">School Fund Accounts</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">School Fund Accounts</h1>
                        <p class="text-muted small mb-0">Manage School Fund Accounts codes for fund collection and tracking.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-receipt"></i> List of School Fund Accounts
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <form method="post" action="{{ route('accountAppraisalCreate') }}" enctype="multipart/form-data" id="adAccntApp">
                                                            @csrf
                                                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                                <h5>Add Accounts</h5>
                                                            </div>

                                                            <div class="form-group">
                                                                <div class="form-row">
                                                                    <div class="mt-2 col-md-12">
                                                                        <label class="form-label fw-semibold">Funds: <span class="text-danger">*</span></label>
                                                                        <select class="form-control form-control-sm" name="fund_id">
                                                                            <option disabled selected> ---Select--- </option>
                                                                            @foreach($funds as $fund)
                                                                                <option value="{{ $fund->fund_name}}">{{ $fund->fund_name}} </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="mt-2 col-md-12">
                                                                        <label class="form-label fw-semibold">Account Name: <span class="text-danger">*</span></label>
                                                                        <input type="text" name="account_name" class="form-control form-control-sm">
                                                                    </div>

                                                                    <div class="mt-2 col-md-12">
                                                                        <label class="form-label fw-semibold">COA Account: <span class="text-danger">*</span></label>
                                                                        <select class="form-control form-control-sm select2bs4" name="coa_id">
                                                                            <option disabled selected> ---Select--- </option>
                                                                            @foreach($accntsCOA as $accntcoa)
                                                                                <option value="{{ $accntcoa->accountcoa_code}}">{{ $accntcoa->accountcoa_code}} - {{ $accntcoa->accountcoa_name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <label>&nbsp;</label>
                                                                        <button type="submit" class="btn btn-success btn-sm btn-block">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-9 mt-3">
                                                <table id="accntApp" class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Fund Name</th>
                                                            <th>COA Account Name</th>
                                                            <th>Account Name</th>
                                                            <th width="10%">Action</th>
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
    </div>

    <div class="modal fade mt-6" id="editAppraisalAccntModal" tabindex="-1" role="dialog" aria-labelledby="editcoaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editcoaModalLabel">Edit Accounts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editAppraisalAccnForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editaccntAppId">
                        <div class="form-group">
                            <label for="editaccntFundId">Funds: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" id="editaccntFundId" name="fund_id">
                                <option disabled selected>Select</option>
                                @foreach ($funds as $fund)
                                    <option value="{{ $fund->fund_name }}">
                                        {{ $fund->fund_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editaccntCOAname">Account Name: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control form-control-sm" id="editaccntCOAname" name="account_name">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editaccntCOAid">COA Account: <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" id="editaccntCOAid" name="coa_id">
                                <option disabled selected>Select</option>
                                @foreach($accntsCOA as $accntcoa)
                                    <option value="{{ $accntcoa->accountcoa_code}}">{{ $accntcoa->accountcoa_code}} - {{ $accntcoa->accountcoa_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        var accntApprslReadRoute = "{{ route('getaccountAppraisalRead') }}";
        var accntApprslCreateRoute = "{{ route('accountAppraisalCreate') }}";
        var accntApprslUpdateRoute = "{{ route('accountAppraisalUpdate', ['id' => ':acntid']) }}";
        var accntApprslDeleteRoute = "{{ route('accountAppraisalDelete', ['id' => ':acntid']) }}";
    </script>
@endsection
