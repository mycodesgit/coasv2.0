@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Pre-Enrollment
@endsection

@section('body')
    <div class="row ">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('show.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Pre-Enrollment</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-receipt"></i> Pre-Enrollment
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-4">
                                    <div class="col-md-12">
                                        @php
                                            date_default_timezone_set('Asia/Manila');

                                            $now = now();
                                            $startTime = now()->setHour(8)->setMinute(0)->setSecond(0);
                                            $endTime = now()->setHour(17)->setMinute(0)->setSecond(0);
                                        @endphp

                                        @if(!$now->isWeekday() || $now->lt($startTime) || $now->gte($endTime))
                                            <div class="alert alert-danger text-center mb-0" role="alert">
                                                <h5>Pre-enrollment is only available from Monday to Friday, 8:00 AM to 5:00 PM.</h5>
                                            </div>
                                        @else 

                                            @if($prewait)
                                                <div class="alert alert-warning text-center mb-0" role="alert">
                                                    <h5>Your Pre-enrollment for {{ $sy->first()->schlyear }} 
                                                        @if($sy->first()->semester == 1)
                                                            1st Sem
                                                        @elseif($sy->first()->semester == 2)
                                                            2nd Sem
                                                        @elseif($sy->first()->semester == 3)
                                                            Summer
                                                        @endif
                                                        has already been submitted for evaluation by the College Enrollment Committee.
                                                    </h5>
                                                </div>
                                                <br>
                                                <button class="btn btn-outline-success" onclick="location.reload();">
                                                    <i class="fas fa-refresh"></i> Refresh Page for Updates
                                                </button>
                                                <div class="mt-4">
                                                    <table id="waitpreTable" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Course</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @elseif ($prewaitreg)
                                                <div class="alert alert-info text-center mb-0" role="alert">
                                                    <h5>Your Pre-enrollment for {{ $sy->first()->schlyear }} 
                                                        @if($sy->first()->semester == 1)
                                                            1st Sem
                                                        @elseif($sy->first()->semester == 2)
                                                            2nd Sem
                                                        @elseif($sy->first()->semester == 3)
                                                            Summer
                                                        @endif
                                                        has already been approved. Pending in Registrar Office.
                                                    </h5>
                                                </div>
                                                <br>
                                                <button class="btn btn-outline-success" onclick="location.reload();">
                                                    <i class="fas fa-refresh"></i> Refresh Page for Updates
                                                </button>
                                            @elseif ($preconfirmenrollreg)
                                                <div class="alert alert-warning text-center mb-0" role="alert">
                                                    <h5>
                                                        Your pre-enrollment for {{ $sy->first()->schlyear }} 
                                                        @if($sy->first()->semester == 1)
                                                            1st Semester
                                                        @elseif($sy->first()->semester == 2)
                                                            2nd Semester
                                                        @elseif($sy->first()->semester == 3)
                                                            Summer Term
                                                        @endif
                                                        at the Main Campus in {{ $preconfirmenrollreg->course }} is ready for confirmation.
                                                    </h5>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-3 mt-2">
                                                        <div class="content-box pt-0 pb-3">
                                                            <div class="text-center mt-3">
                                                                <h6>Click Here to Confirm Your Enrollment</h6>
                                                                <form action="{{ route('confirm.enrollment') }}" method="POST">
                                                                    @csrf

                                                                    <input type="hidden" name="schlyear" value="{{ $sy->first()->schlyear }}" readonly>
                                                                    <input type="hidden" name="semester" value="{{ $sy->first()->semester }}" readonly>

                                                                    <button type="submit" class="btn btn-success btn-md mt-2">
                                                                        Yes, I Confirm Enrollment.
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9 mt-2">
                                                        <div class="content-box">
                                                            {{-- <iframe src="{{ route('rfstudactconfirm') }}" width="100%" height="500"></iframe> --}}
                                                            @include('enrollment.studenroll.pdfrf.studRFconfirm')
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif ($preofficialenrollreg)
                                                <div class="alert alert-success text-center mb-0" role="alert">
                                                    <h5>You are Officially Enrolled for {{ $sy->first()->schlyear }} 
                                                        @if($sy->first()->semester == 1)
                                                            1st Semester
                                                        @elseif($sy->first()->semester == 2)
                                                            2nd Semester
                                                        @elseif($sy->first()->semester == 3)
                                                            Summer
                                                        @endif
                                                        at the Main Campus in {{ $preofficialenrollreg->course }}.
                                                    </h5>
                                                </div>
                                                <br>
                                                <iframe src="{{ route('rfstudactconfirm') }}" width="100%" height="500"></iframe>
                                            @else
                                                <form method="GET" action="{{ route('pre.show') }}" id="enrollStud" class="">
                                                    @csrf
                                                    <div class="row g-3 align-items-end">

                                                        <div class="col-12 col-md-3">
                                                            <label class="form-label mb-1">
                                                                <span>Student ID Number</span>
                                                            </label>
                                                            <input type="text" name="stud_id" class="form-control form-control-sm" value="{{ $studauth->stud_id }}" readonly>
                                                        </div>

                                                        <div class="col-12 col-md-3">
                                                            <label class="form-label mb-1">
                                                                <span>School Year</span>
                                                            </label>
                                                            <select class="form-select form-select-sm" name="schlyear">
                                                                @foreach ($sy as $datasy)
                                                                    <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-12 col-md-3">
                                                            <label class="form-label mb-1">
                                                                <span>Semester</span>
                                                            </label>
                                                            <select class="form-select form-select-sm" name="semester">
                                                                @foreach ($sy as $datasy)
                                                                    <option value="{{ $datasy->semester }}">
                                                                        @if ($datasy->semester == 1)
                                                                            1st Sem
                                                                        @elseif($datasy->semester == 2)
                                                                            2nd Sem
                                                                        @elseif($datasy->semester == 3)
                                                                            Summer
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-12 col-md-3">
                                                            <label class="form-label mb-1 d-block">&nbsp;</label>
                                                            <button type="submit" class="btn btn-success btn-sm w-100">OK</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        @endif
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

        var selectQueueCatRoute  = "{{ route('counterUserUpdate') }}";
        var preenrollistReadRoute = "{{ route('preenrolmentfetch') }}";
    </script>
@endsection