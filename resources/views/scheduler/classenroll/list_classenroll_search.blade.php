@extends('layouts.master_classScheduler')

@section('title')
COAS - V2.0 || Classes Enrolled
@endsection

@section('sideheader')
<h4>Option</h4>
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
            <li class="breadcrumb-item mt-1">Scheduler</li>
            <li class="breadcrumb-item active mt-1">Option</li>
            <li class="breadcrumb-item active mt-1">Classes Enrolled</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('courseEnroll_list_search') }}" id="classEnroll">
                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Classes Enrolled</h4>
                </div>

                <div class="">
                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-10">
                                <label>&nbsp;</label>
                                <h5>Search Results: {{ $totalSearchResults }} 
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

                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <a href="{{ route('courseEnroll_list') }}" class="form-control form-control-sm btn btn-success btn-sm">New Sem</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div class="mt-3 row">
            <div class="col-md-3 mt-3 card" style="">
                <form method="post" action="{{ route('classEnrollCreate') }}"  id="classEnrollAdd">
                    @csrf
                    <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                        <h5>Add</h5>
                    </div>

                    <input type="hidden" name="campus" class="form-control  form-control-sm" value="{{ Auth::guard('web')->user()->campus; }}">

                    <div class="form-group mt-2">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Academic Year</span></label>
                                <input type="text" name="schlyear" class="form-control  form-control-sm" value="{{ request('schlyear') }}" readonly>
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Semester</span></label>
                                <input type="text" name="semester" class="form-control form-control-sm" value="{{ request('semester') }}" readonly>
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Programs</span></label>
                                <select class="form-control form-control-sm" name="progCode" id="">
                                    <option disabled selected>Select</option>
                                    @foreach ($program as $programs)
                                        <option value="{{ $programs->progCod }}">
                                            {{ $programs->progAcronym }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Year & Section</span></label>
                                <input type="text" name="classSection" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()">
                            </div>

                            <div class="mt-2 col-md-12">
                                <label><span class="badge badge-secondary">Est. Number of Student</span></label>
                                <input type="number" name="classno" class="form-control form-control-sm" min="0">
                            </div>

                            <div class="col-md-12">
                                <label>&nbsp;</label>
                                <button type="submit" class="form-control form-control-sm btn btn-primary btn-sm">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-9 mt-3 pl-3 pr-3 pt-3">
                <table id="classEn" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Year&Section</th>
                            <th>Est. No. of Student</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editClassEnModal" tabindex="-1" role="dialog" aria-labelledby="editStudFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFundModalLabel">Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editClassEnForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editclassenId">
                    <div class="form-group">
                        <label for="editclassen">Program</label>
                        <select class="form-control form-control-sm" id="editclassen" name="progCode">
                            <option disabled selected>Select</option>
                            @foreach ($program as $programs)
                                <option value="{{ $programs->progCod }}">
                                    {{ $programs->progAcronym }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editsection">Year & Section</label>
                        <input type="text" class="form-control" id="editsection" name="classSection">
                    </div>
                    <div class="form-group">
                        <label for="editclassno">Est. No. of Student</label>
                        <input type="number" class="form-control" id="editclassno" name="classno">
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
    var classEnReadRoute = "{{ route('getclassEnRead') }}";
    var classEnCreateRoute = "{{ route('classEnrollCreate') }}";
    var classEnUpdateRoute = "{{ route('classEnrolledUpdate', ['id' => ':id']) }}";
    var classEnDeleteRoute = "{{ route('classEnrolledDelete', ['id' => ':id']) }}";
</script>



@endsection

@section('script')