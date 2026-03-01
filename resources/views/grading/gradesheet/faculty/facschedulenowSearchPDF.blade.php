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
    <div class="row">
        <div class="col-12">
            <div class="mb-6">
                <h1 class="fs-5 mb-4 d-none d-md-block">Schedule</h1>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header pt-3">
                                <h6 class="card-title">
                                    <i class="ti ti-file"></i> View Schedule
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ route('schedulefac_searchview') }}" id="attendancegrading">
                                    @csrf   

                                    <div class="">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>School Year: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="schlyear">
                                                    @foreach($sy as $datasy)
                                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Semester: <span class="text-danger">*</span></label>
                                                <select class="form-control form-control-sm" name="semester">
                                                    <option disabled selected>Select</option>
                                                    <option value="1">First Semester</option>
                                                    <option value="2">Second Semester</option>
                                                    <option value="3">Summer</option>
                                                </select> 
                                            </div>

                                            <div class="col-md-3">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">OK</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <button id="viewSchedule" class="btn btn-success btn-xs ml-1">
                                                <i class="fas fa-eye"></i> View Faculty Loading/Schedule
                                            </button>
                                            {{-- <button id="viewFacultyLoad" class="btn btn-secondary btn-xs ml-1">
                                                <i class="fas fa-bars-progress"></i> Faculty Loading
                                            </button> --}}
                                            <button type="button" id="refreshSchedule" class="btn btn-danger btn-xs ml-1">
                                                <i class="fas fa-sync"></i> Refresh
                                            </button>  
                                            <div id="schedule-grid" class="mt-4"></div>
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

    <!-- Schedule View Modal -->
    <div class="modal fade" id="viewfacultyScheduleModal" tabindex="-1" role="dialog" aria-labelledby="viewfacultyScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
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
                        <button id="printSchedule" class="btn btn-success btn-md">
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
