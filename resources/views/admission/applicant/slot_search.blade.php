@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Admission
@endsection
@php
use App\Models\AdmissionDB\Time;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\Venue;
use App\Models\AdmissionDB\AdmissionDate;
@endphp

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
                            <li class="breadcrumb-item active mt-1">Slots</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Slots</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('slots_search') }}">
                                            @csrf

                                            <div class="form-group mt-3">
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label>Year: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" id="year" name="year">
                                                            @foreach($curryear as $datacurryear)
                                                                <option>{{ $datacurryear->adyear }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Campus: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="campus" id="campus">
                                                            <option value="{{Auth::user()->campus}}">
                                                                @if (Auth::user()->campus == 'MC') Main 
                                                                    @elseif(Auth::user()->campus == 'VC') Victorias 
                                                                    @elseif(Auth::user()->campus == 'SCC') San Carlos 
                                                                    @elseif(Auth::user()->campus == 'HC') Hinigaran 
                                                                    @elseif(Auth::user()->campus == 'MP') Moises Padilla 
                                                                    @elseif(Auth::user()->campus == 'IC') Ilog 
                                                                    @elseif(Auth::user()->campus == 'CA') Candoni 
                                                                    @elseif(Auth::user()->campus == 'CC') Cauayan 
                                                                    @elseif(Auth::user()->campus == 'SC') Sipalay 
                                                                    @elseif(Auth::user()->campus == 'HinC') Hinobaan 
                                                                    @elseif(Auth::user()->campus == 'VE') Valladolid 
                                                                @endif
                                                            </option>
                                                            @if(Auth::user()->role == 0 || (Auth::user()->campus == 'MC' && Auth::user()->role == 1))
                                                                <option value="MC">Main</option>
                                                                <option value="VC">Victorias</option>
                                                                <option value="SCC">San Carlos</option>
                                                                <option value="HC">Hinigaran</option>
                                                                <option value="MP">Moises Padilla</option>
                                                                <option value="IC">Ilog</option>
                                                                <option value="CA">Candoni</option>
                                                                <option value="CC">Cauayan</option>
                                                                <option value="SC">Sipalay</option>
                                                                <option value="HinC">Hinobaan</option>
                                                                <option value="VE">Valladolid</option>
                                                            @else
                                                            @endif
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>&nbsp;</label>
                                                        <button type="submit" class="form-control form-control-sm btn btn-success btn-sm">Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="">
                @php
                    use App\Models\AdmissionDB\Year;
                    use Carbon\Carbon;

                    $currentYear = Year::where('status', 'On')->value('adyear');
                    $campus = Auth::guard('web')->user()->campus;
                @endphp

                @foreach ($dateAd as $date)
                    @php
                        // Normalize the admission date
                        $admissionDate = Carbon::parse($date->date)->format('Y-m-d');

                        // Get all time slots for this date and campus
                        $slots = Time::whereDate('date', $admissionDate)
                            ->where('campus', $campus)
                            ->orderBy('time', 'asc')
                            ->get();
                    @endphp

                    @if ($slots->count())
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Admission Date: {{ Carbon::parse($admissionDate)->format('F d, Y') }}</h5>
                            </div>

                            <div class="card-body p-0">
                                <table class="table table-striped mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Time</th>
                                            <th>Availability</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($slots as $slot)
                                            @php
                                                // Count all applicants for this date + time + campus
                                                $booked = Applicant::whereDate('d_admission', $admissionDate)
                                                    ->where('time', $slot->time)
                                                    ->where('campus', $campus)
                                                    ->where('p_status', '!=', 7)
                                                    ->count();

                                                $remaining = $slot->slots - $booked;
                                            @endphp

                                            <tr>
                                                <td>{{ Carbon::createFromFormat('H:i:s', $slot->time)->format('h:i A') }}</td>
                                                <td>
                                                    <span class="badge bg-success">{{ $booked }}</span>
                                                    / <span class="badge bg-secondary">{{ $slot->slots }} </span>
                                                    <small class="text-muted">({{ $remaining }} left)</small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @endforeach
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
