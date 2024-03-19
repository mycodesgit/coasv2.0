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
            <h4>COA Accounts</h4>
        </div>

        <div class="mt-5 row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{route('accountCOACreate')}}" id="adCOA">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Add COA Accounts</h5>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Account Code</span></label>
                                        <input type="number" name="accountcoa_code" class="form-control form-control-sm">
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Account Name</span></label>
                                        <input type="text" name="accountcoa_name" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
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
                <table id="coa" class="table table-hover">
                    <thead>
                        <tr>
                            <th>COA Account Code</th>
                            <th>COA Account name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editCoaModal" tabindex="-1" role="dialog" aria-labelledby="editcoaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editcoaModalLabel">Edit COA Account</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editCoaForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editcoaId">
                    <div class="form-group">
                        <label for="editcoaCode">COA Account Code</label>
                        <input type="number" class="form-control" id="editcoaCode" name="accountcoa_code">
                    </div>
                    <div class="form-group">
                        <label for="editcoaName">COA Account Name</label>
                        <input type="text" class="form-control" id="editcoaName" name="accountcoa_name">
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
    var coaReadRoute = "{{ route('getaccountCOARead') }}";
    var coaCreateRoute = "{{ route('accountCOACreate') }}";
    var coaUpdateRoute = "{{ route('accountCOAUpdate', ['id' => ':id']) }}";
    var coaDeleteRoute = "{{ route('accountCOADelete', ['id' => ':id']) }}";
</script>

@endsection
