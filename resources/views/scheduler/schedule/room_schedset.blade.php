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
                                <a href="{{ route('roomSchedRead') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Room Scheduler</li>
                            <li class="breadcrumb-item active mt-1">Plot Room Class Schedule</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Plot Room Class Schedule</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('roomSchedSetRead') }}" id="roomsched">
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
                                                            <label>Room: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm select2bs4" data-placeholder="Select Room" id="room_id" name="room_id">
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
                                            <div class="col-md-4 mt-3">
                                                <div class="g-3">
                                                    <button id="viewSchedule" class="btn btn-secondary btn-sm">
                                                        <i class="fas fa-eye"></i> View Schedule
                                                    </button>
                                                    <button type="button" id="refreshSchedule" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-sync"></i> Refresh
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

    <!-- View Room Schedule Modal -->
    <div class="modal fade mt-6" id="viewRoomScheduleModal" tabindex="-1" role="dialog" aria-labelledby="viewRoomScheduleModalLabel" aria-hidden="true">
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
