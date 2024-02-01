@extends('layouts.master_admission')

@section('title')
COAS - V1.0 || Slots
@endsection

@php
use App\Models\AdmissionDB\Time;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\Venue;
use App\Models\AdmissionDB\AdmissionDate;
@endphp


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
            <li class="breadcrumb-item active mt-1">Slots</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('slots_search') }}">
                {{ csrf_field() }}

                <div class="container">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Year</span></label>
                                <select class="form-control form-control-sm" id="year" name="year"></select>
                            </div>

                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Campus</span></label>
                                <select class="form-control form-control-sm" name="campus" id="campus">
                                    <option value="{{Auth::user()->campus}}">
                                        @if (Auth::user()->campus == 'MC') Main 
                                            @elseif(Auth::user()->campus == 'SCC') San Carlos 
                                            @elseif(Auth::user()->campus == 'VC') Victorias 
                                            @elseif(Auth::user()->campus == 'HC') Hinigaran 
                                            @elseif(Auth::user()->campus == 'MP') Moises Padilla 
                                            @elseif(Auth::user()->campus == 'HinC') Hinobaan 
                                            @elseif(Auth::user()->campus == 'SC') Sipalay 
                                            @elseif(Auth::user()->campus == 'IC') Ilog 
                                            @elseif(Auth::user()->campus == 'CC') Cauayan 
                                        @endif
                                    </option>
                                    @if (Auth::user()->isAdmin == 0)
                                        <option value="MC">Main</option>
                                        <option value="SCC">San Carlos</option>
                                        <option value="VC">Victorias</option>
                                        <option value="HC">Hinigaran</option>
                                        <option value="MP">Moises Padilla</option>
                                        <option value="HinC">Hinobaan</option>
                                        <option value="SC">Sipalay</option>
                                        <option value="IC">Ilog</option>
                                        <option value="CC">Cauayan</option>
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
                </div>
            </form>
           <h5>Search Results: {{ $totalSearchResults }}
                <small>
                    <i>Date-<b>{{ request('date') }}</b>,
                        Campus-<b>{{ request('campus') }}</b>,
                    </i>
                </small>
           </h5>
        </div>
        <div class="mt-5">
            <div class="">
                @foreach ($dateAd as $date)
                <h4>Admission Date: {{ \Carbon\Carbon::parse($date->date)->format('F d, Y') }}</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Availability</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @if ($slots =  Time::where('date', $date->date)->get())
                            @foreach($slots as $slot)
                                <tr>
                                    <td>{{\Carbon\Carbon::createFromFormat('H:i:s',$slot->time)->format('h:i A')}}</td>
                                    <td>
                                        <span class="badge badge-secondary">
                                            {{ $avail =  Applicant::where('time','=', $slot->time)->where('d_admission','=', $slot->date)->count() }}
                                        </span> / {{ $slot->slots }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')