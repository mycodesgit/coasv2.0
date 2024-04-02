@extends('layouts.master_enrollment')

@section('title')
COAS - V2.0 || Student Info
@endsection

@section('sideheader')
<h4>Enrollment</h4>
@endsection

@yield('sidemenu')

@section('workspace')

<style>
    input:disabled {
        background-color: #fff !important;
    }
    select:disabled {
        background-color: #fff !important;
    }
</style>
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

        <div class="page-header" style="border-bottom: 1px solid #04401f;">
            <form method="GET" action="{{ route('studInfo_search') }}">
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
        </div>

        <div class="mt-5 row">
            <div class="col-md-3">
                <div class="card card-secondary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle"
                            src="{{ asset('template/img/CPSU_L.png') }}"
                            alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center">{{ $student->lname }}, {{ $student->fname }} {{ $student->mname }}</h3>
                        <p class="text-muted text-center">Software Engineer</p>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-md-9" style="background-color: #e9ecef; border-radius: 4px">
                <form method="post" action="" enctype="multipart/form-data" id="admissionApply">
                    @csrf
                    @method('PUT')

                    <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                        <h4>Student Information</h4>
                    </div>

                    <div class="form-group mt-2">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Studen ID No.</span></label>
                                <input type="text" class="form-control form-control-sm" name="admissionid" value="{{$student->admission_id}}" disabled>
                            </div>

                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Lastname</span></label>
                                <input type="text" class="form-control form-control-sm" value="{{$student->lname}}" oninput="this.value = this.value.toUpperCase()"  name="lastname" disabled>
                            </div>

                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Firstname</span></label>
                                <input type="text" class="form-control form-control-sm" value="{{$student->fname}}" oninput="this.value = this.value.toUpperCase()"  name="firstname" disabled>
                            </div>

                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Middlename</span></label>
                                <input type="text" class="form-control form-control-sm" value="{{$student->mname}}" oninput="this.value = this.value.toUpperCase()"  name="mname" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Ext.</span></label>
                                <select class="form-control form-control-sm" name="ext" disabled>
                                    <option>N/A</option>
                                    <option value="Jr." {{ $student->ext == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ $student->ext == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                    <option value="III" {{ $student->ext == 'III' ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ $student->ext == 'IV' ? 'selected' : '' }}>IV</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Gender</span></label>
                                <select class="form-control form-control-sm" name="gender" disabled>
                                    <option value="">Select</option>
                                    <option value="Male" {{ $student->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $student->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Birthdate</span></label>
                                <input type="date" class="form-control form-control-sm" value="{{$student->bday}}" name="bday" id="bday" onchange="calculateAge()" disabled>
                            </div>

                            <div class="col-md-3">
                                <label><span class="badge badge-secondary">Mobile</span></label>
                                <input type="number" class="form-control form-control-sm" value="{{$student->contact}}" name="contact" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="border-top: 1px solid #04401f;">
                        <div class="form-row mt-2">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Email Address</span></label>
                                <input type="text" class="form-control form-control-sm" value="{{$student->email}}" placeholder="e.g john@gmail.com" name="email" disabled>
                            </div>

                            <div class="col-md-8">
                                <label><span class="badge badge-secondary">Address</span></label>
                                <input type="text" class="form-control form-control-sm" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Present Address" name="address" value="{{$student->address}}" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="border-top: 1px solid #04401f;">
                        <div class="form-row mt-2">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Civil Status</span></label>
                                <select class="form-control form-control-sm" name="civil_status" disabled>
                                    <option disabled selected>Select</option>
                                    <option value="Single" {{ $student->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ $student->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Divorced" {{ $student->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                    <option value="Widowed" {{ $student->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Separated" {{ $student->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Religion</span></label>
                                <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="religion" value="{{ $student->religion }}" disabled>
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Parent's Monthly Income</span></label>
                                <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="monthly_income" value="{{ $student->monthly_income }}" disabled>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
    </div>
</div>

@endsection
