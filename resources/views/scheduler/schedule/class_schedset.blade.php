@extends('layouts.master_classScheduler')

@section('title')
COAS - V2.0 || Class Schedule
@endsection

@section('sideheader')
<h4>Option</h4>
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

                <div class="container mt-1">
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

        <div class="container mt-3">
            <div class="row">
                <div class="col-md-12">
                    <div>
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
                                    <button class="btn btn-secondary btn-xs ml-1">View Schedule</button>
                                    <button class="btn btn-info btn-xs ml-1">Print Schedule</button>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="schedule-grid"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Schedule Class</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="scheduleForm">
                    <div class="modal-body">
                        <input type="hidden" id="day" name="day">
                        <input type="hidden" id="start_time" name="start_time">
                        <input type="hidden" id="end_time" name="end_time">
                        
                        <div id="selected-time-range" class="mb-3"></div>
                        
                        <div class="form-group">
                            <div class="form-row">
                                <label for="subject_id"><span class="badge badge-secondary">Select Subject</span></label>
                                <select id="subject_id" name="subject_id" class="form-control form-control-sm">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <label for="faculty_id"><span class="badge badge-secondary">Select Faculty</span></label>
                                <select id="faculty_id" name="faculty_id" class="form-control form-control-sm">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <label for="room_id"><span class="badge badge-secondary">Select Room</span></label>
                                <select id="room_id" name="room_id" class="form-control form-control-sm">
                                </select>
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
</div>

<script>
    var classenrollyrsecReadRoute = "{{ route('getCoursesyearsec') }}";
</script>

<script>
    var days = @json($days);
    var times = @json($times);
</script>


@endsection
