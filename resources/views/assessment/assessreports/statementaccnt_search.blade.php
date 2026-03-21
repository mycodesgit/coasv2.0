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
                            <li class="breadcrumb-item active mt-1">Accounts Per Semester</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Accounts Per Semester</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row mt-3 p-2">
                                            <div class="col-md-12">
                                                <div class="col-md-12">
                                                    <form method="GET" action="{{ route('stateaccntpersem_search') }}" id="studstatesem">
                                                        @csrf

                                                        <div class="">
                                                            <div class="form-group">
                                                                <div class="row g-3">
                                                                    <div class="col-md-2">
                                                                        <label>Student ID Number: <span class="text-danger">*</span></label>
                                                                        <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <label>Academic Year: <span class="text-danger">*</span></label>
                                                                        <select class="form-control form-control-sm" name="schlyear">
                                                                            @foreach($sy as $datasy)
                                                                                <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <label>Semester: <span class="text-danger">*</span></label>
                                                                        <select class="form-control  form-control-sm" name="semester">
                                                                            <option disabled selected>---Select---</option>
                                                                            <option value="1">First Semester</option>
                                                                            <option value="2">Second Semester</option>
                                                                            <option value="3">Summer</option>
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <label>Category: <span class="text-danger">*</span></label>
                                                                        <select class="form-control  form-control-sm" name="category">
                                                                            <option disabled selected>---Select---</option>
                                                                            <option value="1">Undergraduate</option>
                                                                            <option value="2">Graduate School</option>
                                                                            {{-- <option value="3">All</option> --}}
                                                                        </select>
                                                                    </div>

                                                                    <div class="col-md-2">
                                                                        <label>&nbsp;</label>
                                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                                <div class="col-md-6 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <table id="" class="table table-hover table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <center class="mt-3">
                                                                            <h4>Appraisal</h4>
                                                                        </center>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>Code</th>
                                                                        <th>Fund</th>
                                                                        <th>Amount</th>
                                                                        <th>Year</th>
                                                                        <th>Semester</th>
                                                                        <th>Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $totalAmount = 0;
                                                                    @endphp
                                                                    @foreach($studfees as $datastudfeesview)
                                                                        @php
                                                                            $totalAmount += $datastudfeesview->amount;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $datastudfeesview->fundID }}</td>
                                                                            <td>{{ $datastudfeesview->account }}</td>
                                                                            <td>{{ $datastudfeesview->amount  }}</td>
                                                                            <td>{{ $datastudfeesview->schlyear }}</td>
                                                                            <td>{{ $datastudfeesview->semester }}</td>
                                                                            <td>{{ Carbon\Carbon::parse($datastudfeesview->dateAssess)->format('M j, Y') }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mt-3">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <table id="" class="table table-hover table-striped table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <center class="mt-3">
                                                                            <h4>Payment</h4>
                                                                        </center>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>OR</th>
                                                                        <th>Code</th>
                                                                        <th>Fund</th>
                                                                        <th>Amount</th>
                                                                        <th>Date</th>
                                                                        <th width="20%">Comments</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $totalAmountPaid = 0;
                                                                    @endphp
                                                                    @foreach($studpayment as $datastudpaymentview)
                                                                        @php
                                                                            $totalAmountPaid += $datastudpaymentview->amountpaid;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $datastudpaymentview->orno }}</td>
                                                                            <td>{{ $datastudpaymentview->fund }}</td>
                                                                            <td>{{ $datastudpaymentview->account }}</td>
                                                                            <td>{{ number_format($datastudpaymentview->amountpaid, 2)  }}</td>
                                                                            <td>{{ Carbon\Carbon::parse($datastudpaymentview->datepaid)->format('M j, Y') }}</td>
                                                                            <td>{{ $datastudpaymentview->comments }}</td>
                                                                        </tr>
                                                                    @endforeach
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
                            <input type="text" name="fundID" id="editstudfeeFund" value="164" readonly class="form-control form-control-sm">
                        </div>
                        <div class="form-group mt-3">
                            <label for="editstudfeeaccountName">Account Name</label>
                            <select class="form-control form-control-sm select2" id="editstudfeeaccountName" name="account">
                                <option disabled selected> ---Select---</option>
                                @foreach($studAccntap as $studapp)
                                    <option value="{{ $studapp->account_name }}">{{ $studapp->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label for="editstudfeeamountFee">Amount</label>
                            <input type="number" class="form-control" id="editstudfeeamountFee" name="amount">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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

        var studFeesUpdtReadRoute = "{{ route('stateaccntpersem_getsearch') }}";
        var studFeesUpdtCreateRoute = "{{ route('stateaccntpersem_getsearchCreate') }}";
        var studFeesUpdtUpdateRoute = "{{ route('stateaccntpersem_getsearchUpdate', ['id' => ':id']) }}";
        var studFeesUpdtDeleteRoute = "{{ route('stateaccntpersem_getsearchDelete', ['id' => ':id']) }}";

        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("refreshPageBtn").addEventListener("click", function () {
                // Reload the page
                location.reload();
            });
        });
    </script>
@endsection
