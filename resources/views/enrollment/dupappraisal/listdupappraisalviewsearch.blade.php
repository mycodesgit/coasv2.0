@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
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
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Edit Duplicate Appraisal</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12 scrolling-column">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Edit Duplicate Appraisal</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="card mt-2" style="background-color: #e9ecef">
                                            <div class="body pr-2 pl-2 pt-2 pb-2">
                                                <div class="form-group" style="padding-left: 10px; padding-right: 10px">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>Student ID</label>
                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->stud_id }}" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Last Name</label>
                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->lname }}" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>First Name</label>
                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->fname }}" readonly>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>Middle Name</label>
                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->mname }}" readonly>
                                                        </div>
                                                        <div class="col-md-1">
                                                            <label>Ext. Name</label>
                                                            <input type="text" name="" class="form-control form-control-sm" value="{{ $student->ext }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card mt-2" style="background-color: #e9ecef">
                                            <div class="card-body pr-2 pl-2 pt-2 pb-2">
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
                                        <div class="card mt-2" style="background-color: #e9ecef">
                                            <div class="card-body pr-2 pl-2 pt-2 pb-2">
                                                <div class="table-responsive">
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
                                                </div>
                                                {{-- <input type="hidden" id="fundnameCodeInput" name="fndCodes" class="form-control form-control-sm" value="{{ $fundIDsString }}" readonly>
                                                <input type="hidden" id="accountNameInput" name="accntNames" class="form-control form-control-sm" value="{{ $accountsString }}" readonly>
                                                <input type="hidden" id="amountFeeInput" name="amntFees" class="form-control form-control-sm" value="{{ $amountsString }}" readonly>
                                                <input type="hidden" id="primInput" name="id" class="form-control form-control-sm" value="{{ $primIDsString }}" readonly> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 sticky-column">
                                        <div class="card mt-2" style="background-color: #e9ecef">
                                            <div class="card-body">
                                                <a href="{{ route('dupapprslSearch') }}" class="col-md-12 btn btn-success btn-sm">Edit New</a>
                                                <form action="{{ route('studrfprint') }}" method="get" target="_blank">
                                                    @csrf
                                                    <input type="hidden" name="stud_id" value="{{ request('stud_id') }}">
                                                    <input type="hidden" name="schlyear" value="{{ request('schlyear') }}">
                                                    <input type="hidden" name="semester" value="{{ request('semester') }}">
                                                <button type="submit" class="col-md-12 btn btn-success btn-sm mt-2 btnprim" id="printRFButton" target="_blank">
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
                                                    <div class="row">
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
