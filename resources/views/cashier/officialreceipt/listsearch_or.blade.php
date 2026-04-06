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
                            <li class="breadcrumb-item active mt-1">Official Receipt</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                <h4>Official Receipt</h4>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                                <h5>Information</h5>
                                            </div>

                                            <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ Auth::guard('web')->user()->campus; }}">

                                            <div class="form-group mt-2">
                                                <div class="row g-3">
                                                    <div class="col-md-12">
                                                        <label>O.R. Number: <span class="text-danger">*</span></label>
                                                        <input type="text" name="orno" class="form-control form-control-sm" value="{{ request('orno') }}" oninput="this.value = this.value.toUpperCase()" readonly>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label>Academic Year: <span class="text-danger">*</span></label>
                                                        <input type="text" name="semester" class="form-control form-control-sm" value="{{ request('schlyear') }}" readonly>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label>Semester: <span class="text-danger">*</span></label>
                                                        <input type="text" name="semester" class="form-control form-control-sm" value="{{ request('semester') }}" readonly>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <label>Student ID Number: <span class="text-danger">*</span></label>
                                                        <input type="text" name="stud_id" class="form-control form-control-sm" value="{{ request('stud_id') }}" oninput="formatInput(this); this.value = this.value.toUpperCase()" readonly>
                                                    </div>
                                                    
                                                    <div class="col-md-12">
                                                        <label>Fullname: <span class="text-danger">*</span></label>
                                                        @if(request('r3') === 'on')
                                                            <input type="text" name="stud_id" class="form-control form-control-sm" value="{{ $orstud->first()->lname }}, {{ $orstud->first()->fname }} {{ $orstud->first()->mname }}" oninput="formatInput(this); this.value = this.value.toUpperCase()" readonly>
                                                        @else
                                                            <input type="text" name="stud_id" class="form-control form-control-sm" value="" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
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
                                                <input type="hidden" name="studID" value="{{ request('stud_id') }}">
                                                <input type="hidden" name="semester" value="{{ request('semester') }}">
                                                <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
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
                                                            <input type="number" name="amountpaid" class="form-control form-control-sm" step="0.01" min="0" inputmode="decimal" pattern="^\d+(\.\d{1,2})?$">
                                                        </div>

                                                        <div class="mt-1 col-md-12">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="btn btn-success btn-sm btn-block">Add</button>
                                                        </div>
                                                    </div>
                                                </div>  
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mt-3">
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
                                    <form action="{{ route('orprint') }}" method="get" target="_blank">
                                        @csrf
                                        <input type="hidden" name="stud_id" value="{{ request('stud_id') }}">
                                        <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                                        <input type="hidden" name="semester" value="{{ request('semester') }}">
                                        <input type="hidden" name="orno" value="{{ request('orno') }}">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-print"></i> Print OR
                                        </button>
                                    </form>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="post" action="{{ route('orCommentsCreate') }}" id="studOrAdded">
                                                @csrf
                                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                    <h5 class="text-bold">Comments</h5>
                                                </div>

                                                <input type="hidden" name="orno" value="{{ request('orno') }}">
                                                <input type="hidden" name="studID" value="{{ request('stud_id') }}">
                                                <input type="hidden" name="semester" value="{{ request('semester') }}">
                                                <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                                                <input type="hidden" name="campus" value="{{ Auth::guard('web')->user()->campus }}">
                                                <input type="hidden" name="datepaid" value="{{ now()->format('Y-m-d') }}">
                                                <input type="hidden" name="studpayID" value="">

                                                <div class="form-group mt-3">
                                                    <div class="row g-2">
                                                        <div class="mt-2 col-md-10">
                                                            <label>Add Comments: <span class="text-danger">*</span></label>
                                                            <input type="text" name="comments" class="form-control form-control-md">
                                                        </div>

                                                        <div class="mt-2 col-md-2">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="btn btn-outline-danger btn-block">
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
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="editorStudFeeModal" role="dialog" aria-labelledby="editorStudFeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editorFundModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <div class="form-group mt-3">
                            <label for="editorstudfeeaccount">Account Name</label>
                            <select class="form-control form-control-sm select2bs4" id="editorstudfeeaccount" name="account">
                                <option disabled selected> ---Select---</option>
                                @foreach($studAccntap as $studapp)
                                    <option value="{{ $studapp->account_name }}">{{ $studapp->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editstudfeeamountFee">Amount</label>
                            <input type="number" class="form-control form-contorl-sm" id="editorstudfeeamountFee" name="amountpaid">
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
    </script>

    <script>
        var studorReadRoute = "{{ route('getorpaymentRead') }}";
        var studorCommentCreateRoute = "{{ route('orCommentsCreate') }}";
        var studorCreateRoute = "{{ route('orCreate') }}";
        var studorUpdateRoute = "{{ route('orUpdate', ['id' => ':id']) }}";
        var studorDeleteRoute = "{{ route('orDelete', ['id' => ':id']) }}";
    </script>
@endsection
