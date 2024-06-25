@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Configure Admission
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
            <li class="breadcrumb-item active mt-1">Configure Admission</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">

        </div>
        <div class="mt-5">
            <p>
                @if(Session::has('success'))
                    <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
                @elseif (Session::has('fail'))
                    <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                @endif
            </p>

            <div class="row">
                <div class="col-md-10">
                    <div class="tab-content" id="vert-tabs-right-tabContent">
                        <div class="tab-pane fade show active" id="vert-tabs-right-one" role="tabpanel" aria-labelledby="vert-tabs-right-one-tab">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="post" action="{{ route('add_Program') }}" enctype="multipart/form-data" id="adProg">
                                                @csrf
                                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                    <h5>Add Programs</h5>
                                                </div>

                                                <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                <div class="form-group">
                                                    <div class="form-row">
                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Program Code</span></label>
                                                            <input type="text" name="code" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>

                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Program Name</span></label>
                                                            <input type="text" name="program" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <table id="example1" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Program</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($program as $programs)
                                                <tr style="">
                                                <td>{{ $programs->code }}</td>
                                                <td>{{ $programs->program }}</td>
                                                <td style="text-align:center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu" style="">
                                                            @php
                                                                $allowedCampuses = ['MC', 'VC', 'SCC', 'MP', 'HC', 'IC', 'CA', 'CC', 'SC', 'HinC']; 
                                                            @endphp

                                                            @if(in_array($programs->campus, $allowedCampuses) && $programs->campus == Auth::user()->campus)
                                                                <a class="dropdown-item" href="{{ route('edit_program', ['id' => encrypt($programs->id)]) }}">
                                                                    <i class="fas fa-pen"></i> Edit
                                                                </a>
                                                            
                                                                <a class="dropdown-item" href="{{ route('programDelete', ['id' => $programs->id]) }}">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </a>
                                                            @else
                                                                <a href="#" class="dropdown-item">You don't have permission.</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="vert-tabs-right-two" role="tabpanel" aria-labelledby="vert-tabs-right-two-tab">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="post" action="{{ route('add_Strand') }}" enctype="multipart/form-data" id="adStrand">
                                                @csrf
                                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                    <h5>Add Strands</h5>
                                                </div>

                                                <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                <div class="form-group">
                                                    <div class="form-row">
                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Strand Code</span></label>
                                                            <input type="text" name="code" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>

                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Strand Name</span></label>
                                                            <input type="text" name="strand" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <table id="strand" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Strand</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($strand as $strand)
                                                <tr>
                                                    <td>{{ $strand->code}}</td>
                                                    <td>{{ $strand->strand }}</td>
                                                    <td style="text-align:center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <div class="dropdown-menu" role="menu" style="">
                                                            @php
                                                                $allowedCampuses = ['MC', 'VC', 'SCC', 'MP', 'HC', 'IC', 'CA', 'CC', 'SC', 'HinC']; 
                                                            @endphp

                                                            @if(in_array($strand->campus, $allowedCampuses) && $strand->campus == Auth::user()->campus)
                                                                <a class="dropdown-item" href="{{ route('edit_strand', ['id' => encrypt($strand->id)]) }}">
                                                                    <i class="fas fa-pen"></i> Edit
                                                                </a>
                                                                <a class="dropdown-item" href="{{ route('strandDelete', ['id' => $strand->id]) }}">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </a>
                                                            @else
                                                                <a href="#" class="dropdown-item">You don't have permission.</a>
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="vert-tabs-right-three" role="tabpanel" aria-labelledby="vert-tabs-right-three-tab">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="post" action="{{ route('add_admission_date') }}" enctype="multipart/form-data" id="adDateCon">
                                                @csrf
                                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                    <h5>Add Admission Date</h5>
                                                </div>

                                                <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                <div class="form-group">
                                                    <div class="form-row">
                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Admission Date</span></label>
                                                            <input type="date" name="date" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <table id="adDate" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Campus</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($date as $date)
                                                <tr>
                                                    <td>
                                                        @if ($date->campus == 'MC') Main 
                                                        @elseif($date->campus == 'VC') Victorias 
                                                        @elseif($date->campus == 'SCC') San Carlos 
                                                        @elseif($date->campus == 'HC') Hinigaran 
                                                        @elseif($date->campus == 'MP') Moises Padilla 
                                                        @elseif($date->campus == 'IC') Ilog 
                                                        @elseif($date->campus == 'CA') Candoni
                                                        @elseif($date->campus == 'CC') Cauayan 
                                                        @elseif($date->campus == 'SC') Sipalay 
                                                        @elseif($date->campus == 'HinC') Hinobaan 
                                                        @endif
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($date->date)->format('F d, Y') }}</td>
                                                    <td style="text-align:center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <div class="dropdown-menu" role="menu" style="">
                                                            @php
                                                                $allowedCampuses = ['MC', 'VC', 'SCC', 'MP', 'HC', 'IC', 'CA', 'CC', 'SC', 'HinC']; 
                                                            @endphp

                                                            @if(in_array($date->campus, $allowedCampuses) && $date->campus == Auth::user()->campus)
                                                                <a class="dropdown-item" href="{{ route('edit_date', ['id' => encrypt($date->id)]) }}">
                                                                    <i class="fas fa-pen"></i> Edit
                                                                </a>
                                                                <a class="dropdown-item" href="{{ route('dateDelete', ['id' => $date->id]) }}">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </a>
                                                            @else
                                                                <a href="#" class="dropdown-item">You don't have permission.</a>
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="vert-tabs-right-four" role="tabpanel" aria-labelledby="vert-tabs-right-four-tab">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="post" action="{{ route('add_admission_time') }}" enctype="multipart/form-data" id="adTimeCon">
                                                @csrf
                                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                    <h5>Add Admission Time</h5>
                                                </div>

                                                <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                <div class="form-group">
                                                    <div class="form-row">
                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Date</span></label>
                                                            <select class="form-control form-control-sm" name="date" style="text-transform: uppercase;">
                                                                <option value="">Select Date</option>
                                                                @foreach ($dates as $date)
                                                                <option value="{{ $date->date }}" @if (old('date') == "{{ $date->date }}") {{ 'selected' }} @endif>
                                                                    {{ Carbon\Carbon::parse($date->date)->format('F j, Y') }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Time</span></label>
                                                            <input type="time" name="time" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>

                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Slots</span></label>
                                                            <input type="number" name="slots" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <table id="adTime" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Campus</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                <th>Slots</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($time as $time)
                                            <tr>
                                                <td>
                                                    @if ($time->campus == 'MC') Main 
                                                    @elseif($time->campus == 'VC') Victorias 
                                                    @elseif($time->campus == 'SCC') San Carlos 
                                                    @elseif($time->campus == 'HC') Hinigaran 
                                                    @elseif($time->campus == 'MP') Moises Padilla 
                                                    @elseif($time->campus == 'IC') Ilog 
                                                    @elseif($time->campus == 'CA') Candoni
                                                    @elseif($time->campus == 'CC') Cauayan 
                                                    @elseif($time->campus == 'SC') Sipalay 
                                                    @elseif($time->campus == 'HinC') Hinobaan 
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($time->date)->format('F d, Y') }}</td>
                                                <td>@if ($time->time == NULL) @else {{\Carbon\Carbon::createFromFormat('H:i:s',$time->time)->format('h:i A')}}@endif</td>
                                                <td>{{ $time->slots }}</td>
                                                <td style="text-align:center;">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <div class="dropdown-menu" role="menu" style="">
                                                        @php
                                                            $allowedCampuses = ['MC', 'VC', 'SCC', 'MP', 'HC', 'IC', 'CA', 'CC', 'SC', 'HinC']; 
                                                        @endphp

                                                        @if(in_array($time->campus, $allowedCampuses) && $time->campus == Auth::user()->campus)
                                                            <a class="dropdown-item" href="{{ route('edit_time', ['id' => encrypt($time->id)]) }}">
                                                                <i class="fas fa-pen"></i> Edit
                                                            </a>
                                                            <a class="dropdown-item" href="{{ route('timeDelete', ['id' => $time->id]) }}">
                                                                <i class="fas fa-trash"></i> Delete
                                                            </a>
                                                        @else
                                                            <a href="#" class="dropdown-item">You don't have permission.</a>
                                                        @endif
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="vert-tabs-right-five" role="tabpanel" aria-labelledby="vert-tabs-right-five-tab">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <form method="post" action="{{ route('add_admission_venue') }}" enctype="multipart/form-data" id="adVenueCon">
                                                @csrf
                                                <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                                                    <h5>Add Venue</h5>
                                                </div>

                                                <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ request('campus') }}">

                                                <div class="form-group">
                                                    <div class="form-row">
                                                        <div class="mt-2 col-md-12">
                                                            <label><span class="badge badge-secondary">Admission Venue</span></label>
                                                            <input type="text" name="venue" class="form-control form-control-sm" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                                                        </div>

                                                        <div class="col-md-12">
                                                            <label>&nbsp;</label>
                                                            <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <table id="adVenue" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Campus</th>
                                                <th>Venue</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($venue as $venue)
                                                <tr>
                                                    <td>
                                                        @if ($venue->campus == 'MC') Main 
                                                        @elseif($venue->campus == 'VC') Victorias 
                                                        @elseif($venue->campus == 'SCC') San Carlos 
                                                        @elseif($venue->campus == 'HC') Hinigaran 
                                                        @elseif($venue->campus == 'MP') Moises Padilla 
                                                        @elseif($venue->campus == 'IC') Ilog 
                                                        @elseif($venue->campus == 'CA') Candoni 
                                                        @elseif($venue->campus == 'CC') Cauayan 
                                                        @elseif($venue->campus == 'SC') Sipalay 
                                                        @elseif($venue->campus == 'HinC') Hinobaan 
                                                        @endif
                                                    </td>
                                                    <td>{{ $venue->venue }}</td>
                                                    <td style="text-align:center;">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                            </button>
                                                            <div class="dropdown-menu" role="menu" style="">
                                                            @php
                                                                $allowedCampuses = ['MC', 'VC', 'SCC', 'MP', 'HC', 'IC', 'CA', 'CC', 'SC', 'HinC']; 
                                                            @endphp

                                                            @if(in_array($venue->campus, $allowedCampuses) && $venue->campus == Auth::user()->campus)
                                                                <a class="dropdown-item" href="{{ route('edit_venue', ['id' => encrypt($venue->id)]) }}">
                                                                    <i class="fas fa-pen"></i> Edit
                                                                </a>
                                                                <a class="dropdown-item" href="{{ route('venueDelete', ['id' => $venue->id]) }}">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </a>
                                                            @else
                                                                <a href="#" class="dropdown-item">You don't have permission.</a>
                                                            @endif
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="card" style="background-color: #e9ecef !important">
                        <div class="ml-2 mr-2 mt-3 mb-1">
                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                <h4>Menu</h4>
                            </div>
                            <div class="mt-3" style="font-size: 13pt;">
                                <div class="nav flex-column nav-pills nav-stacked nav-tabs-right h-100" id="vert-tabs-right-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-right-one-tab" data-toggle="pill" href="#vert-tabs-right-one" role="tab" aria-controls="vert-tabs-right-one" aria-selected="true">Programs</a>
                                    <a class="nav-link" id="vert-tabs-right-two-tab" data-toggle="pill" href="#vert-tabs-right-two" role="tab" aria-controls="vert-tabs-right-two" aria-selected="false">Strand</a>
                                    <a class="nav-link" id="vert-tabs-right-three-tab" data-toggle="pill" href="#vert-tabs-right-three" role="tab" aria-controls="vert-tabs-right-three" aria-selected="false">Admission Date</a>
                                    <a class="nav-link" id="vert-tabs-right-four-tab" data-toggle="pill" href="#vert-tabs-right-four" role="tab" aria-controls="vert-tabs-right-four" aria-selected="false">Schedule Time</a>
                                    <a class="nav-link" id="vert-tabs-right-five-tab" data-toggle="pill" href="#vert-tabs-right-five" role="tab" aria-controls="vert-tabs-right-five" aria-selected="false">Venue</a>
                                </div>
                                </div>
                        </div>
                    </div>
                </div>
                @include('modal.configure-modal')
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')
