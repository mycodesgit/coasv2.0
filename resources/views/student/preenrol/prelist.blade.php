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
                                        <div class="container-fluid px-2 px-md-3">
                                            @php
                                                date_default_timezone_set('Asia/Manila');

                                                $now = now();
                                                $startTime = now()->setHour(8)->setMinute(0)->setSecond(0);
                                                $endTime = now()->setHour(17)->setMinute(0)->setSecond(0);
                                            @endphp

                                            {{-- OFF HOURS --}}
                                            {{-- @if(!$now->isWeekday() || $now->lt($startTime) || $now->gte($endTime))
                                                <div class="card shadow-sm border-0">
                                                    <div class="card-body text-center py-5">
                                                        <i class="fas fa-clock text-danger mb-3" style="font-size:60px;"></i>
                                                        <h3 class="fw-bold text-danger">
                                                            Pre-enrollment Closed
                                                        </h3>
                                                        <p class="text-muted mb-0">
                                                            Pre-enrollment is available only from
                                                            <strong>Monday to Friday</strong>,
                                                            <strong>8:00 AM to 5:00 PM</strong>.
                                                        </p>
                                                    </div>
                                                </div>
                                            @else --}}
                                                {{-- PENDING EVALUATION --}}
                                                @if($prewait)
                                                    <div class="card shadow-sm border-0 mb-4">
                                                        <div class="card-body">
                                                            <div class="d-flex align-items-center mb-3">
                                                                <i class="fas fa-hourglass-half text-warning me-3" style="font-size:40px;"></i>
                                                                <div>
                                                                    <h4 class="mb-1 fw-bold">
                                                                        Pending Evaluation
                                                                    </h4>
                                                                    <p class="mb-0 text-muted">
                                                                        Your pre-enrollment for
                                                                        <strong>{{ $sy->first()->schlyear }}</strong>
                                                                        @if($sy->first()->semester == 1)
                                                                            1st Semester
                                                                        @elseif($sy->first()->semester == 2)
                                                                            2nd Semester
                                                                        @elseif($sy->first()->semester == 3)
                                                                            Summer
                                                                        @endif
                                                                        has been submitted for evaluation.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <button class="btn btn-outline-success btn-sm mb-4"
                                                                    onclick="location.reload();">
                                                                <i class="fas fa-sync-alt"></i>
                                                                Refresh Status
                                                            </button>

                                                            <div class="table-responsive">
                                                                <table id="waitpreTable" class="table table-hover align-middle">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Status</th>
                                                                            <th>Course</th>
                                                                            <th>Date</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody></tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {{-- APPROVED --}}
                                                @elseif ($prewaitreg)
                                                    <div class="card shadow-sm border-0">
                                                        <div class="card-body text-center py-5">
                                                            <i class="fas fa-check-circle text-info mb-3"
                                                            style="font-size:60px;"></i>
                                                            <h5 class="fw-bold text-info">
                                                                Approved by College/Department
                                                            </h5>
                                                            <p class="text-muted">
                                                                Your pre-enrollment has been approved and is now
                                                                pending at the Registrar Office.
                                                            </p>
                                                            <button class="btn btn-outline-success mt-2"
                                                                    onclick="location.reload();">
                                                                <i class="fas fa-sync-alt"></i>
                                                                Refresh Status
                                                            </button>
                                                        </div>
                                                    </div>
                                                {{-- ASSESSMENT --}}
                                                @elseif ($preassessenrollreg)
                                                    <div class="card shadow-sm border-0">
                                                        <div class="card-body text-center py-5">
                                                            <i class="fas fa-check-circle text-info mb-3"
                                                            style="font-size:60px;"></i>
                                                            <h5 class="fw-bold text-info">
                                                                Approved by Registrar Office
                                                            </h5>
                                                            <p class="text-muted">
                                                                Your pre-enrollment has been approved and is now
                                                                pending at the Assessment Office.
                                                            </p>
                                                            <button class="btn btn-outline-success mt-2"
                                                                    onclick="location.reload();">
                                                                <i class="fas fa-sync-alt"></i>
                                                                Refresh Status
                                                            </button>
                                                        </div>
                                                    </div>
                                                {{-- READY FOR CONFIRMATION --}}
                                                @elseif ($preconfirmenrollreg)
                                                    <div class="row g-3">
                                                        <div class="col-lg-4">
                                                            <div class="card shadow-sm border-0 h-100">
                                                                <div class="card-body text-center">
                                                                    <i class="fas fa-user-check text-warning mb-3"
                                                                    style="font-size:55px;"></i>

                                                                    <h4 class="fw-bold">
                                                                        Enrollment Ready
                                                                    </h4>

                                                                    <p class="text-muted">
                                                                        Your enrollment is ready for confirmation.
                                                                    </p>

                                                                    <!-- Instruction -->
                                                                    <div class="alert alert-info text-start">
                                                                        <i class="fas fa-info-circle"></i>
                                                                        Please review all enrolled subjects carefully in the
                                                                        <strong>Registration Form Preview</strong> before clicking
                                                                        <strong>Confirm Enrollment</strong>. Make sure the subject
                                                                        codes, schedules, and units are correct.
                                                                    </div>

                                                                    <div class="alert alert-warning text-start">
                                                                        <strong>Course:</strong>
                                                                        {{ $preconfirmenrollreg->course }}
                                                                    </div>

                                                                    <form action="{{ route('confirm.enrollment') }}"
                                                                        method="POST">
                                                                        @csrf

                                                                        <input type="hidden"
                                                                            name="schlyear"
                                                                            value="{{ $sy->first()->schlyear }}">

                                                                        <input type="hidden"
                                                                            name="semester"
                                                                            value="{{ $sy->first()->semester }}">

                                                                        <button type="submit"
                                                                                class="btn btn-success w-100 text-light">
                                                                            <i class="fas fa-check-circle"></i>
                                                                            Yes, I Confirm Enrollment
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-8">
                                                            <div class="card shadow-sm border-0">
                                                                <div class="card-body">
                                                                    <h5 class="fw-bold mb-3 text-center">
                                                                        Registration Form Preview
                                                                    </h5>

                                                                    @include('enrollment.studenroll.pdfrf.studRFconfirm')
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {{-- OFFICIALLY ENROLLED --}}
                                                @elseif ($preofficialenrollreg)
                                                    <div class="card shadow-sm border-0">
                                                        <div class="card-body text-center py-4">
                                                            <i class="fas fa-graduation-cap text-success mb-3"
                                                            style="font-size:65px;"></i>
                                                            <h3 class="fw-bold text-success">
                                                                Officially Enrolled
                                                            </h3>
                                                            <p class="text-muted mb-4">
                                                                Congratulations! You are officially enrolled in
                                                                <strong>{{ $preofficialenrollreg->course }}</strong>.
                                                            </p>
                                                            <div class="border rounded overflow-hidden">
                                                                <iframe src="{{ route('rfstudactconfirm') }}"
                                                                        width="100%"
                                                                        height="600"
                                                                        style="border:none;">
                                                                </iframe>
                                                            </div>
                                                        </div>
                                                    </div>
                                                {{-- FORM --}}
                                                @else
                                                    <form method="GET" action="{{ route('pre.show') }}" id="enrollStud">
                                                        @csrf
                                                        <div class="row g-3">
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">Student ID Number: <span class="text-danger">*</span></label>
                                                                <input type="text" name="stud_id" class="form-control" value="{{ $studauth->stud_id }}" readonly>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">School Year: <span class="text-danger">*</span></label>
                                                                <select class="form-control" name="schlyear">
                                                                    @foreach ($sy as $datasy)
                                                                        <option value="{{ $datasy->schlyear }}">
                                                                            {{ $datasy->schlyear }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                                <select class="form-control" name="semester">
                                                                    @foreach ($sy as $datasy)
                                                                        <option value="{{ $datasy->semester }}">
                                                                            @if ($datasy->semester == 1)
                                                                                1st Semester
                                                                            @elseif($datasy->semester == 2)
                                                                                2nd Semester
                                                                            @elseif($datasy->semester == 3)
                                                                                Summer
                                                                            @endif
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <br>
                                                                <button type="submit" class="btn btn-success mt-2 btn-block">
                                                                    <i class="fas fa-paper-plane"></i>
                                                                    Continue Pre-enrollment
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                @endif
                                            {{-- @endif --}}
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