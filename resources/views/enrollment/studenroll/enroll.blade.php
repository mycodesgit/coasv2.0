@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                {{-- <h1 class="fs-5 mb-4 d-none d-md-block">Dashboard</h1> --}}
                <div class="card mb-3" style=" background-color: #e9ecef; margin-top: -10px">
                    <div class="card-body">
                        <ol class="breadcrumb" style="margin-bottom: -3px;">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Enroll Student</li>
                        </ol>
                    </div>
                </div>
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div>
                        <h1 class="h4 fw-bold mb-1" style="letter-spacing: -0.02em;">Enroll Student</h1>
                        <p class="text-muted small mb-0">Complete the form below to enroll a new student.</p>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search to show data
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('searchStudEnroll') }}" id="enrollStud" class="mb-3">
                                    @csrf

                                    <div class="form-group">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Student ID Number: <span class="text-danger">*</span></label>
                                                <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">School Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Semester: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="semester">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->semester }}">{{ $datasy->semester == 1 ? '1st Semester' : ($datasy->semester == 2 ? '2nd Semester' : 'Summer') }}</option>
                                                    @endforeach
                                                    {{-- <option value="1">1st Semester</option>
                                                    <option value="2">2nd Semester</option>
                                                    <option value="3">Summer</option> --}}
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="d-flex flex-column h-100">
                                                    <label class="form-label fw-semibold opacity-0 d-none d-md-block">Action</label>
                                                    <button type="submit" class="btn btn-success btn-sm">OK</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-server"></i> List of Pre-Enrolled Students
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(in_array(Auth::guard('web')->user()->campus, ['MC', 'VC', 'SCC', 'HC', 'MP', 'IC', 'CA', 'CC', 'SC', 'HinC']))
                                    @if($queueMode->statusqueue === 'Off')

                                    @else
                                        <div class="table-responsive p-2">
                                            <table id="queueTable" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Student ID No.</th>
                                                        <th>Fullname</th>
                                                        <th>Course Yr&Section</th>
                                                        <th>Campus</th>
                                                        <th>Status</th>
                                                        <th width="10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                        <hr>

                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        @if(in_array(Auth::guard('web')->user()->campus, ['MC']))
                            @if($queueMode->statusqueue === 'Off')
                            @else
                                <div class="row g-4 justify-content-center">
                                    <!-- Queue Control Card -->
                                    <div class="col-md-12">
                                        <div class="card card-animate">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-server"></i> Current Serving
                                                </h6>
                                            </div>
                                            <div class="card-body p-3 text-center">
                                                <div class="">
                                                    <input type="text"
                                                        id="queueNumber"
                                                        class="form-control-plaintext text-dark fw-bolder display-6 text-center rounded-3"
                                                        readonly
                                                        value="--">
                                                </div>

                                                <div class="d-grid gap-3 d-sm-flex justify-content-sm-center mt-3">
                                                    <button id="nextButton" class="btn btn-success fw-bold shadow-sm" data-counter-id="1">
                                                        <i class="ti ti-player-track-next me-1"></i> Next
                                                    </button>
                                                    <button id="callButton" class="btn btn-outline-danger fw-bold">
                                                        <i class="ti ti-phone-call me-1"></i> Call
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Transaction Category Card -->
                                    <div class="col-md-12">
                                        <div class="card card-animate">
                                            <div class="card-header pt-3">
                                                <h6 class="card-title">
                                                    <i class="ti ti-server"></i> Select Transaction
                                                </h6>
                                            </div>
                                            <div class="card-body p-4">
                                                <form action="{{ route('counterUserUpdate') }}" method="POST" id="transacCategory">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $queueUser->id ?? '' }}">

                                                    <div class="mb-3">
                                                        <select name="category" class="form-select form-select-md text-secondary fw-semibold" id="tranCategory">
                                                            <option value="Enrollment" {{ (isset($queueUser) && $queueUser->category == 'Enrollment') ? 'selected' : '' }}>Enrollment</option>
                                                            <option value="Processing" {{ (isset($queueUser) && $queueUser->category == 'Processing') ? 'selected' : '' }}>Processing</option>
                                                            <option value="Pre-register" {{ (isset($queueUser) && $queueUser->category == 'Pre-register') ? 'selected' : '' }}>Pre-register</option>
                                                            <option value="Evaluation" {{ (isset($queueUser) && $queueUser->category == 'Evaluation') ? 'selected' : '' }}>Evaluation</option>
                                                            <option value="Printing" {{ (isset($queueUser) && $queueUser->category == 'Printing') ? 'selected' : '' }}>Printing</option>
                                                        </select>
                                                    </div>

                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-success fw-bold shadow-sm">
                                                            Save Transaction
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
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
        var studqueuelistReadRoute = "{{ route('studqueuefetch') }}";
        var studqueuelistShowReadRoute = "{{ route('editsearchStudRead') }}";
    </script>
@endsection
