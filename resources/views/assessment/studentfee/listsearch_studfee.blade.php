@extends('layouts.master_assessment')

@section('title')
COAS - V1.0 || Student Fee
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
            <li class="breadcrumb-item active mt-1">Student Fee</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
            <h5>Search Results: {{ $totalSearchResults }} 
                <small>
                    <i>Year-<b>{{ request('schlyear') }}</b>,
                        Semester-<b>{{ request('semester') }}</b>,
                        Campus-<b>{{ request('campus') }}</b>,
                        Course-<b>{{ request('prog_Code') }}</b>,
                        YrLevel-<b>{{ request('yrlevel') }}</b>,
                    </i>
                </small>
            </h5>

        <div class="mt-3 row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{route('list_searchStudfee')}}" id="studFeeAssess">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Add Student Fee</h5>
                            </div>

                            <input type="hidden" name="campus" value="{{ Auth::guard('web')->user()->campus }}">
                            <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                            <input type="hidden" name="semester" value="{{ request('semester') }}">
                            <input type="hidden" name="prog_Code" value="{{ request('prog_Code') }}">
                            <input type="hidden" name="yrlevel" value="{{ request('yrlevel') }}">

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Fund</span></label>
                                        <select id="fundname_code" class="form-control form-control-sm" name="fundname_code">
                                            <option disabled selected> ---Select---</option>
                                            @foreach($studfund as $fund)
                                                <option value="{{ $fund->fund_name }}">{{ $fund->fund_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Account</span></label>
                                        <select class="form-control form-control-sm select2bs4" name="accountName">
                                            <option disabled selected> ---Select---</option>
                                            @foreach($studAccntap as $studapp)
                                                <option value="{{ $studapp->account_name }}">{{ $studapp->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Amount</span></label>
                                        <input type="number" name="amountFee" class="form-control form-control-sm">
                                    </div>

                                    <div class="col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <table id="studentFees" class="table table-hover">
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

<div class="modal fade" id="editStudFeeModal" tabindex="-1" role="dialog" aria-labelledby="editStudFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFundModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editStudFeeForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editStudFeeId">
                    <div class="form-group">
                        <label for="editstudfeeFund">Fund</label>
                        <select id="fundname_code" class="form-control form-control-sm" id="editstudfeeFund" name="fundname_code">
                            <option disabled selected> ---Select---</option>
                            @foreach($studfund as $fund)
                                <option value="{{ $fund->fund_name }}" {{ $fund->fund_name == '164' ? 'selected' : '' }}>{{ $fund->fund_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editstudfeeaccountName">Account Name</label>
                        <input type="text" class="form-control" id="editstudfeeaccountName" name="accountName">
                    </div>
                    <div class="form-group">
                        <label for="editstudfeeamountFee">Amount</label>
                        <input type="number" class="form-control" id="editstudfeeamountFee" name="amountFee">
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
    var studfeeReadRoute = "{{ route('getstudFeeRead') }}";
    var studfeeCreateRoute = "{{ route('studFeeCreate') }}";
    var studfeeUpdateRoute = "{{ route('studFeeUpdate', ['id' => ':id']) }}";
    var studfeeDeleteRoute = "{{ route('studFeeDelete', ['id' => ':id']) }}";
</script>

@endsection
