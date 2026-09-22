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
                            <li class="breadcrumb-item active mt-1">Student Fees Template</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Student Fee Template</h1>
                        <p class="text-muted small mb-0">Manage Student Fees to all programs.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-plus"></i> Add Student Fee Template
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{route('studFeeTemplateCreate')}}" id="studFeeAssessTemplate">
                                    @csrf

                                    <input type="hidden" name="temptype" value="{{ request('temptype') }}">
                                    <input type="hidden" name="semester" value="{{ request('semester') }}">
                                    <input type="hidden" name="yrlevel" value="{{ request('yrlevel') }}">

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Fund: <span class="text-danger">*</span></label>
                                                <select id="fundname_code" class="form-control form-control-sm" name="fundname_code">
                                                    <option disabled selected> ---Select---</option>
                                                    @foreach($studfund as $fund)
                                                        <option value="{{ $fund->fund_name }}">{{ $fund->fund_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Account: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm select2bs4" data-placeholder="--Select--" name="accountName">
                                                    @foreach($studAccntap as $studapp)
                                                        <option value="{{ $studapp->account_name }}">{{ $studapp->account_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Amount: <span class="text-danger">*</span></label>
                                                <input type="number" name="amountFee" class="form-control form-control-sm">
                                            </div>

                                            <div class="col-md-12 d-flex justify-content-between">
                                                <button type="reset" class="btn btn-light"><i class="ti ti-restore"></i> Clear</button>
                                                <button type="submit" class="btn btn-success"><i class="ti ti-device-floppy"></i>  Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-receipt-pound"></i> List of Student Fees Template
                                </h6>
                            </div>
                            <div class="card-body">
                                <table id="studentFeesTemplate" class="table table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Fund</th>
                                            <th>Account Name</th>
                                            <th>Amount</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="float-right">
                                            <h5>Grand Total: <span id="grandTotal"></span></h5>
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

    <div class="modal fade mt-6" id="editStudFeeModal" role="dialog" aria-labelledby="editStudFeeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFundModalLabel">Edit Student Fee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editStudFeeForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editStudFeeId">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editstudfeeFund">Fund: <span class="text-danger">*</span></label>
                                <select id="fundname_code" class="form-control form-control-sm" id="editstudfeeFund" name="fundname_code">
                                    <option disabled selected> ---Select---</option>
                                    @foreach($studfund as $fund)
                                        <option value="{{ $fund->fund_name }}" {{ $fund->fund_name == '164' ? 'selected' : '' }}>{{ $fund->fund_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editstudfeeaccountName">Account Name: <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm select2bs4" id="editstudfeeaccountName" name="accountName">
                                    <option disabled selected> ---Select---</option>
                                    @foreach($studAccntap as $studapp)
                                        <option value="{{ $studapp->account_name }}">{{ $studapp->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold" for="editstudfeeamountFee">Amount: <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="editstudfeeamountFee" name="amountFee">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var studfeeTemplateReadRoute = "{{ route('getstudFeetemplateRead') }}";
        var studfeeTemplateCreateRoute = "{{ route('studFeeTemplateCreate') }}";
        var studfeeUpdateRoute = "{{ route('studFeeUpdate', ['id' => ':id']) }}";
        var studfeeDeleteRoute = "{{ route('studFeeDelete', ['id' => ':id']) }}";
    </script>
@endsection
