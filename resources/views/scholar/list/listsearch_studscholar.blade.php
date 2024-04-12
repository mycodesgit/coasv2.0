@extends('layouts.master_scholarship')

@section('title')
COAS - V2.0 || List of Scholars Student
@endsection

@section('sideheader')
<h4>Scholarship</h4>
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
            <li class="breadcrumb-item mt-1">Scholarship</li>
            <li class="breadcrumb-item active mt-1">Student Scholarship</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('studscholar_searchRead') }}" id="studscholar">
                @csrf

                <div class="container">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <select class="form-control form-control-sm" id="schlyear" name="schlyear"></select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Semester</span></label>
                                <select class="form-control  form-control-sm" name="semester">
                                    <option disabled selected>---Select---</option>
                                    <option value="1">First Semester</option>
                                    <option value="2">Second Semester</option>
                                    <option value="3">Summer</option>
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
            <h5>Search Results: {{-- {{ $totalSearchResults }} --}} 
                <small>
                    <i>Year-<b>{{ request('schlyear') }}</b>,
                        Semester-<b>{{ request('semester') }}</b>,
                        Campus-<b>
                            @if (Auth::guard('web')->user()->campus == 'MC') Main 
                                @elseif(Auth::guard('web')->user()->campus == 'SCC') San Carlos 
                                @elseif(Auth::guard('web')->user()->campus == 'VC') Victorias 
                                @elseif(Auth::guard('web')->user()->campus == 'HC') Hinigaran 
                                @elseif(Auth::guard('web')->user()->campus == 'MP') Moises Padilla 
                                @elseif(Auth::guard('web')->user()->campus == 'HinC') Hinobaan 
                                @elseif(Auth::guard('web')->user()->campus == 'SC') Sipalay 
                                @elseif(Auth::guard('web')->user()->campus == 'IC') Ilog 
                                @elseif(Auth::guard('web')->user()->campus == 'CC') Cauayan 
                            @endif
                        </b>,
                    </i>
                </small>
            </h5>
        </div>
        <div class="mt-2">
            <div class="">
                <table id="schstud" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>StudID</th>
                            <th>Course</th>
                            <th>Scholarhip</th>
                            <th>Sponsor</th>
                            <th>CHEDCategory</th>
                            <th>CPSUCategory</th>
                            <th>Tuition</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($data as $schstud)
                            <tr>
                                <td>{{ $schstud->lname }}, {{ $schstud->fname }}</td>
                                <td>{{ $schstud->studentID }}</td>
                                <td>{{ $schstud->progAcronym }} {{ $schstud->studYear }}-{{ $schstud->studSec }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">{{ $schstud->scholar_name }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">{{ $schstud->scholar_sponsor }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">{{ $schstud->chedsch_name }}</td>
                                <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 8ch;">{{ $schstud->unisch_name }}</td>
                                <td>{{ number_format($schstud->amount, 2)  }}</td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    var studschReadRoute = "{{ route('getstudscholarSearchRead') }}";
</script>

@endsection

@section('script')
