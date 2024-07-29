@extends('layouts.master_classSetSchedule')

@section('title')
CISS V.1.0 || Room Schedule
@endsection

@section('workspace')
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('roomSchedRead') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-left-long"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">Scheduler</li>
            <li class="breadcrumb-item active mt-1">Room Schedule</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-default">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="page-header">
            <form method="GET" action="{{ route('roomSchedSetRead') }}" id="roomsched">
                {{ csrf_field() }}

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Room Schedule</h4>
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
                                <label><span class="badge badge-secondary">Room</span></label>
                                <select class="form-control form-control-sm select2bs4" data-placeholder="Select Room" id="room_id" name="room_id">
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
                        <div class="col-md-6">
                            <div class="breadcrumb" style="font-size: 13pt">
                                <span>Room: {{ $roomName }},</span>
                                <span class="ml-2">{{ request('schlyear') }},</span>
                                <span class="ml-2">
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
                        <div class="col-md-6">
                            <div class="breadcrumb">
                                <button id="viewSchedule" class="btn btn-secondary btn-xs ml-1">
                                    <i class="fas fa-eye"></i> View Schedule
                                </button>
                                <button type="button" id="refreshSchedule" class="btn btn-primary btn-xs ml-1">
                                    <i class="fas fa-sync"></i> Refresh
                                </button>  
                            </div>
                        </div>
                    </div>
                    <div id="schedule-grid"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Schedule View Modal -->
    <div class="modal fade" id="viewRoomScheduleModal" tabindex="-1" role="dialog" aria-labelledby="viewRoomScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title" id="viewRoomScheduleModalLabel">
                        <span>Room: {{ $roomName }},</span>
                        <span class="ml-2">{{ request('schlyear') }},</span>
                        <span class="ml-2">
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
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <div class="modal-body" id="schedule-view">
                    <!-- Schedule content will be dynamically inserted here -->
                </div>
                <div class="modal-footer">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var classSubOfferSchedReadRoute = "{{ route('getSubjectsClassSchedFac') }}";
    var classenrollyrsecReadRoute = "{{ route('getCoursesyearsecFac') }}";
    var classenrollyrsecReadRoute = "{{ route('getCoursesyearsec') }}";
    var classRoomSchedReadRoute = "{{ route('getRoomClassSched') }}";
</script>

<script>
    var days = @json($days);
    var times = @json($times);
</script>


@endsection
