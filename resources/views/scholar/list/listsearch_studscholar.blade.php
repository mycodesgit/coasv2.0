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

                <div class="">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <select class="form-control form-control-sm" name="schlyear">
                                    @foreach($sy as $datasy)
                                        <option value="{{ $datasy->schlyear }}">{{ $datasy->schlyear }}</option>
                                    @endforeach
                                </select>

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
                            <th>#</th>
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


<div class="modal fade" id="editstudSchEnModal" role="dialog" aria-labelledby="editstudSchEnModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editstudSchEnModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editstudSchEnForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editstudSchEnId">
                    <div class="form-group">
                        <label for="editstudSchEnStudID">Student ID No.</label>
                        <input type="text" id="editstudSchEnStudID" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editstudSchEnStudName">Student Name</label>
                        <input type="text" id="editstudSchEnStudName" class="form-control form-control-sm" readonly>
                    </div>
                    <div class="form-group">
                        <label for="editstudSchEnSch">Scholarship</label>
                        <select class="form-control form-control-sm" name="studSch" id="editstudSchEnSch">
                            <option disabled selected>--Select--</option>
                            @foreach($studsch as $datasch)
                                <option value="{{ $datasch->id }}">{{ $datasch->scholar_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var studschReadRoute = "{{ route('getstudscholarSearchRead') }}";
    var studschUpdateRoute = "{{ route('studscholarUpdate', ['id' => ':id']) }}";
    var idStudSchEncryptRoute = "{{ route('idcrypt') }}";
</script>

@endsection

@section('script')
