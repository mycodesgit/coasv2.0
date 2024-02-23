@extends('layouts.master_admission')

@section('title')
COAS - V1.0 || Admission Schedules
@endsection

@section('sideheader')
<h4>Admission</h4>
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
            <li class="breadcrumb-item mt-1">Admission</li>
            <li class="breadcrumb-item active mt-1">Admission Schedules</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">

            <form method="GET" action="{{ route('schedulesPDF_reports') }}" id="adSched" target="_blank">
                {{ csrf_field() }}

                <div class="container">
                    <div class="form-group">
                        <div class="form-row">
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

            <h5>Search Results: {{ $totalSearchResults }} 
                <small>
                    <i>Year-<b>{{ request('year') }}</b>,
                        Campus-<b>{{ request('campus') }}</b>,
                        Datetime-<b>
                            @php
                                $dateID = request('date');
                                $adTime = \App\Models\AdmissionDB\Time::where('id', $dateID)->first();

                                if ($adTime) {
                                    echo $adTime->date;
                                } else {
                                    echo 'Date not found in ad_time table';
                                }
                            @endphp
                        </b>,
                    </i>
                </small>
            </h5>
        </div>
        <div class="mt-5">
            <div class="">
                <table id="report" class="table table-hover">
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
                        @php $no = 1; @endphp
                        @foreach($data as $applicant)
                            @if ($applicant->p_status = [1,2])
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')