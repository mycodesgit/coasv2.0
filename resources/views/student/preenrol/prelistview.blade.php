@extends('layouts.master_student')

@section('title')
    CISS V.1.0 || Pre-Enrollment
@endsection

@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="content-box" style="background-color: rgb(240, 242, 247)">
                <h5>Selection Section</h5>
                <hr>
                <form method="POST" id="AddpreenrollStud">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <input type="hidden" value="{{ request('schlyear') }}" name="schlyear" id="schlyearInput" readonly>
                        <input type="hidden" value="{{ request('semester') }}" name="semester" id="semesterInput" readonly>
                        <input type="hidden" value="{{ $studauth->stud_id }}" name="studentID" id="studentID" readonly>
                        <input type="hidden" value="{{ $studauth->campus }}" name="campus" id="campusInput" readonly>
                        <input type="hidden" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" name="postedDate" readonly>
                        <input type="hidden" value="99" name="studMajor" readonly>
                        <input type="hidden" value="99" name="studMinor" readonly>
                        <input type="hidden" value="3" name="transferee" readonly>
                        <input type="hidden" value="0" name="fourPs" readonly>

                        <div class="col-12 col-md-2">
                            <label class="text-bold">Status <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm bg-white" disabled>
                                @foreach ($studstat as $data)
                                    <option value="{{ $data->id }}" {{ $data->id == $selectedStudStatus ? 'selected' : '' }}>{{ $data->studentStatName }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="studStatus" value="{{ $selectedStudStatus }}">
                        </div>

                        <div class="col-12 col-md-3">
                            <labe class="text-bold">Type <span class="text-danger">*</span></labe>
                            <select class="form-control form-control-sm bg-white" disabled>
                                <option disabled selected> --Select--</option>
                                @foreach ($studtype as $data)
                                    <option value="{{ $data->id }}" {{ $data->id == 2 ? 'selected' : '' }}>{{ $data->studentTypeName }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="studType" value="2">
                        </div>
                        
                        <div class="col-12 col-md-3">
                            <label class="text-bold">Course Year&Section <span class="text-danger">*</span></label>
                            <select class="form-control form-control-sm" name="course" id="programNameSelect">
                                <option disabled selected> --Select --</option> {{-- Always default to this; JS can auto-select if needed --}}
                                @forelse ($classEnrolls as $class) {{-- Use @forelse to handle empty --}}
                                @php
                                    $yearsection = preg_replace('/\D/', '', $class->classSection);
                                    // Optional: Auto-select first or based on current year (e.g., if you have $currentSection)
                                    $isSelected = false; // Or: $isSelected = ($class->classSection === $currentSection ?? false);
                                @endphp
                                <option value="{{ $class->progAcronym }} {{ $class->classSection }}" 
                                        data-pkey="{{ $class->subjID}}" 
                                        data-section="{{ $class->classSection }}"  
                                        data-program-code="{{ $class->progCode }}" 
                                        data-program-classid="{{ $class->clid }}" 
                                        data-program-name="{{ $class->progName }}" 
                                        data-year-section="{{ $class->yearleveldesc }}"
                                        {{ $isSelected ? 'selected' : '' }}>
                                    {{ $class->progAcronym }} {{ $class->classSection }}
                                </option>
                                @empty
                                    <option disabled>No sections available for your program.</option>
                                @endforelse
                            </select>
                        </div>

                        <input type="hidden" id="programIDInput" name="studClassID" class="form-control form-control-sm" readonly>
                        <input type="hidden" id="programCodeInput" name="progCod" class="form-control form-control-sm" readonly>
                        <input type="hidden" id="numericPart" name="studYear" placeholder="Numeric Part">
                        <input type="hidden" id="alphabeticalPart" name="studSec" placeholder="Alphabetical Part">

                        <div class="col-12 col-md-2">
                            <label class="text-bold">Total Units</label>
                            <input type="text" id="totalunitInput" name="studUnit" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="col-12 col-md-2">
                            <label class="text-bold">Year Level</label>
                            <input type="text" id="yearsectionInput" name="" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="col-12 col-md-5">
                            <label class="text-bold">Program Name</label>
                            <input type="text" id="programNameInput" name="" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="col-12 col-md-7">
                            <label class="text-bold">Student Level</label>
                            <input type="hidden" name="studLevel" id="studLevelHidden" value="50">
                            <select class="form-control form-control-sm" name="studLevel" id="studLevel" disabled style="background-color: #fff !important">
                                <option disabled selected> --Select Course-- </option>
                                @foreach ($studlvl as $data)
                                    <option value="{{ $data->id }}" {{ $data->id == 50 ? 'selected' : '' }}>{{ $data->studLevel }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-9">
            <div class="content-box">
                <h5>Subjects Section</h5>
                <hr>
                <div class="" style="font-size: 10pt">
                    <table id="subjectTable" class="table table-striped table-bordered">
                        <thead style="font-weight: normal">
                            <tr>
                                <th>ID</th>
                                <th>SubjCode</th>
                                <th>SubjName</th>
                                <th>Description</th>
                                <th>Credit</th>
                                <th>LecFee</th>
                                <th>LabFee</th>
                                <th>DevFee</th>
                                <th>ITSubj</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="content-box">
                <h5>Fees Section</h5>
                <hr>
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-4">
                        Tuition: <input type="text" id="totalLecFeeInput" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        LabFee: <input type="text" id="totalLabFeeInput" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="col-12 col-md-4">
                        DevFee: <input type="text" id="totalDevFeeInput" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="col-md-12 mt-1">
                        <input type="hidden" id="itsubjInput" class="form-control form-control-sm" readonly>
                    </div>
                </div>      
                <input type="hidden" id="subjIDsInput" name="subjIDs" class="form-control form-control-sm" readonly>
                <hr>
                <div class="row g-3 align-items-end">
                    <button class="btn btn-success btn-block" id="submitPreButton">Submit Pre-enrolment</button>
                </div>
            </div>
        </div>
        <br>
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

        var checkEnrollmentRoute  = "{{ route('checkPreEnroll') }}";
        var fetchTemplateRoute  = "{{ route('fetchpreenrolSubjects') }}";
        var savePreEnrollmentRoute  = "{{ route('studPreEnrollmentCreate') }}";
        var preenrolRoute  = "{{ route('pre.index') }}";
    </script>
@endsection
