@extends('layouts.master_cashiering')

@section('title')
CISS V.1.0 || Edit OR
@endsection

@section('sideheader')
<h4>Cashier</h4>
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
            <li class="breadcrumb-item mt-1">Cashier</li>
            <li class="breadcrumb-item active mt-1">Edit Official Receipt</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Edit Official Receipt</h4>
        </div>

        <div class="mt-3 row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <form method="get">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Information</h5>
                            </div>

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">O.R. Number</span></label>
                                        <input type="text" name="orno" class="form-control form-control-sm" value="{{ request('orno') }}" oninput="this.value = this.value.toUpperCase()" readonly>
                                    </div>

                                    <div class="mt-2 col-md-6">
                                        <label><span class="badge badge-secondary">Semester</span></label>
                                        <input type="text" name="semester" class="form-control form-control-sm" value="{{ $dataprimidORdataget->semester }}" readonly>
                                    </div>

                                    <div class="mt-2 col-md-6">
                                        <label><span class="badge badge-secondary">School Year</span></label>
                                        <input type="text" name="semester" class="form-control form-control-sm" value="{{ $dataprimidORdataget->schlyear }}" readonly>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Student ID Number</span></label>
                                        <input type="text" name="stud_id" class="form-control form-control-sm" value="{{ $orstud->first()->studID }}" oninput="formatInput(this); this.value = this.value.toUpperCase()" readonly>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Fullname</span></label>
                                        <input type="text" name="stud_id" class="form-control form-control-sm" value="{{ $orstud->first()->lname }}, {{ $orstud->first()->fname }} {{ $orstud->first()->mname }}" oninput="formatInput(this); this.value = this.value.toUpperCase()" readonly>
                                    </div>
                                </div>
                            </div>  
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('orCreate') }}" id="adOR">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5>Select</h5>
                            </div>

                            <input type="hidden" name="orno" value="{{ request('orno') }}">
                            <input type="hidden" name="studID" value="{{ $orstud->first()->studID }}">
                            <input type="hidden" name="semester" value="{{ $dataprimidORdataget->semester }}">
                            <input type="hidden" name="schlyear" value="{{ $dataprimidORdataget->schlyear }}">
                            <input type="hidden" name="campus" value="{{ Auth::guard('web')->user()->campus }}">
                            <input type="hidden" name="datepaid" value="{{ now()->format('Y-m-d') }}">

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Fund</span></label>
                                        <select id="fund" class="form-control form-control-sm" name="fund">
                                            <option disabled selected> ---Select---</option>
                                            @foreach($studfund as $fund)
                                                <option value="{{ $fund->fund_name }}">{{ $fund->fund_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Account</span></label>
                                        <select class="form-control form-control-sm select2bs4" data-placeholder="--Select--" name="account">
                                            @foreach($studAccntap as $studapp)
                                                <option value="{{ $studapp->account_name }}">{{ $studapp->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mt-2 col-md-12">
                                        <label><span class="badge badge-secondary">Amount</span></label>
                                        <input type="number" name="amountpaid" class="form-control form-control-sm">
                                    </div>

                                    <div class="mt-1 col-md-12">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Add</button>
                                    </div>
                                </div>
                            </div>  
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <table id="ortable" class="table table-hover">
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
                <hr>
                <form action="{{ route('orprintedit') }}" method="get" target="_blank">
                    @csrf
                    <input type="hidden" name="stud_id" value="{{ $orstud->first()->studID }}">
                    <input type="hidden" name="schlyear" value="{{ $dataprimidORdataget->schlyear }}">
                    <input type="hidden" name="semester" value="{{ $dataprimidORdataget->semester }}">
                    <input type="hidden" name="orno" value="{{ request('orno') }}">
                    <button type="submit" class="btn btn-primary float-right">
                        <i class="fas fa-print" onclick="printPDF()"></i> Print OR
                    </button>
                </form>
                <br><br>
                <hr>
                <button class="btn btn-outline-danger studorspec-delete float-right"
                    data-orno="{{ request('orno') }}"
                    data-schlyear="{{ $dataprimidORdataget->schlyear }}"
                    data-semester="{{ $dataprimidORdataget->semester }}" data-id="{{ $dataprimidORdataget->studorprimID }}"><i class="fas fa-trash"></i> Delete Official Receipt
                </button>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ route('orCommentsUpdate') }}" id="adEditORcomment">
                            @csrf
                            <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                <h5 class="text-bold">Comments</h5>
                            </div>

                            <input type="hidden" name="id" value="{{ $datacommentOR->studorcomentsprimID ?? 'null' }}">
                            <input type="hidden" name="orno" value="{{ request('orno') }}">
                            <input type="hidden" name="studID" value="{{ request('stud_id') }}">
                            <input type="hidden" name="semester" value="{{ $dataprimidORdataget->semester }}">
                            <input type="hidden" name="schlyear" value="{{ $dataprimidORdataget->schlyear }}">
                            <input type="hidden" name="campus" value="{{ Auth::guard('web')->user()->campus }}">
                            <input type="hidden" name="datepaid" value="{{ now()->format('Y-m-d') }}">
                            <input type="hidden" name="studpayID" value="{{ $dataprimidOR->studorprimID ?? 'null' }}">

                            <div class="form-group">
                                <div class="form-row">
                                    <div class="mt-2 col-md-10">
                                        <label><span class="badge badge-secondary">Add Comments</span></label>
                                        <input type="text" name="comments" value="{{ $datacommentOR->comments ?? ' ' }}" class="form-control form-control-md">
                                    </div>

                                    <div class="mt-2 col-md-2">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="form-control form-control-md btn btn-outline-danger btn-md text-bold">
                                            <i class="fas fa-save"></i> Save Comments
                                        </button>
                                    </div>
                                </div>
                            </div>  
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editorStudFeeModal" role="dialog" aria-labelledby="editorStudFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editorFundModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editorStudFeeForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editorStudFeeId">
                    <div class="form-group">
                        <label for="editorstudfeeFund">Fund</label>
                        <select id="fundname_code" class="form-control form-control-sm" id="editorstudfeeFund" name="fund">
                            <option disabled selected> ---Select---</option>
                            @foreach($studfund as $fund)
                                <option value="{{ $fund->fund_name }}" {{ $fund->fund_name == '164' ? 'selected' : '' }}>{{ $fund->fund_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editorstudfeeaccount">Account Name</label>
                        <select class="form-control form-control-sm select2bs4" id="editorstudfeeaccount" name="account">
                            <option disabled selected> ---Select---</option>
                            @foreach($studAccntap as $studapp)
                                <option value="{{ $studapp->account_name }}">{{ $studapp->account_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editstudfeeamountFee">Amount</label>
                        <input type="number" class="form-control" id="editorstudfeeamountFee" name="amountpaid">
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
    function formatInput(input) {
        let cleaned = input.value.replace(/[^A-Za-z0-9]/g, '');
        
        if (cleaned.length > 0) {
            let formatted = cleaned.substring(0, 4) + '-' + cleaned.substring(4, 8) + '-' + cleaned.substring(8, 9);
            input.value = formatted;
        } else {
            input.value = '';
        }
    }

    function handleDelete(event) {
        if (event.key === 'Backspace') {
            let input = event.target;
            let value = input.value;
            input.value = value.substring(0, value.length - 1);
            formatInput(input);
        }
    }
    function printPDF() {
        window.print();
    }
</script>

<script>
    var studorReadRoute = "{{ route('getorpaymentRead') }}";
    var studorCreateRoute = "{{ route('orCreate') }}";
    var studorUpdateRoute = "{{ route('orUpdate', ['id' => ':id']) }}";
    var studorDeleteRoute = "{{ route('orDelete', ['id' => ':id']) }}";

    var studorcommentsUpdateRoute = "{{ route('orCommentsUpdate', ['id' => ':id']) }}";
</script>

@endsection
