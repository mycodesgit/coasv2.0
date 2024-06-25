@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Student Info
@endsection

@section('sideheader')
<h4>Enrollment</h4>
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
            <li class="breadcrumb-item mt-1">Enrollment</li>
            <li class="breadcrumb-item mt-1">Reports</li>
            <li class="breadcrumb-item active mt-1">Student Info</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                <h4>Student Info</h4>
            </div> 
        </div>

        <div class="mt-3" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('studInfo_search') }}">
                {{ csrf_field() }}

                <div class="">
                    <div class="form-group">
                        <div class="form-row">
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
            <h5>Search Results:
                <small>
                    <i>
                        Campus-<b>{{ request('campus') }}</b>,
                    </i>
                </small>
            </h5>
        </div>

        <div class="mt-5">
            <div class="">
                <table id="studinfoall" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Student ID</th>
                            <th>Gender</th>
                            <th>Civil Status</th>
                            <th>City</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<div class="modal fade" id="viewdatastudModal" role="dialog" aria-labelledby="viewdatastudModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewdatastudModalLabel">View Student Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="">
                <div class="modal-body">
                    <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                        <h4>Student Information</h4>
                    </div>
                    <input type="hidden" name="id" id="viewdatastudIdprim">
                    <div class="form-group mt-3">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Student ID No.</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdatastudID" readonly>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Firstname</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdatastudFname">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Middlename</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudMname">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Lastname</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudLname">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Ext. name</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudExt">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Gender</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudGender">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Birthday</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdatastudBday">
                            </div>
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Birth Place</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdatastudBdayp">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Mobile</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudMobile">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Email Address</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudEmail">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Religion</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudReligion">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Address</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudAddress">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">House No.</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdatastudHnum">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Street/Barangay</span></label>
                                <input type="text" class="form-control form-control-sm" name="" id="viewdatastudBrgy">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Munipality/City</span></label>
                                <select class="form-control form-control-sm" id="viewdatastudCity">
                                    <option value="">Select City</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Province</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudProvince">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Region</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudRegion">
                            </div>
                            <div class="col-md-2">
                                <label><span class="badge badge-secondary">Zip Code</span></label>
                                <input type="text" name="" class="form-control form-control-sm" id="viewdatastudZcode">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var studentlistinfoRoute = "{{ route('getstudInfo_search') }}";
    var appidEncryptRoute = "{{ route('idcrypt') }}";

    var isCampus = '{{ Auth::guard('web')->user()->campus }}';
    var requestedCampus = '{{ request('campus') }}'
</script>

@endsection
