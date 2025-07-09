@extends('layouts.master_faculty')

@section('title')
CISS V.1.0 || Grading
@endsection

@section('sideheader')
<h4>Grading</h4>
@endsection

@section('sideheaderlegend')
<h4>Legend</h4>
@endsection

@yield('sidemenu')

@section('workspace')
<div class="card">
    <div class="card-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('homefaculty') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li class="breadcrumb-item mt-1">My Teaching Schedule</li>
            <li class="breadcrumb-item active mt-1">Search</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                <h4>My Teaching Schedule</h4>
            </div>
        </div>

        <div class="mt-1 row">
            <div class="col-md-12">
                <form method="GET" action="{{ route('schedulefac_searchview') }}" id="attendancegrading">
                    @csrf   

                    <div class="form-group mt-2" style="padding: 10px">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">School Year</span></label>
                                <select class="form-control form-control-sm" name="schlyear">
                                    @foreach($sy as $datasy)
                                        <option value="{{ $datasy->schlyear }}" {{ request('schlyear') == $datasy->schlyear ? 'selected' : '' }}>{{ $datasy->schlyear }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Semester</span></label>
                                <select class="form-control form-control-sm" name="semester">
                                    <option disabled selected>Select</option>
                                    <option value="1" {{ request('semester') == 1 ? 'selected' : '' }}>First Semester</option>
                                    <option value="2" {{ request('semester') == 2 ? 'selected' : '' }}>Second Semester</option>
                                    <option value="3" {{ request('semester') == 3 ? 'selected' : '' }}>Summer</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="breadcrumb">
                            <button id="viewSchedule" class="btn btn-secondary btn-xs ml-1">
                                <i class="fas fa-eye"></i> View Faculty Loading/Schedule
                            </button>
                            {{-- <button id="viewFacultyLoad" class="btn btn-secondary btn-xs ml-1">
                                <i class="fas fa-bars-progress"></i> Faculty Loading
                            </button> --}}
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
<div class="modal fade" id="viewfacultyScheduleModal" tabindex="-1" role="dialog" aria-labelledby="viewfacultyScheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="viewfacultyScheduleModalLabel">
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
                        <i class="fas fa-print"></i> Print Faculty Loading/Schedule
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

<script>
    var classSubOfferSchedReadRoute = "{{ route('getSubjectsClassSchedFac') }}";
    var classenrollyrsecReadRoute = "{{ route('getCoursesyearsecFac') }}";
    var classenrollyrsecReadRoute = "{{ route('getCoursesyearsec') }}";
    var classRoomSchedReadRoute = "{{ route('getRoomClassSched') }}";
</script>



@endsection
