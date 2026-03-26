@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Admission
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
                                <a href="{{ route('home') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Admission</li>
                            <li class="breadcrumb-item active mt-1">Schedules</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Schedules</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('schedulesPDF_reports') }}" id="adSched" target="_blank">
                                            @csrf

                                            <div class="custom-container">
                                                <div class="form-group">
                                                    <div class="row g-3">
                                                        <div class="col-md-2">
                                                            <label>&nbsp;</label>
                                                            <a href="{{ route('schedules_printing') }}" class="form-control form-control-sm btn btn-success btn-sm">New Search</a>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="form-control form-control-sm btn btn-info btn-sm">Generate PDF</button>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <input type="hidden" name="year" value="{{ request('year') }}" class="form-control form-control-sm">
                                                        </div>

                                                        <div class="col-md-2">
                                                            <input type="hidden" name="campus" value="{{ request('campus') }}" class="form-control form-control-sm">
                                                        </div>

                                                        <div class="col-md-3">
                                                            <input type="hidden" name="date" value="{{ request('date') }}" class="form-control form-control-sm">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="table-responsive mt-3 p-2">
                                                    <table id="appsschedlistTable" class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th>App ID</th>
                                                                <th>Name</th>
                                                                <th>Type</th>
                                                                <th>Contact</th>
                                                                <th>Date & Time</th>
                                                                <th>Venue</th>
                                                                <th>Campus</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {{-- @php $no = 1; @endphp
                                                            @foreach($data as $applicant)
                                                                @if ($applicant->p_status != 7)  
                                                                <tr>
                                                                    <td>{{ $no++ }}</td>
                                                                    <td>{{ $applicant->admission_id }}</td>
                                                                    <td style="text-transform: uppercase;">
                                                                        <b>
                                                                            {{ $applicant->fname }} {{ substr($applicant->mname,0,1) }} {{ $applicant->lname }}
                                                                        </b>
                                                                    </td>
                                                                    <td>
                                                                        @if ($applicant->type == 1) New 
                                                                            @elseif($applicant->type == 2) Returnee 
                                                                            @elseif($applicant->type == 3) Transferee 
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $applicant->contact }}</td>
                                                                    <td>{{ Carbon\Carbon::parse($applicant->date . ' ' . $applicant->time)->format('F j, Y g:i A') }}</td>
                                                                    <td>{{ $applicant->venue }}</td>
                                                                    <td>{{ $applicant->campus }}</td>
                                                                </tr>
                                                                @else
                                                                @endif
                                                            @endforeach --}}
                                                        </tbody>
                                                    </table>
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
    </div>

    <script>
        var allApplicantSchedRoute = "{{ route('getschedulesreportsRead') }}";
    </script>
@endsection
