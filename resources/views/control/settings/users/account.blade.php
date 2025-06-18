@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || User's List
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
                                                <option value="{{$user->role}}">
                                                    @if ($user->role == 1) Guidance Officer 
                                                        @elseif($user->role == 2) Guidance Staff 
                                                        @elseif($user->role == 3) Registrar 
                                                        @elseif($user->role == 4) Registrar Staff 
                                                        @elseif($user->role == 5) College Dean 
                                                        @elseif($user->role == 6) Program Head 
                                                        @elseif($user->role == 7) College Staff 
                                                        @elseif($user->role == 8) Scholarship Head
                                                        @elseif($user->role == 9) Scholarship Staff
                                                        @elseif($user->role == 10) Assessment Head
                                                        @elseif($user->role == 11) Assessment Staff
                                                        @elseif($user->role == 12) MIS Staff
                                                        @elseif($user->role == 13) MIS Director
                                                        @elseif($user->role == 14) MIS Officer
                                                        @elseif($user->role == 15) Graduate School Staff
                                                        @elseif($user->role == 16) OSSA Staff
                                                        @elseif($user->role == 17) Cashier
                                                        @elseif($user->role == 18) Cashier Staff
                                                    @endif
                                                </option>
                                                <option value="0" @if (old('role') == 0 || $user->role == '0') {{ 'selected' }} @endif>Administrator</option>
                                                <option value="1" @if (old('role') == 1 || $user->role == '1') {{ 'selected' }} @endif>Guidance Officer</option>
                                                <option value="2" @if (old('role') == 2 || $user->role == '2') {{ 'selected' }} @endif>Guidance Staff</option>
                                                <option value="3" @if (old('role') == 3 || $user->role == '3') {{ 'selected' }} @endif>Registrar</option>
                                                <option value="4" @if (old('role') == 4 || $user->role == '4') {{ 'selected' }} @endif>Registrar Staff</option>
                                                <option value="5" @if (old('role') == 5 || $user->role == '5') {{ 'selected' }} @endif>College Dean</option>
                                                <option value="6" @if (old('role') == 6 || $user->role == '6') {{ 'selected' }} @endif>Program Head</option>
                                                <option value="7" @if (old('role') == 7 || $user->role == '7') {{ 'selected' }} @endif>College Staff</option>
                                                <option value="8" @if (old('role') == 8 || $user->role == '8') {{ 'selected' }} @endif>Scholarship Head</option>
                                                <option value="9" @if (old('role') == 9 || $user->role == '9') {{ 'selected' }} @endif>Scholarship Staff</option>
                                                <option value="10" @if (old('role') == 10 || $user->role == '10') {{ 'selected' }} @endif>Assessment Head</option>
                                                <option value="11" @if (old('role') == 11 || $user->role == '11') {{ 'selected' }} @endif>Assessment Staff</option>
                                                <option value="12" @if (old('role') == 12 || $user->role == '12') {{ 'selected' }} @endif>MIS Staff</option>
                                                <option value="13" @if (old('role') == 13 || $user->role == '13') {{ 'selected' }} @endif>MIS Director</option>
                                                <option value="14" @if (old('role') == 14 || $user->role == '14') {{ 'selected' }} @endif>MIS Officer</option>
                                                <option value="15" @if (old('role') == 15 || $user->role == '15') {{ 'selected' }} @endif>Graduate School Staff</option>
                                                <option value="16" @if (old('role') == 16 || $user->role == '16') {{ 'selected' }} @endif>OSSA Staff</option>
                                                <option value="17" @if (old('role') == 17 || $user->role == '17') {{ 'selected' }} @endif>Cashier</option>
                                                <option value="18" @if (old('role') == 18 || $user->role == '18') {{ 'selected' }} @endif>Cashier Staff</option>
                                                <option value="19" @if (old('type') == 19 || $user->role == '19') {{ 'selected' }} @endif>Encoder</option>
                                                <option value="20" @if (old('type') == 20 || $user->role == '20') {{ 'selected' }} @endif>Dean of Instruction</option>
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
                                                <option value="MIS Office" @if (old('dept') == 'MIS Office' || $user->dept == 'MIS Office') {{ 'selected' }} @endif>MIS Office</option>
                                                <option value="OSSA" @if (old('dept') == 'OSSA' || $user->dept == 'OSSA') {{ 'selected' }} @endif>OSSA</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">Last Name</span></label>
                                            <input type="text" name="lname" class="form-control form-control-sm" value="{{ $user->lname }}" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">First Name</span></label>
                                            <input type="text" name="fname" class="form-control form-control-sm" value="{{ $user->fname }}" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">Middle Name</span></label>
                                            <input type="text" name="mname" class="form-control form-control-sm" value="{{ $user->mname }}" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label><span class="badge badge-secondary">Ext Name</span></label>
                                            <input type="text" name="ext" class="form-control form-control-sm" value="{{ $user->ext }}" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" readonly>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <label><span class="badge badge-secondary">Email Address</span></label>
                                            <input type="email" name="email" class="form-control form-control-sm" value="{{ $user->email }}" readonly>
                                        </div>
                                    </div>
                                </div>

                                @auth('web')
                                    @if(Auth::guard('web')->user()->role == '0')
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