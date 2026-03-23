@extends('layouts.master_settings')

@section('title')
CISS V.1.0 || Settings
@endsection

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
                            <li class="breadcrumb-item mt-1">Settings</li>
                            <li class="breadcrumb-item active mt-1">User's Management</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>User's Management</h4>
                                </div>
                                <div class="row">
                                    <div class="table-responsive p-3 mt-3">
                                        <button type="button" class="btn btn-success btn-sm mb-4 text-light" data-bs-toggle="modal" data-bs-target="#modal-user">
                                            <i class="fas fa-user-plus"></i> Add New
                                        </button>
                                        @include('modal.userAdd')
                                        <table id="userlist" class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Campus</th>
                                                    <th>Status</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edituserModal" tabindex="-1" aria-modal="true" role="dialog" aria-labelledby="edituserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edituserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edituserForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edituserId">

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>First Name: <span class="text-danger">*</span></label>
                                    <input type="text" name="fname" id="edituserfname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter First Name" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-4">
                                    <label>Middle Name: </label>
                                    <input type="text" name="mname" id="editusermname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter Middle Name" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-4">
                                    <label>Last Name: <span class="text-danger">*</span></label>
                                    <input type="text" name="lname" id="edituserlname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter Last Name" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>Ext.: </label>
                                    <select class="form-control form-control-sm" name="ext" id="edituserext">
                                        <option value="">N/A</option>
                                        <option value="Jr." @if (old('ext') == "Jr.") {{ 'selected' }} @endif>Jr.</option>
                                        <option value="Sr." @if (old('ext') == "Sr.") {{ 'selected' }} @endif>Sr.</option>
                                        <option value="III" @if (old('ext') == "III") {{ 'selected' }} @endif>III</option>
                                        <option value="IV" @if (old('ext') == "IV") {{ 'selected' }} @endif>IV</option>
                                    </select>
                                </div>
                                
                                <div class="col-md-4">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="text" name="email" id="edituseremail" placeholder="Enter Email" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-4">
                                    <label>Campus: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="campus" id="editusercampus">
                                        <option disabled selected>Select</option>
                                        <option value="MC" @if (old('campus') == 'MC') {{ 'selected' }} @endif>Main</option>
                                        <option value="VC" @if (old('campus') == 'VC') {{ 'selected' }} @endif>Victorias</option>
                                        <option value="SCC" @if (old('campus') == 'SCC') {{ 'selected' }} @endif>San Carlos</option>
                                        <option value="HC" @if (old('campus') == 'HC') {{ 'selected' }} @endif>Hinigaran</option>
                                        <option value="MP" @if (old('campus') == 'MP') {{ 'selected' }} @endif>Moises Padilla</option>
                                        <option value="IC" @if (old('campus') == 'IC') {{ 'selected' }} @endif>Ilog</option>
                                        <option value="CA" @if (old('campus') == 'CA') {{ 'selected' }} @endif>Candoni</option>
                                        <option value="CC" @if (old('campus') == 'CC') {{ 'selected' }} @endif>Cauayan</option>
                                        <option value="SC" @if (old('campus') == 'SC') {{ 'selected' }} @endif>Sipalay</option>
                                        <option value="HinC" @if (old('campus') == 'HinC') {{ 'selected' }} @endif>Hinobaan</option>
                                        <option value="VE" @if (old('campus') == 'VE') {{ 'selected' }} @endif>Valladolid</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3"> 
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label>Department: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="dept" id="edituserdept">
                                        <option disabled selected>Select</option>
                                        <option value="CAS" @if (old('dept') == 'CAS') {{ 'selected' }} @endif>College of Arts and Sciences</option>
                                        <option value="CCS" @if (old('dept') == 'CCS') {{ 'selected' }} @endif>College of Computer Studies</option>
                                        <option value="CTE" @if (old('dept') == 'CTE') {{ 'selected' }} @endif>College of Teacher Education</option>
                                        <option value="CCJE" @if (old('dept') == 'CCJE') {{ 'selected' }} @endif>College of Criminal Justice Education</option>
                                        <option value="COE" @if (old('dept') == 'COE') {{ 'selected' }} @endif>College of Engineering</option>
                                        <option value="CAF" @if (old('dept') == 'CAF') {{ 'selected' }} @endif>College of Agriculture and Forestry</option>
                                        <option value="CBM" @if (old('dept') == 'CBM') {{ 'selected' }} @endif>College of Business Management</option>
                                        <option value="Guidance Office" @if (old('dept') == 'Guidance Office') {{ 'selected' }} @endif>Guidance Office</option>
                                        <option value="Registrar Office" @if (old('dept') == 'Registrar Office') {{ 'selected' }} @endif>Registrar Office</option>
                                        <option value="Assessment Office" @if (old('dept') == 'Assessment Office') {{ 'selected' }} @endif>Assessment Office</option>
                                        <option value="Scholarship Office" @if (old('dept') == 'Scholarship Office') {{ 'selected' }} @endif>Scholarship Office</option>
                                        <option value="Cashier Office" @if (old('dept') == 'Cashier Office') {{ 'selected' }} @endif>Cashier Office</option>
                                        <option value="Graduate School Registar" @if (old('dept') == 'Graduate School Registar') {{ 'selected' }} @endif>Graduate School Registar</option>
                                        <option value="MIS Office" @if (old('dept') == 'MIS Office') {{ 'selected' }} @endif>MIS Office</option>
                                        <option value="OSSA" @if (old('dept') == 'OSSA') {{ 'selected' }} @endif>OSSA</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>User Level: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" name="role" id="edituserrole">
                                        <option disabled selected>Level</option>
                                        <option value="0" @if (old('type') == 0) {{ 'selected' }} @endif>Administrator</option>
                                        <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>Guidance Officer</option>
                                        <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Guidance Staff</option>
                                        <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Registrar</option>
                                        <option value="4" @if (old('type') == 4) {{ 'selected' }} @endif>Registrar Staff</option>
                                        <option value="5" @if (old('type') == 5) {{ 'selected' }} @endif>College Dean</option>
                                        <option value="6" @if (old('type') == 6) {{ 'selected' }} @endif>Program Head</option>
                                        <option value="7" @if (old('type') == 7) {{ 'selected' }} @endif>College Staff</option>
                                        <option value="8" @if (old('type') == 8) {{ 'selected' }} @endif>Scholarship Head</option>
                                        <option value="9" @if (old('type') == 9) {{ 'selected' }} @endif>Scholarship Staff</option>
                                        <option value="10" @if (old('type') == 10) {{ 'selected' }} @endif>Assessment Head</option>
                                        <option value="11" @if (old('type') == 11) {{ 'selected' }} @endif>Assessment Staff</option>
                                        <option value="12" @if (old('type') == 12) {{ 'selected' }} @endif>MIS Staff</option>
                                        <option value="13" @if (old('type') == 13) {{ 'selected' }} @endif>MIS Director</option>
                                        <option value="14" @if (old('type') == 14) {{ 'selected' }} @endif>MIS Officer</option>
                                        <option value="15" @if (old('type') == 15) {{ 'selected' }} @endif>Graduate School Staff</option>
                                        <option value="16" @if (old('type') == 16) {{ 'selected' }} @endif>OSSA Staff</option>
                                        <option value="17" @if (old('type') == 17) {{ 'selected' }} @endif>Cashier</option>
                                        <option value="18" @if (old('type') == 18) {{ 'selected' }} @endif>Cashier Staff</option>
                                        <option value="19" @if (old('type') == 19) {{ 'selected' }} @endif>Encoder</option>
                                        <option value="20" @if (old('type') == 20) {{ 'selected' }} @endif>Dean of Instruction</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edituserPassModal" tabindex="-1" aria-modal="true" role="dialog" aria-labelledby="edituserPassModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edituserPassModalLabel">Change User Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edituserPassForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edituserPassId">

                        <div class="form-group">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>New Password: <span class="text-danger">*</span></label>
                                    <input type="text" name="password" id="edituserpass" placeholder="Enter New Password" class="form-control form-control-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edituserAccessModal" tabindex="-1" aria-modal="true" role="dialog" aria-labelledby="edituserAccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edituserAccessModalLabel">User Access</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edituserAccessForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edituserAccessId">

                        <div class="form-group mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="editusername"  class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>User Level: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="edituserroleaccess" disabled>
                                        <option disabled selected>Level</option>
                                        <option value="0" @if (old('type') == 0) {{ 'selected' }} @endif>Administrator</option>
                                        <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>Guidance Officer</option>
                                        <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Guidance Staff</option>
                                        <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Registrar</option>
                                        <option value="4" @if (old('type') == 4) {{ 'selected' }} @endif>Registrar Staff</option>
                                        <option value="5" @if (old('type') == 5) {{ 'selected' }} @endif>College Dean</option>
                                        <option value="6" @if (old('type') == 6) {{ 'selected' }} @endif>Program Head</option>
                                        <option value="7" @if (old('type') == 7) {{ 'selected' }} @endif>College Staff</option>
                                        <option value="8" @if (old('type') == 8) {{ 'selected' }} @endif>Scholarship Head</option>
                                        <option value="9" @if (old('type') == 9) {{ 'selected' }} @endif>Scholarship Staff</option>
                                        <option value="10" @if (old('type') == 10) {{ 'selected' }} @endif>Assessment Head</option>
                                        <option value="11" @if (old('type') == 11) {{ 'selected' }} @endif>Assessment Staff</option>
                                        <option value="12" @if (old('type') == 12) {{ 'selected' }} @endif>MIS Staff</option>
                                        <option value="13" @if (old('type') == 13) {{ 'selected' }} @endif>MIS Director</option>
                                        <option value="14" @if (old('type') == 14) {{ 'selected' }} @endif>MIS Officer</option>
                                        <option value="15" @if (old('type') == 15) {{ 'selected' }} @endif>Graduate School Staff</option>
                                        <option value="16" @if (old('type') == 16) {{ 'selected' }} @endif>OSSA Staff</option>
                                        <option value="17" @if (old('type') == 17) {{ 'selected' }} @endif>Cashier</option>
                                        <option value="18" @if (old('type') == 18) {{ 'selected' }} @endif>Cashier Staff</option>
                                        <option value="19" @if (old('type') == 19) {{ 'selected' }} @endif>Encoder</option>
                                        <option value="20" @if (old('type') == 20) {{ 'selected' }} @endif>Dean of Instruction</option>
                                        <option value="21" @if (old('type') == 21) {{ 'selected' }} @endif>YearBook</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3"> 
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="buttons">Select Buttons</label>
                                    @php
                                        $buttons = [
                                            'admission-url' => 'Admission',
                                            'enrollment-url' => 'Enrollment',
                                            'scheduler-url' => 'Scheduling',
                                            'assessment-url' => 'Assessment',
                                            'cashiering-url' => 'Cashiering',
                                            'scholarship-url' => 'Scholarship',
                                            'yearbook-url' => 'Yearbook',
                                            'grading-url' => 'Grading',
                                            'kiosk-url' => 'Kiosk',
                                            'queue-url' => 'Qeueuing',
                                            'nstp-url' => 'Nstp',
                                            'ossa-url' => 'Ossa',
                                            'request-url' => 'Request',
                                            'setting-url' => 'Settings',
                                        ];
                                    @endphp

                                    @foreach($buttons as $value => $label)
                                        <div class="icheck-success">
                                            <input type="checkbox" id="{{ $value }}" name="buttons[]" value="{{ $value }}">
                                            <label for="{{ $value }}">{{ $label }}</label>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edituserSchlyrAccessModal" tabindex="-1" aria-modal="true" role="dialog" aria-labelledby="edituserSchlyrAccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edituserSchlyrAccessModalLabel">Allow User Access for Academic Year</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edituserSchlyrAccessForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edituserSchlyrAccessId">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="editusernameAllow"  class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>User Level: <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-sm" id="edituserroleaccessAllow" disabled>
                                        <option disabled selected>Level</option>
                                        <option value="0" @if (old('type') == 0) {{ 'selected' }} @endif>Administrator</option>
                                        <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>Guidance Officer</option>
                                        <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Guidance Staff</option>
                                        <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Registrar</option>
                                        <option value="4" @if (old('type') == 4) {{ 'selected' }} @endif>Registrar Staff</option>
                                        <option value="5" @if (old('type') == 5) {{ 'selected' }} @endif>College Dean</option>
                                        <option value="6" @if (old('type') == 6) {{ 'selected' }} @endif>Program Head</option>
                                        <option value="7" @if (old('type') == 7) {{ 'selected' }} @endif>College Staff</option>
                                        <option value="8" @if (old('type') == 8) {{ 'selected' }} @endif>Scholarship Head</option>
                                        <option value="9" @if (old('type') == 9) {{ 'selected' }} @endif>Scholarship Staff</option>
                                        <option value="10" @if (old('type') == 10) {{ 'selected' }} @endif>Assessment Head</option>
                                        <option value="11" @if (old('type') == 11) {{ 'selected' }} @endif>Assessment Staff</option>
                                        <option value="12" @if (old('type') == 12) {{ 'selected' }} @endif>MIS Staff</option>
                                        <option value="13" @if (old('type') == 13) {{ 'selected' }} @endif>MIS Director</option>
                                        <option value="14" @if (old('type') == 14) {{ 'selected' }} @endif>MIS Officer</option>
                                        <option value="15" @if (old('type') == 15) {{ 'selected' }} @endif>Graduate School Staff</option>
                                        <option value="16" @if (old('type') == 16) {{ 'selected' }} @endif>OSSA Staff</option>
                                        <option value="17" @if (old('type') == 17) {{ 'selected' }} @endif>Cashier</option>
                                        <option value="18" @if (old('type') == 18) {{ 'selected' }} @endif>Cashier Staff</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3"> 
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="buttons">Select Academic Year</label>
                                    @php
                                        use App\Models\SettingDB\ConfigureCurrent;

                                        $sy = ConfigureCurrent::select('id', 'schlyear')
                                            ->whereIn('id', function($query) {
                                                $query->select(DB::raw('MAX(id)'))
                                                    ->from('settings_conf')
                                                    ->groupBy('schlyear');
                                            })
                                            ->orderBy('id', 'DESC')
                                            ->get();
                                    @endphp

                                    @foreach($sy as $label)
                                        <div class="icheck-success">
                                            <input type="checkbox" id="schlyear{{ $label->schlyear }}" name="schlyraccess[]" value="{{ $label->schlyear }}">
                                            <label for="schlyear{{ $label->schlyear }}">{{ $label->schlyear }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edituserDeactModal" tabindex="-1" aria-modal="true" role="dialog" aria-labelledby="edituserDeactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edituserDeactModalLabel">Change User Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edituserDeactForm">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edituserDeactId">

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Name: <span class="text-danger">*</span></label>
                                    <input type="text" id="edituserDeactfullname"  class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Change User Status: <span class="text-danger">*</span></label>
                                    <select name="statuser" class="form-control form-control-sm" id="edituserDeactStat">
                                        <option disabled selected> --Select-- </option>
                                        <option value="1">Enable</option>
                                        <option value="2">Disabled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var useraccountRoute = "{{ route('getusersRead') }}";
        var userCreateRoute = "{{ route('userCreate') }}";
        var useraccessRoute = "{{ route('getButtonAccess', ['id' => ':id']) }}";
        var schlyraccessRoute = "{{ route('getSchlyearAccess', ['id' => ':id']) }}";
        var userSaveAccessRoute = "{{ route('saveButtonAccess', ['id' => ':id']) }}";
        var schlyrSaveAccessRoute = "{{ route('saveSchlyrAccess', ['id' => ':id']) }}";
        var useraccountUpdateRoute = "{{ route('userUpdate', ['id' => ':id']) }}";
        var userpassUpdateRoute = "{{ route('userPassUpdate', ['id' => ':id']) }}";
        var userDeactRoute = "{{ route('userStatusUpdate', ['id' => ':id']) }}";

        var setconfCreateRoute = "{{ route('setconfCreate') }}";
        var setconfUpdateRoute = "{{ route('setconfUpdate', ['id' => ':id']) }}";
    </script>
@endsection
