@extends('layouts.master_track')

@section('title')
CISS V.1.0 || Track Admission
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
                                <a href="{{ route('admission-portal') }}" class="btn btn-success btn-sm text-light">
                                    <i class="fas fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item mt-1">Track Admission</li>
                            <li class="breadcrumb-item active mt-1">Dashboard</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body p-4">
                                @if(Session::has('success'))
                                    <div class="alert alert-success">{{ Session::get('success')}}</div>
                                @elseif (Session::has('error'))
                                    <div class="alert alert-danger">{{Session::get('error')}}</div>
                                @endif
                                <form method="POST" action="{{ route('admission_track_status') }}">
                                    @csrf
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="lastname">Last Name: <span class="text-danger">*</span></label>
                                            <input type="text" name="lname" placeholder="Enter Last Name" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="firstname">First Name: <span class="text-danger">*</span></label>
                                            <input type="text" name="fname" placeholder="Enter First Name" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="searchbutton">&nbsp;</label>
                                            <button type="submit" class="btn btn-success btn-block btn-sm text-light">
                                                Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <h2 class="fs-4"><i class="ti ti-user"></i> Applicant Information</h2>
                                    <hr>
                                    <div class="mt-4">
                                        <div class="table-responsive">
                                            <table class="table mb-0">
                                                <tbody>
                                                    <tr>
                                                        <th style="width: 35%">Admission ID:</th>
                                                        <td style="font-style: italic">{{ $data->first()->admission_id }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 35%">Name:</th>
                                                        <td style="font-style: italic">{{ $data->first()->fname }} {{ substr($data->first()->mname, 0,2) }} {{ $data->first()->lname }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 35%">Preferred Campus:</th>
                                                        <td style="font-style: italic">
                                                            @if ($data->first()->campus == 'MC') Main Campus
                                                            @elseif($data->first()->campus == 'VC') Victorias Campus
                                                            @elseif($data->first()->campus == 'SCC') San Carlos Campus
                                                            @elseif($data->first()->campus == 'MP') Moises Padilla Campus
                                                            @elseif($data->first()->campus == 'HC') Hinigaran Campus
                                                            @elseif($data->first()->campus == 'IC') Ilog Campus
                                                            @elseif($data->first()->campus == 'CA') Candoni Campus
                                                            @elseif($data->first()->campus == 'CC') Cauayan Campus
                                                            @elseif($data->first()->campus == 'SC') Sipalay Campus
                                                            @elseif($data->first()->campus == 'HinC') Hinobaan Campus
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 35%">Date of Birth:</th>
                                                        <td style="font-style: italic">{{ \Carbon\Carbon::parse($data->first()->bday)->format('F d, Y') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 35%">Email Address:</th>
                                                        <td style="font-style: italic">{{ $data->first()->email }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 35%">Contact No.:</th>
                                                        <td style="font-style: italic">{{ $data->first()->contact }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 35%">Present Address:</th>
                                                        <td style="font-style: italic">{{ $data->first()->address }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <h2 class="fs-4"><i class="ti ti-checklist"></i> Admission Status</h2>
                                    <hr>
                                    <div class="tracking-list">
                                        <div class="tracking-item">
                                            <div class="tracking-icon status-delivered">
                                                <i class="ti ti-check fa-1x"></i>
                                            </div>
                                            <div class="tracking-date"><span>{{ $data->first()->created_at->format('F d, Y') }}</span><span>{{ $data->first()->created_at->format('h:i A') }}</span></div>
                                            <div class="tracking-content">Application<span>Submitted/Recorded Applicant Information</span></div>
                                        </div>

                                        <div class="tracking-item">
                                            @if ( !empty($data->first()->d_admission) && !empty($data->first()->time) && !empty($data->first()->venue))
                                                <div class="tracking-icon status-delivered">
                                                    <i class="ti ti-check fa-1x"></i>
                                                </div>
                                            @else
                                                <div class="tracking-icon status-intransit">
                                                    <i class="fas fa-times fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="tracking-date"><span>{{ $data->first()->updated_at->format('F d, Y') }}</span><span>{{ $data->first()->updated_at->format('h:i A') }}</span></div>
                                            <div class="tracking-content">
                                                Examination Schedule
                                                <span>
                                                    Date:
                                                    @if (!empty($data->first()->d_admission))
                                                        <i class="text-success">{{ \Carbon\Carbon::parse($data->first()->d_admission)->format('F d, Y') }}</i>
                                                    @else
                                                        <i class="text-danger">Waiting</i>
                                                    @endif,

                                                    Time:
                                                    @if (!empty($data->first()->time))
                                                        <i class="text-success">{{ \Carbon\Carbon::parse($data->first()->t_admission)->format('h:i A') }}</i>
                                                    @else
                                                        <i class="text-danger">Waiting</i>
                                                    @endif,

                                                    Venue:
                                                    @if (!empty($data->first()->venue))
                                                        <i class="text-success">{{ $data->first()->venue }}</i>
                                                    @else
                                                        <i class="text-danger">Waiting</i>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        @php
                                            $item = $data->first();
                                            $score = is_numeric($item->raw_score ?? null) ? (int) $item->raw_score : null;

                                            $stanine = null;

                                            if ($score !== null) {
                                                if ($score >= 1 && $score <= 12) $stanine = 1;
                                                elseif ($score <= 18) $stanine = 2;
                                                elseif ($score <= 23) $stanine = 3;
                                                elseif ($score <= 29) $stanine = 4;
                                                elseif ($score <= 36) $stanine = 5;
                                                elseif ($score <= 43) $stanine = 6;
                                                elseif ($score <= 49) $stanine = 7;
                                                elseif ($score <= 56) $stanine = 8;
                                                elseif ($score <= 72) $stanine = 9;
                                            }
                                        @endphp
                                        <div class="tracking-item">
                                            @if ( $stanine !== null)
                                                <div class="tracking-icon status-delivered">
                                                    <i class="ti ti-check fa-1x"></i>
                                                </div>
                                            @else
                                                <div class="tracking-icon status-intransit">
                                                    <i class="fas fa-times fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="tracking-date"><span>{{ \Carbon\Carbon::parse($data->first()->rawscoredate)->format('F d, Y') }}</span><span>{{ \Carbon\Carbon::parse($data->first()->rawscoredate)->format('h:i A') }}</span></div>
                                            <div class="tracking-content">
                                                Examination Results
                                                <span>
                                                    @if ($data->first()->campus !=  'MC')
                                                        Stanine:
                                                        @if ($stanine !== null)
                                                            <i class="text-success">{{ $stanine }}</i>
                                                        @else
                                                            <i class="text-danger">Waiting</i>
                                                        @endif
                                                        ,
                                                    @endif

                                                    Remarks:
                                                    @if (!empty($data->first()->percentile))
                                                        <i class="text-success">{{ $data->first()->percentile }}</i>
                                                    @else
                                                        <i class="text-danger">Waiting</i>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>

                                        <div class="tracking-item">
                                            @if ($data->first()->p_status == 4 || $data->first()->p_status == 5 || $data->first()->p_status == 6 || $data->first()->p_status == 7) 
                                                <div class="tracking-icon status-delivered">
                                                    <i class="ti ti-check fa-1x"></i>
                                                </div>
                                            @else
                                                <div class="tracking-icon status-intransit">
                                                    <i class="fas fa-times fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="tracking-date"><span>{{ \Carbon\Carbon::parse($data->first()->examresultdate)->format('F d, Y') }}</span><span>{{ \Carbon\Carbon::parse($data->first()->examresultdate)->format('h:i A') }}</span></div>
                                            <div class="tracking-content">
                                                Confirmation
                                                <span>
                                                    Remarks:
                                                    @if (!empty($data->first()->percentile))
                                                        <i class="text-success">{{ $data->first()->percentile }} <i class="text-dark">( Proceed to Pre-Enrollment )</i></i>
                                                    @else
                                                        <i class="text-danger">Waiting for confirmation on Pre-enrolment</i>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="tracking-item">
                                            @if ($data->first()->p_status == 6 || $data->first()->p_status == 7) 
                                                <div class="tracking-icon status-delivered">
                                                    <i class="ti ti-check fa-1x"></i>
                                                </div>
                                            @else
                                                <div class="tracking-icon status-intransit">
                                                    <i class="fas fa-times fa-2x"></i>
                                                </div>
                                            @endif
                                            <div class="tracking-date"><span>{{ \Carbon\Carbon::parse($data->first()->deptratingdate)->format('F d, Y') }}</span><span>{{ \Carbon\Carbon::parse($data->first()->deptratingdate)->format('h:i A') }}</span></div>
                                            <div class="tracking-content">
                                                Acceptance
                                                <span>
                                                    Accepted for the program: 
                                                    @if (!empty($data->first()->percentile))
                                                        <i class="text-success">{{ $data->first()->course }}</i>
                                                    @else
                                                        <i class="text-danger">Waiting</i>
                                                    @endif
                                                </span>
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
@endsection
