@extends('layouts.master_settings')

@section('title')
COAS - V2.0 || User's List
@endsection

@section('sideheader')
<h4>Settings</h4>
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
            <li class="breadcrumb-item mt-1">Settings</li>
            <li class="breadcrumb-item active mt-1">Account</li>
        </ol>

        <div class="page-header" style="border-bottom: 1px solid #04401f;"></div>

        <div class="mt-3">
            <p>
                @if(Session::has('success'))
                    <div class="alert alert-success" id="alert">{{ Session::get('success')}}</div>
                @elseif (Session::has('fail'))
                    <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
                @endif
            </p>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="" enctype="multipart/form-data" id="classEnrollAdd">
                                @csrf
                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                    <h5>Personal Info</h5>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">User Level</span></label>
                                            <select class="form-control form-control-sm" name="type" required="" style="text-transform: uppercase; pointer-events: none">
                                                <option value="{{$user->isAdmin}}">
                                                    @if ($user->isAdmin == 1) Guidance Officer 
                                                        @elseif($user->isAdmin == 2) Guidance Staff 
                                                        @elseif($user->isAdmin == 3) Registrar 
                                                        @elseif($user->isAdmin == 4) Registrar Staff 
                                                        @elseif($user->isAdmin == 5) College Dean 
                                                        @elseif($user->isAdmin == 6) Program Head 
                                                        @elseif($user->isAdmin == 7) College Staff 
                                                        @elseif($user->isAdmin == 8) Faculty
                                                    @endif
                                                </option>
                                                <option value="0" @if (old('type') == 0 || $user->isAdmin == '0') {{ 'selected' }} @endif>Administrator</option>
                                                <option value="1" @if (old('type') == 1 || $user->isAdmin == '1') {{ 'selected' }} @endif>Guidance Officer</option>
                                                <option value="2" @if (old('type') == 2 || $user->isAdmin == '2') {{ 'selected' }} @endif>Guidance Staff</option>
                                                <option value="3" @if (old('type') == 3 || $user->isAdmin == '3') {{ 'selected' }} @endif>Registrar</option>
                                                <option value="4" @if (old('type') == 4 || $user->isAdmin == '4') {{ 'selected' }} @endif>Registrar Staff</option>
                                                <option value="5" @if (old('type') == 5 || $user->isAdmin == '5') {{ 'selected' }} @endif>College Dean</option>
                                                <option value="6" @if (old('type') == 6 || $user->isAdmin == '6') {{ 'selected' }} @endif>Program Head</option>
                                                <option value="7" @if (old('type') == 7 || $user->isAdmin == '7') {{ 'selected' }} @endif>College Staff</option>
                                                <option value="8" @if (old('type') == 8 || $user->isAdmin == '8') {{ 'selected' }} @endif>Faculty</option>
                                            </select>
                                        </div>

                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">Campus</span></label>
                                            <select class="form-control form-control-sm" name="campus" required="" style="pointer-events: none;">
                                                <option disabled selected>Select</option>
                                                <option value="MC" @if (old('campus') == 'MC' || $user->campus == 'MC') {{ 'selected' }} @endif>Main</option>
                                                <option value="SCC" @if (old('campus') == 'SCC' || $user->campus == 'SCC') {{ 'selected' }} @endif>San Carlos</option>
                                                <option value="VC" @if (old('campus') == 'VC' || $user->campus == 'VC') {{ 'selected' }} @endif>Victorias</option>
                                                <option value="HC" @if (old('campus') == 'HC' || $user->campus == 'HC') {{ 'selected' }} @endif>Hinigaran</option>
                                                <option value="MP" @if (old('campus') == 'MP' || $user->campus == 'MP') {{ 'selected' }} @endif>Moises Padilla</option>
                                                <option value="HinC" @if (old('campus') == 'HinC' || $user->campus == 'HinC') {{ 'selected' }} @endif>Hinobaan</option>
                                                <option value="SC" @if (old('campus') == 'SC' || $user->campus == 'SC') {{ 'selected' }} @endif>Sipalay</option>
                                                <option value="IC" @if (old('campus') == 'IC' || $user->campus == 'IC') {{ 'selected' }} @endif>Ilog</option>
                                                <option value="CC" @if (old('campus') == 'CC' || $user->campus == 'CC') {{ 'selected' }} @endif>Cauayan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label><span class="badge badge-secondary">Department</span></label>
                                            <select class="form-control form-control-sm" name="campus" required="" style="pointer-events: none;">
                                                <option disabled selected>Select</option>
                                                <option value="CCS" @if (old('dept') == 'CCS' || $user->dept == 'CCS') {{ 'selected' }} @endif>College of Computer Studies</option>
                                                <option value="COTED" @if (old('dept') == 'COTED' || $user->dept == 'COTED') {{ 'selected' }} @endif>College of Teacher Education</option>
                                                <option value="CCJE" @if (old('dept') == 'CCJE' || $user->dept == 'CCJE') {{ 'selected' }} @endif>College of Criminal Justice Education</option>
                                                <option value="COE" @if (old('dept') == 'COE' || $user->dept == 'COE') {{ 'selected' }} @endif>College of Engineering</option>
                                                <option value="CAF" @if (old('dept') == 'CAF' || $user->dept == 'CAF') {{ 'selected' }} @endif>College of Agriculture and Forestry</option>
                                                <option value="CBM" @if (old('dept') == 'CBM' || $user->dept == 'CBM') {{ 'selected' }} @endif>College of Business Management</option>
                                                <option value="Guidance Office" @if (old('dept') == 'Guidance Office' || $user->dept == 'Guidance Office') {{ 'selected' }} @endif>Guidance Office</option>
                                                <option value="Registrar Office" @if (old('dept') == 'Registrar Office' || $user->dept == 'Registrar Office') {{ 'selected' }} @endif>Registrar Office</option>
                                                <option value="Assessment Office" @if (old('dept') == 'Assessment Office' || $user->dept == 'Assessment Office') {{ 'selected' }} @endif>Assessment Office</option>
                                                <option value="Scholarship Office" @if (old('dept') == 'Scholarship Office' || $user->dept == 'Scholarship Office') {{ 'selected' }} @endif>Scholarship Office</option>
                                                <option value="Cashier Office" @if (old('dept') == 'Cashier Office' || $user->dept == 'Cashier Office') {{ 'selected' }} @endif>Cashier Office</option>
                                                <option value="Graduate School Registar" @if (old('dept') == 'Graduate School Registar' || $user->dept == 'Graduate School Registar') {{ 'selected' }} @endif>Graduate School Registar</option>
                                                <option value="MIS" @if (old('dept') == 'MIS' || $user->dept == 'MIS') {{ 'selected' }} @endif>MIS Office</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">Last Name</span></label>
                                            <input type="text" name="lname" class="form-control form-control-sm" value="{{ $user->lname }}" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                                        </div>

                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">First Name</span></label>
                                            <input type="text" name="fname" class="form-control form-control-sm" value="{{ $user->fname }}" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">Middle Name</span></label>
                                            <input type="text" name="mname" class="form-control form-control-sm" value="{{ $user->mname }}" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                                        </div>

                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">Ext Name</span></label>
                                            <input type="text" name="ext" class="form-control form-control-sm" value="{{ $user->ext }}" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label><span class="badge badge-secondary">Email Address</span></label>
                                            <input type="email" name="email" class="form-control form-control-sm" value="{{ $user->email }}">
                                        </div>
                                    </div>
                                </div>

                                @auth('web')
                                    @if(Auth::guard('web')->user()->isAdmin == '0')
                                    <div class="form-group mt-2">
                                        <div class="form-row">
                                            <div class="col-md-12">
                                                <label>&nbsp;</label>
                                                <button type="submit" class="btn btn-primary btn-lg">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @endauth
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <form method="post" action="{{ route('userUpdatePassword') }}" enctype="multipart/form-data" id="classEnrollAdd">
                                @csrf
                                <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;">
                                    <h5>Update Password</h5>
                                </div>
                                <input type="hidden" name="id" value="{{ $user->id }}">
                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label><span class="badge badge-secondary">Password</span></label>
                                            <input type="text" name="password" class="form-control form-control-sm">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-2">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-lg">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection

@section('script')