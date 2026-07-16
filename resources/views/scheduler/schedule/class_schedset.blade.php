@extends('layouts.master_classSetSchedule')

@section('title')
CISS V.1.0 || Class Scheduler
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
                                <a href="{{ route('classSchedRead') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Class Scheduler</li>
                            <li class="breadcrumb-item active mt-1">Plot Class Schedule</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Plot Class Schedule</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('classSchedSetRead') }}" id="classsched">
                                            @csrf

                                            <div class="mt-2">
                                                <div class="form-group">
                                                    <div class="row g-3">
                                                        <div class="col-md-2">
                                                            <label>Academic Year: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="schlyear" id="schlyear1">
                                                                @foreach($sy as $datasy)
                                                                    <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Semester: <span class="text-danger">*</span></label>
                                                            <select class="form-control  form-control-sm" name="semester" id="semester">
                                                                <option disabled selected>---Select---</option>
                                                                <option value="1">First Semester</option>
                                                                <option value="2">Second Semester</option>
                                                                <option value="3">Summer</option>
                                                            </select>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label>Course: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm select2bs4" name="progCod" id="progCod">
                                                                <option disabled selected>Select a course</option>
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

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="row">
                                            <div class="col-md-8 mt-3">
                                                <div class="breadcrumb" style="font-size: 13pt">
                                                    <span>Course: {{ $progAcronym ?? 'Not Available' }} {{ $progCodSuffix ?? 'Not Available' }},</span>
                                                    <span class="ml-2">School Year: {{ request('schlyear') }},</span>
                                                    <span class="ml-2">
                                                        Semester: 
                                                        @if(request('semester') == 1)
                                                            1st Sem
                                                        @elseif(request('semester') == 2)
                                                            2nd Sem
                                                        @elseif(request('semester') == 3)
                                                            Summer
                                                        @else
                                                            Unknown Semester
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <div class="g-3">
                                                    <button type="button" id="viewSchedule" class="btn btn-success btn-sm">
                                                        <i class="fas fa-eye"></i> View Schedule
                                                    </button>
                                                    <button type="button" id="refreshSchedule" class="btn btn-info btn-sm">
                                                        <i class="fas fa-sync"></i> Refresh
                                                    </button>  
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#viewScheduletoDeleteModal" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Delete Schedule
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-1">
                                                <div id="schedule-grid"></div>
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

    <!-- View Schedule Modal -->
    <div class="modal fade mt-6" id="viewScheduleModal" tabindex="-1" role="dialog" aria-labelledby="viewScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="viewScheduleModalLabel">
                        <span>Course: {{ $progAcronym ?? 'Not Available' }} {{ $progCodSuffix ?? 'Not Available' }},</span>
                            <span class="ml-2">School Year: {{ request('schlyear') }},</span>
                            <span class="ml-2">
                                Semester: 
                                @if(request('semester') == 1)
                                    1st Sem
                                @elseif(request('semester') == 2)
                                    2nd Sem
                                @elseif(request('semester') == 3)
                                    Summer
                                @else
                                    Unknown Semester
                                @endif
                            </span>
                    </h5>
                    <div>
                        <button id="printSchedule" class="btn btn-info btn-md">
                            <i class="fas fa-print"></i> Print Schedule
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                <div class="modal-body" id="schedule-view">
                    <!-- Schedule content will be dynamically inserted here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add Schedule Modal -->
    <div class="modal fade mt-6" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Schedule Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="scheduleForm" class="addclasssched">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" class="form-control form-control-sm" id="day" name="schedday" readonly>
                        <input type="hidden" class="form-control form-control-sm" id="start_time" name="start_time" readonly>
                        <input type="hidden" class="form-control form-control-sm" id="end_time" name="end_time" readonly>
                        <input type="hidden" class="form-control form-control-sm" id="progcodename" name="progcodename" value="{{ $progCodPart }}" readonly>
                        <input type="hidden" class="form-control form-control-sm" id="progcodesection" name="progcodesection" value="{{ $progCodSuffix }}" readonly>
                        <input type="hidden" class="form-control form-control-sm" id="progschlyear" name="schlyear" value="{{ request('schlyear') }}" readonly>
                        <input type="hidden" class="form-control form-control-sm" id="progsemester" name="semester" value="{{ request('semester') }}" readonly>
                        <input type="hidden" class="form-control form-control-sm" id="progpostedBy" name="postedBy" value="{{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }}" readonly>
                        <input type="hidden" class="form-control form-control-sm" id="campus" name="campus" value="{{ Auth::guard('web')->user()->campus }}" readonly>

                        
                        <div id="selected-time-range" class="mb-3"></div>
                        
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="subject_id">Select Subject: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm select2" data-placeholder="Select Subjects" id="subject_id" name="subject_id" ></select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="faculty_id">Select Faculty: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm select2" data-placeholder="Select Faculty" id="faculty_id" name="faculty_id" >
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="room_id">Select Room: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm select2" data-placeholder="Select Room" id="room_id" name="room_id">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="remarks">Select Remarks: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="remarks" name="remarks">
                                        <option disabled selected> --Select-- </option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Merge Toggle -->
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_merged" name="is_merged" value="1">
                                        <label class="form-check-label" for="is_merged">
                                            <strong>Merge Multiple Sections?</strong>
                                            <small class="text-muted d-block">Check this if you want to combine sections (e.g., 1-A and 1-B)</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sections to Merge (hidden by default) -->
                        <div class="form-group mt-3" id="merge_sections_container" style="display: none;">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="merge_sections">Select Sections to Merge: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm select2" 
                                        id="merge_sections" 
                                        name="merge_sections[]" 
                                        multiple="multiple" 
                                        data-placeholder="Select sections to merge">
                                    </select>
                                    <small class="text-muted">Hold Ctrl/Cmd to select multiple sections</small>
                                    <div id="selected_sections_info" class="mt-2" style="display:none;">
                                        <span class="badge bg-info">Selected: <span id="selected_count">0</span> sections</span>
                                        <span class="badge bg-success">Main: <span id="main_section_display">{{ $progCodSuffix }}</span></span>
                                        <div class="alert alert-info mt-2">
                                            <i class="fas fa-info-circle"></i> 
                                            This schedule will be applied to: <strong id="all_sections_display">{{ $progCodSuffix }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" id="saveSchedule">Save Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Schedule Modal -->
    <div class="modal fade mt-6" id="viewScheduletoDeleteModal" tabindex="-1" role="dialog" aria-labelledby="viewScheduletoDeleteModalLabel" aria-hidden="true"  style="z-index: 9998 !important;" >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="viewScheduletoDeleteModalLabel">
                        <span>Course: {{ $progAcronym ?? 'Not Available' }} {{ $progCodSuffix ?? 'Not Available' }},</span>
                            <span class="ml-2">School Year: {{ request('schlyear') }},</span>
                            <span class="ml-2">
                                Semester: 
                                @if(request('semester') == 1)
                                    1st Sem
                                @elseif(request('semester') == 2)
                                    2nd Sem
                                @elseif(request('semester') == 3)
                                    Summer
                                @else
                                    Unknown Semester
                                @endif
                            </span>
                    </h5>
                    <div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                <div class="modal-body">
                    <table id="asd" width="100%" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Subject Section</th>
                                <th>Descriptive Title</th>
                                <th>Faculty</th>
                                <th>Day & Time</th>
                                <th width="2%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Table data goes here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        var classenrollyrsecReadRoute = "{{ route('getCoursesyearsec') }}";
        var classSubOfferSchedReadRoute = "{{ route('getSubjectsClassSched') }}";
        var classFacultySchedReadRoute = "{{ route('getFacultyClassSched') }}";
        var classRoomSchedReadRoute = "{{ route('getRoomClassSched') }}";
        var getAllSubjectsMergeRoute = "{{ route('getAllSubjectsForMerge') }}";

        var classplottedReadRoute = "{{ route('getschedclassplotted') }}";
        var classplottedDeleteRoute = "{{ route('schedclassplottedDelete', ['id' => ':id']) }}";

        var authUserFname = "{{ Auth::guard('web')->user()->fname }} {{ Auth::guard('web')->user()->lname }}";
        var authUserRole  = "{{ Auth::guard('web')->user()->role }}";
    </script>

    <script>
        var days = @json($days);
        var times = @json($times);
    </script>
@endsection
