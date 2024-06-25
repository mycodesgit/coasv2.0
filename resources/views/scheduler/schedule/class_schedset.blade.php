@extends('layouts.master_classSetSchedule')

@section('title')
CISS V.1.0 || Class Schedule
@endsection

@section('workspace')
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('classSchedRead') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-left-long"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Scheduler</li>
            <li class="breadcrumb-item active mt-1">Class Schedule</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-default">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header">
            <form method="GET" action="{{ route('classSchedSetRead') }}" id="classEnroll">
                {{ csrf_field() }}

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Class Schedule</h4>
                </div>

                <div class="container-fluid mt-1">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <select class="form-control form-control-sm" name="schlyear" id="schlyear1">
                                    @foreach($sy as $datasy)
                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Semester</span></label>
                                <select class="form-control  form-control-sm" name="semester" id="semester">
                                    <option disabled selected>---Select---</option>
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Summer</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Course</span></label>
                                <select class="form-control form-control-sm" name="progCod" id="progCod">
                                    <option disabled selected>Select a course</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-8">
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
                            <div class="col-md-4">
                                <div class="breadcrumb">
                                    <button class="btn btn-danger btn-xs ml-1">Delete Schedule</button>
                                    <button id="viewSchedule" class="btn btn-secondary btn-xs ml-1">View Schedule</button>
                                    <button id="printSchedule" class="btn btn-info btn-xs ml-1">Print Schedule</button>  
                                </div>
                            </div>
                        </div>
                    <div id="schedule-grid"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Schedule Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="scheduleForm">
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
                        
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="subject_id"><span class="badge badge-secondary">Select Subject</span></label>
                                    <select class="form-control form-control-sm select2bs4" data-placeholder="Select Subjects" id="subject_id" name="subject_id" >
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="faculty_id"><span class="badge badge-secondary">Select Faculty</span></label>
                                    <select class="form-control form-control-sm select2bs4" data-placeholder="Select Faculty" id="faculty_id" name="faculty_id" >
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="room_id"><span class="badge badge-secondary">Select Room</span></label>
                                    <select class="form-control form-control-sm select2bs4" data-placeholder="Select Room" id="room_id" name="room_id">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="remarks"><span class="badge badge-secondary">Select Remarks</span></label>
                                    <select class="form-control form-control-sm" id="remarks" name="remarks">
                                        <option disabled selected> --Select-- </option>
                                        <option value="LEC">LEC</option>
                                        <option value="LAB">LAB</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveSchedule">Save Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal for viewing the schedule -->
    <div class="modal fade" id="viewScheduleModal" tabindex="-1" role="dialog" aria-labelledby="viewScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewScheduleModalLabel">View Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="schedule-view"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var classenrollyrsecReadRoute = "{{ route('getCoursesyearsec') }}";
    var classSubOfferSchedReadRoute = "{{ route('getSubjectsClassSched') }}";
    var classFacultySchedReadRoute = "{{ route('getFacultyClassSched') }}";
    var classRoomSchedReadRoute = "{{ route('getRoomClassSched') }}";
</script>

<script>
    var days = @json($days);
    var times = @json($times);
</script>


@endsection
