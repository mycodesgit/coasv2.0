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
    <section class="section mt-4">
        <!-- <div class="section-header" style="border-radius: 20px !important;">
            <h1>Blank Page</h1>
        </div> -->

        <div class="section-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="card" style="border-radius: 20px">
                        <div class="card-body">
                            <form method="GET" action="{{ route('schedulefac_searchview') }}" id="attendancegrading">
                                @csrf   

                                <div class="form-group mt-2">
                                    <div class="form-row">
                                        <div class="col-md-3">
                                            <label><span class="badge badge-success">School Year</span></label>
                                            <select class="form-control form-control-sm" name="schlyear">
                                                @foreach($sy as $datasy)
                                                    <option value="{{ $datasy->schlyear }}" {{ request('schlyear') == $datasy->schlyear ? 'selected' : '' }}>{{ $datasy->schlyear }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label><span class="badge badge-success">Semester</span></label>
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
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card" style="border-radius: 20px">
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
    </section>

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
