@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Faculty Services
@endsection

@section('workspace')
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">
                    <a href="{{ route('index.services') }}">
                        <i class="ti ti-arrow-left"></i> Services 
                    </a>
                    <span class="text-muted">/ Pre-enrollment</span>
                </h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-animate">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-search"></i> Search for Pre-enrollment
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="col-md-12">
                                    <ul class="nav nav-pills bg-light p-2 rounded-2 d-inline-flex mt-3" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-one-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-one" type="button" role="tab"
                                                aria-controls="pills-one" aria-selected="true">
                                                Regular & Irregular Enrollment
                                            </button>
                                        </li>
                                        &nbsp;
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="pills-two-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-two" type="button" role="tab"
                                                aria-controls="pills-two" aria-selected="false" tabindex="-1">
                                                Shiftee & Transferee Enrollment
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-12"> 
                                    <div class="tab-content mt-1" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-one" role="tabpanel" aria-labelledby="pills-one-tab" tabindex="0">
                                            <div class="mt-2 p-2">
                                                <form method="GET" action="{{ route('prelist.process') }}" id="enrollStud" class="mb-3">
                                                    @csrf 

                                                    <div class="form-group mt-2">
                                                        <div class="row g-3">
                                                            <div class="col-md-3">
                                                                <label>Student ID Number: <span class="text-danger">*</span></label>
                                                                <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label>School Year: <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="schlyear">
                                                                    @foreach($sy as $datasy)
                                                                        <option value="{{ encrypt($datasy->schlyear) }}">{{ $datasy->schlyear }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label>Semester: <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="semester">
                                                                    @foreach($sy as $datasy)
                                                                        <option value="{{ encrypt($datasy->semester) }}">{{ $datasy->semester == 1 ? '1st Semester' : ($datasy->semester == 2 ? '2nd Semester' : 'Summer') }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
                                                @php
                                                    $campuses = explode(',', Auth::guard('faculty')->user()->campus);
                                                @endphp

                                                @if(count(array_intersect($campuses, ['MC', 'VC', 'SCC', 'HC', 'MP', 'IC', 'CA', 'CC', 'SC', 'HinC'])) > 0)
                                                    @if($queueMode->statusqueue === 'Off')

                                                    @else
                                                        <div class="mt-5">
                                                            <h5>List of Pre-Enrolled Students</h5>
                                                            <table id="holdTable" class="table table-hover">
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
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="pills-two" role="tabpanel" aria-labelledby="pills-two-tab" tabindex="0">
                                            <div class="p-2">
                                                <form method="GET" action="{{ route('prelist.processShift') }}" id="enrollStud" class="mb-3">
                                                    @csrf 

                                                    <div class="form-group mt-2">
                                                        <div class="row g-3">
                                                            <div class="col-md-3">
                                                                <label>Student ID Number: <span class="text-danger">*</span></label>
                                                                <input type="text" name="stud_id" class="form-control form-control-sm" oninput="formatInput(this); this.value = this.value.toUpperCase()" autofocus>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label>School Year: <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="schlyear">
                                                                    @foreach($sy as $datasy)
                                                                        <option value="{{ encrypt($datasy->schlyear) }}">{{ $datasy->schlyear }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label>Semester: <span class="text-danger">*</span></label>
                                                                <select class="form-control form-control-sm" name="semester">
                                                                    @foreach($sy as $datasy)
                                                                        <option value="{{ encrypt($datasy->semester) }}">{{ $datasy->semester == 1 ? '1st Semester' : ($datasy->semester == 2 ? '2nd Semester' : 'Summer') }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label>&nbsp;</label>
                                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>
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

        var preenrollistReadRoute = "{{ route('fetchprestudenrol') }}";
        var preenrollistShowRoute  = "{{ route('storeprenrolprocess') }}";
    </script>
@endsection
