@extends('layouts.master_assessment')

@section('title')
CISS V.1.0 || Student Statements of Accounts Per Semester
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
            <li class="breadcrumb-item active mt-1">Student Statements of Accounts Per Semester</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <h4>Student Statements of Accounts Per Semester</h4>
        </div>

        <div class="mt-2 row">
            <div class="col-md-12">
                                <form method="GET" action="{{ route('stateaccntpersem_search') }}" id="studstatesum">
                    @csrf

                    <div class="">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Student ID Number</span></label>
                                    <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                </div>

                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Academic Year</span></label>
                                    <select class="form-control form-control-sm" name="schlyear">
                                        @foreach($sy as $datasy)
                                            <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Semester</span></label>
                                    <select class="form-control  form-control-sm" name="semester">
                                        <option disabled selected>---Select---</option>
                                        <option value="1">First Semester</option>
                                        <option value="2">Second Semester</option>
                                        <option value="3">Summer</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label><span class="badge badge-secondary">Category</span></label>
                                    <select class="form-control  form-control-sm" name="category">
                                        <option disabled selected>---Select---</option>
                                        {{-- <option value="1">Undergraduate</option> --}}
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
        </div>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-3 row">
            <div class="col-md-7 card">
                <table id="" class="table table-hover">
                    <thead>
                        <tr>
                            <center>
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
                        @foreach($studfees as $datastudfeesview)
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
            <div class="col-md-5 card">
                <table id="" class="table table-hover">
                    <thead>
                        <tr>
                            <center>
                                <h4>Payment</h4>
                            </center>
                        </tr>
                        <tr>
                            <th>OR</th>
                            <th>Code</th>
                            <th>Fund</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studpayment as $datastudpaymentview)
                            <tr>
                                <td>{{ $datastudpaymentview->orno }}</td>
                                <td>{{ $datastudpaymentview->fund }}</td>
                                <td>{{ $datastudpaymentview->account }}</td>
                                <td>{{ $datastudpaymentview->amountpaid  }}</td>
                                <td>{{ Carbon\Carbon::parse($datastudpaymentview->datepaid)->format('M j, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection