@extends('layouts.master_assessment')

@section('title')
COAS - V2.0 || Fund
@endsection

@section('sideheader')
<h4>Assessment</h4>
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
            <li class="breadcrumb-item mt-1">Assessment</li>
            <li class="breadcrumb-item active mt-1">Fund</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Accounts</h4>
        </div>

        <div class="mt-5 row">
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
                                        <label><span class="badge badge-secondary">Funds</span></label>
                                        <select class="form-control form-control-sm" name="fund_id">
                                            <option disabled selected> ---Select--- </option>
                                            @foreach($funds as $fund)
                                                <option value="{{ $fund->id}}">{{ $fund->fund_name}} </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Account Name</span></label>
                                        <input type="text" name="account_name" class="form-control form-control-sm">
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">COA Account</span></label>
                                        <select class="form-control form-control-sm" name="coa_id">
                                            <option disabled selected> ---Select--- </option>
                                            @foreach($accntsCOA as $accntcoa)
                                                <option value="{{ $accntcoa->id}}">{{ $accntcoa->accountcoa_code}} - {{ $accntcoa->accountcoa_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
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

<div class="modal fade" id="editAppraisalAccntModal" tabindex="-1" role="dialog" aria-labelledby="editcoaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editcoaModalLabel">Edit Accounts</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editAppraisalAccnForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editaccntAppId">
                    <div class="form-group">
                        <label for="editaccntFundId">Funds</label>
                        <select class="form-control form-control-sm" id="editaccntFundId" name="fund_id">
                            <option disabled selected>Select</option>
                            @foreach ($funds as $fund)
                                <option value="{{ $fund->fund_name }}">
                                    {{ $fund->fund_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editaccntCOAname">Account Name</label>
                        <input type="text" class="form-control" id="editaccntCOAname" name="account_name">
                    </div>
                    <div class="form-group">
                        <label for="editaccntCOAid">COA Account</label>
                        <select class="form-control form-control-sm" id="editaccntCOAid" name="coa_id">
                            <option disabled selected>Select</option>
                            @foreach($accntsCOA as $accntcoa)
                                <option value="{{ $accntcoa->accountcoa_code}}">{{ $accntcoa->accountcoa_code}} - {{ $accntcoa->accountcoa_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
