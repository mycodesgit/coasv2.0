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
                            <li class="breadcrumb-item active mt-1">Funds</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Funds</h1>
                        <p class="text-muted small mb-0">Codes for Collection of Funds.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-plus"></i> Add Funds
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="post" action="{{ route('fundCreate') }}" id="adFund">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Fund Name: <span
                                                        class="text-danger">*</span></label>
                                                <input type="number" name="fund_name"
                                                    class="form-control form-control-sm"
                                                    oninput="this.value = this.value.toUpperCase()">
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
                                    <i class="ti ti-credit-card-refund"></i> List of Funds
                                </h6>
                            </div>
                            <div class="card-body">
                                <table id="fund" class="table table-hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Fund Name</th>
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

    {{-- <div class="modal fade mt-6" id="editFundModal" tabindex="-1" role="dialog" aria-labelledby="editFundModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFundModalLabel">Edit Fund Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editFundForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editFundId">
                        <div class="form-group">
                            <label for="editFundName">Fund Name: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editFundName" name="fund_name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="editFundModal" tabindex="-1" aria-modal="true" role="dialog" aria-labelledby="editFundModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFundModalLabel">Edit Fund Name</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editFundForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editFundName">Fund Name: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editFundName" name="fund_name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        var fundReadRoute = "{{ route('getfundsRead') }}";
        var fundCreateRoute = "{{ route('fundCreate') }}";
        var fundUpdateRoute = "{{ route('fundUpdate', ['id' => ':id']) }}";
        var fundDeleteRoute = "{{ route('fundDelete', ['id' => ':id']) }}";
    </script>
@endsection
