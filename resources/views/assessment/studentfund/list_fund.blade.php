@extends('layouts.master_assessment')

@section('title')
COAS - V1.0 || Fund
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
            <h4>Funds</h4>
        </div>

        <div class="mt-5 row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('fundCreate') }}" enctype="multipart/form-data" id="adFund">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Add Funds</h5>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Fund Name</span></label>
                                        <input type="number" name="fund_name" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
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
                <table id="fund" class="table table-hover">
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

<div class="modal fade" id="editFundModal" tabindex="-1" role="dialog" aria-labelledby="editFundModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFundModalLabel">Edit Fund Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editFundForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editFundId">
                    <div class="form-group">
                        <label for="editFundName">Fund Name</label>
                        <input type="text" class="form-control" id="editFundName" name="fund_name">
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
    var fundReadRoute = "{{ route('getfundsRead') }}";
    var fundCreateRoute = "{{ route('fundCreate') }}";
    var fundUpdateRoute = "{{ route('fundUpdate', ['id' => ':id']) }}";
    var fundDeleteRoute = "{{ route('fundDelete', ['id' => ':id']) }}";
</script>

@endsection
