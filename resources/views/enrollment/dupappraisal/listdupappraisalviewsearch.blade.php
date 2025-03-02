@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Edit Duplicate Appraisal
@endsection

@section('sideheader')
<h4>Enrollment</h4>
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
            <li class="breadcrumb-item mt-1">Enrollment</li>
            <li class="breadcrumb-item active mt-1">Edit Duplicate Appraisal</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                <div class="row">
                    <div class="col-md-9">
                        <h4>Edit Duplicate Appraisal</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 scrolling-column">
                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <div class="form-group">
                                <div class="form-row">
                                    <div class="col-md-2">
                                        <label><span class="badge badge-success">Student ID</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->stud_id }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">Last Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->lname }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">First Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->fname }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label><span class="badge badge-secondary">Middle Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->mname }}" readonly>
                                    </div>
                                    <div class="col-md-1">
                                        <label><span class="badge badge-secondary">Ext. Name</span></label>
                                        <input type="text" name="" class="form-control form-control-sm" value="{{ $student->ext }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <form method="#" action="#" id="AddenrollStud">
                                @csrf
                                
                                <input type="hidden" value="{{ request('schlyear') }}" name="schlyear" id="schlyearInput" readonly>
                                <input type="hidden" value="{{ request('semester') }}" name="semester" id="semesterInput" readonly>
                                <input type="hidden" value="{{ $student->stud_id }}" name="studentID" id="studentID" readonly>
                                <input type="hidden" value="{{ $student->campus }}" name="campus" id="campusInput" readonly>
                                <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="postedDate" readonly>
                                <input type="hidden" value="{{ Auth::guard('web')->user()->id }}" name="postedBy" readonly>
                                <input type="hidden" value="{{ $programEnHistory->id }}" name="id" readonly>
                            </form>
                        </div>
                    </div>
                    <div class="card" style="background-color: #e9ecef">
                        <div class="body pr-2 pl-2 pt-2">
                            <table id="tablestudFee" class="table table-striped">
                                <thead style="background-color: #c9c9c9">
                                    <tr>
                                        <th>Fund</th>
                                        <th>Account</th>
                                        <th>Amount</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach($studEditfees as $dataenfees)
                                    <tr>
                                        <td>{{ $dataenfees->fundID }}</td>
                                        <td>{{ $dataenfees->account }}</td>
                                        <td>{{ $dataenfees->account === 'LAB FEE' ? 0 : $dataenfees->amount }}</td>
                                        <td>
                                            <button class="btn btn-outline-danger btn-sm delete-row">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @php
                                        $primfundIDs = [];
                                        $fundIDs = [];
                                        $accounts = [];
                                        $amounts = [];

                                        foreach ($studEditfees as $fee) {
                                            $primfundIDs[] = $fee->id;
                                            $fundIDs[] = $fee->fundID;
                                            $accounts[] = $fee->account;
                                            $amounts[] = $fee->account === 'LAB FEE' ? 0 : $fee->amount;
                                        }

                                        $primIDsString = implode(',', $primfundIDs);
                                        $fundIDsString = implode(',', $fundIDs);
                                        $accountsString = implode(',', $accounts);
                                        $amountsString = implode(',', $amounts);
                                @endphp --}}
                                </tbody>
                            </table>
                            {{-- <input type="hidden" id="fundnameCodeInput" name="fndCodes" class="form-control form-control-sm" value="{{ $fundIDsString }}" readonly>
                            <input type="hidden" id="accountNameInput" name="accntNames" class="form-control form-control-sm" value="{{ $accountsString }}" readonly>
                            <input type="hidden" id="amountFeeInput" name="amntFees" class="form-control form-control-sm" value="{{ $amountsString }}" readonly>
                            <input type="hidden" id="primInput" name="id" class="form-control form-control-sm" value="{{ $primIDsString }}" readonly> --}}
                        </div>
                    </div>
                </div>

                <div class="col-md-2 sticky-column mt-6">
                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="card-body">
                            <a href="{{ route('dupapprslSearch') }}" class="form-control form-control-sm btn btn-success btn-sm">Edit New</a>
                            <form action="{{ route('studrfprint') }}" method="get" target="_blank">
                                @csrf
                                <input type="hidden" name="stud_id" value="{{ request('stud_id') }}">
                                <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                                <input type="hidden" name="semester" value="{{ request('semester') }}">
                            <button type="submit" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim" id="printRFButton" target="_blank">
                                Print RF
                            </button>
                            </form>
                            {{-- <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Check Conflict</a>
                            <a href="" class="form-control form-control-sm btn btn-success btn-sm mt-2 btnprim">Est. No. of Stud.</a> --}}
                        </div>
                    </div>

                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-row">
                                    @php
                                        $totallecFee = 0;
                                        foreach ($subjectsEn as $datalecfee) {
                                            $totallecFee += $datalecfee->lecFee;
                                        }
                                        $totallabFee = 0;
                                        foreach ($subjectsEn as $datalabfee) {
                                            $totallabFee += $datalabfee->labFee;
                                        }
                                    @endphp
                                    <div class="col-md-6">
                                        Tuition: <input type="text" id="totalLecFeeInput" class="form-control form-control-sm" value="{{ $totallecFee }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        Lab Fee: <input type="text" id="totalLabFeeInput" class="form-control form-control-sm" value="{{ $totallabFee }}" readonly>
                                    </div>
                                </div>
                            </div>      
                            <input type="hidden" id="subjIDsInput" name="subjIDs" class="form-control form-control-sm" readonly value="{{ $subOfferedIds }}">
                            <input type="hidden" id="subjprimIDsInput" name="id" class="form-control form-control-sm" readonly value="{{ $studsubenrollIds }}">
                            <input type="hidden" id="primaryIDsInput" name="id" class="form-control form-control-sm" readonly value="{{ $studsubenrollIdsprimID }}">
                            <input type="hidden" id="itsubjInput" class="form-control form-control-sm" readonly value="{{ $studsubenrollIdsprimIDitfee }}">
                        </div>
                    </div>

                    <div class="card mt-2" style="background-color: #e9ecef">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-row">
                                    <label>Posted By:</label>
                                    <input type="text" name="" value="{{ $selectedpostedby }}" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var fetchstudFeeRoute  = "{{ route('getdupapprslSearchAjax') }}";
    var dupappDeleteRoute = "{{ route('dupapprslDelete', ['id' => ':id']) }}";

    document.addEventListener('DOMContentLoaded', function() {
    var scrollableColumn = document.querySelector('.scrolling-column');
        scrollableColumn.addEventListener('scroll', function() {
        var scrollTop = this.scrollTop;
        this.scrollTo({
            top: scrollTop,
            behavior: 'smooth'
            });
        });
    });
</script>

@endsection
