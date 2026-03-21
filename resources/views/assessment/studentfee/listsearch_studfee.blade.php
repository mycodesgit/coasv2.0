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
                <div class="card" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Assessment</li>
                            <li class="breadcrumb-item active mt-1">Student Fees</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Fees</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row mt-3 p-2">
                                            <div class="col-md-12">
                                                <div class="breadcrumb" style="font-size: 13pt">
                                                    <small>
                                                        <i>Year-<b>{{ request('schlyear') }}</b>,
                                                            Semester-<b>{{ request('semester') }}</b>,
                                                            Campus-<b>{{ request('campus') }}</b>,
                                                            Course-<b>
                                                            @php
                                                                $progCode = request('prog_Code');
                                                                $program = \App\Models\ScheduleDB\EnPrograms::where('progCod', $progCode)->first(); // Replace with your actual model
                                                            @endphp
                                                            @if($program)
                                                                {{ $program->progAcronym }} 
                                                            @else
                                                                {{ $progCode }}
                                                            @endif
                                                        </b>

                                                        <b>{{ request('yrlevel') }}</b>,
                                                        </i>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <form method="GET" action="" id="studFeeShowAssess">
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
                                                                    <div class="col-md-12">
                                                                        <label>&nbsp;</label>
                                                                        <button type="submit" class="btn btn-success btn-sm btn-block">Show Student Fees Template</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-9 mt-3">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="editStudFeeModal" role="dialog" aria-labelledby="editStudFeeModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editFundModalLabel">Edit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <div class="form-group mt-3">
                            <label for="editstudfeeaccountName">Account Name</label>
                            <select class="form-control form-control-sm select2" id="editstudfeeaccountName" name="accountName">
                                <option disabled selected> ---Select---</option>
                                @foreach($studAccntap as $studapp)
                                    <option value="{{ $studapp->account_name }}">{{ $studapp->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editstudfeeamountFee">Amount</label>
                            <input type="number" class="form-control" id="editstudfeeamountFee" name="amountFee">
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

    <div class="modal fade mt-6" id="studentFeesModal" tabindex="-1" role="dialog" aria-labelledby="studentFeesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="studentFeesModalLabel">Student Fees Template</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="studFeeAssess" method="POST">
                    <div class="modal-body">
                        <table class="table table-bordered" id="studentFeesTable">
                            <thead>
                                <tr>
                                    <th>Fund Name Code</th>
                                    <th>Amount Fee</th>
                                    <th>Account Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Populated by AJAX -->
                            </tbody>
                        </table>
                        <!-- Hidden inputs container -->
                        <input type="hidden" name="campus[]" value="{{ Auth::guard('web')->user()->campus }}">
                        <input type="hidden" name="schlyear[]" value="{{ request('schlyear') }}">
                        <input type="hidden" name="semester[]" value="{{ request('semester') }}">
                        <input type="hidden" name="prog_Code[]" value="{{ request('prog_Code') }}">
                        <input type="hidden" name="yrlevel[]" value="{{ request('yrlevel') }}">
                        <div id="hiddenInputsContainer" style="display: none;">
                            <!-- Hidden inputs will be dynamically added here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Add Student Fees</button>
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
