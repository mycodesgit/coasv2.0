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
            <li class="breadcrumb-item active mt-1">User's List</li>
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
                <div class="col-md-12">
                    <button type="button" class="btn btn-success btn-sm mb-4" data-toggle="modal" data-target="#modal-user">
                        <i class="fas fa-user-plus"></i> Add New
                    </button>

                    <button type="button" class="btn btn-info btn-sm mb-4" data-toggle="modal" data-target="#buttonFilterModal">
                      <i class="fas fa-filter"></i> Filter Menu
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
                            {{-- @php $no = 1; @endphp
                            @foreach($data as $user)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td style="text-transform: uppercase;">
                                        <b>{{$user->fname}} 
                                            @if($user->mname == null)
                                                @else {{ substr($user->mname,0,1) }}.
                                            @endif {{$user->lname}}  

                                            @if($user->ext == 'N/A') 
                                                @else{{$user->ext}}
                                            @endif
                                        </b>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->role == '0')
                                            <span class="badge badge-secondary">Administrator</span>
                                        @elseif ($user->role == '1')
                                            <span class="badge badge-primary">Guidance Officer</span>
                                        @elseif ($user->role == '2')
                                            <span class="badge badge-success">Guidance Staff</span>
                                        @elseif ($user->role == '3')
                                            <span class="badge badge-danger">Registrar</span>
                                        @elseif ($user->role == '4')
                                            <span class="badge badge-warning">Registrar Staff</span>
                                        @elseif ($user->role == '5')
                                            <span class="badge badge-info">College Dean</span>
                                        @elseif ($user->role == '6')
                                            <span class="badge badge-info">Program Head</span>
                                        @elseif ($user->role == '7')
                                            <span class="badge badge-info">College Staff</span>
                                        @elseif ($user->role == '8')
                                            <span class="badge badge-warning">Scholarship Head</span>
                                        @elseif ($user->role == '9')
                                            <span class="badge badge-warning">Scholarship Staff</span>
                                        @elseif ($user->role == '10')
                                            <span class="badge badge-warning">Assessment Head</span>
                                        @elseif ($user->role == '11')
                                            <span class="badge badge-warning">Assessment Staff</span>
                                        @elseif ($user->role == '12')
                                            <span class="badge badge-secondary">MIS Staff</span>
                                        @elseif ($user->role == '13')
                                            <span class="badge badge-secondary">MIS Director</span>
                                        @elseif ($user->role == '14')
                                            <span class="badge badge-secondary">MIS Officer</span>
                                        @elseif ($user->role == '15')
                                            <span class="badge badge-warning">Grad School Staff</span>
                                        @elseif ($user->role == '16')
                                            <span class="badge badge-pink" style="background-color: #e83e8c; color: #fff">OSSA Staff</span>
                                        @elseif ($user->role == '17')
                                            <span class="badge badge-info">Cashier</span>
                                        @elseif ($user->role == '18')
                                            <span class="badge badge-info">Cashier Staff</span>
                                        @else
                                            <span class="badge badge-light">Unknown Role</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->campus }}</td>
                                    <td style="text-align:center;">
                                        <a href="{{ route('edit_user', ['id' => encrypt($user->id)]) }}" type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-cog"></i>
                                        </a>
                                        <button class="btn btn-primary btn-sm btn-edit"
                                            data-toggle="modal"
                                            data-target="#buttonFilterModal{{ $user->id }}"
                                            data-event-id="{{ $user->id }}"
                                            data-buttonmenus="{{ json_encode($user->buttons) }}">
                                            <i class="fas fa-exclamation-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                <!-- Button Filter Modal -->
                                <div class="modal fade" id="buttonFilterModal{{ $user->id }}" tabindex="-1" aria-labelledby="buttonFilterModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('userbuttonUpdate', ['id' => $user->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $user->id }}">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="buttonFilterModalLabel">Filter User Buttons</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="user">Name</label>
                                                        <input type="text" class="form-control form-control-sm" value="{{ $user->fname }} {{ substr($user->mname,0,1) }}. {{ $user->lname }}" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="user">Role</label>
                                                        <input type="text" class="form-control form-control-sm" value="{{ 
                                                            $user->role == 0 ? 'Administrator' :
                                                            ($user->role == 1 ? 'Guidance Officer' :
                                                            ($user->role == 2 ? 'Guidance Staff' :
                                                            ($user->role == 3 ? 'Registrar' :
                                                            ($user->role == 4 ? 'Registrar Staff' :
                                                            ($user->role == 5 ? 'College Dean' :
                                                            ($user->role == 6 ? 'Program Head' :
                                                            ($user->role == 7 ? 'College Staff' :
                                                            ($user->role == 8 ? 'Scholarship Head' :
                                                            ($user->role == 9 ? 'Scholarship Staff' :
                                                            ($user->role == 10 ? 'Assessment Head' :
                                                            ($user->role == 11 ? 'Assessment Staff' :
                                                            ($user->role == 12 ? 'MIS Staff' :
                                                            ($user->role == 13 ? 'MIS Director' :
                                                            ($user->role == 14 ? 'MIS Officer' :
                                                            ($user->role == 15 ? 'Graduate School Staff' :
                                                            ($user->role == 16 ? 'OSSA Staff' :
                                                            ($user->role == 17 ? 'Cashier' :
                                                            ($user->role == 18 ? 'Cashier Staff' : 'Unknown Role'))))))))))))))))))
                                                        }}" readonly>
                                                    </div>
                                                    <div class="form-group" id="buttonSelection">
                                                        <label for="buttons">Select Buttons</label>
                                                        @php
                                                            $buttons = [
                                                                'admission-url' => 'Admission',
                                                                'enrollment-url' => 'Enrollment',
                                                                'scheduler-url' => 'Scheduling',
                                                                'assessment-url' => 'Assessment',
                                                                'cashiering-url' => 'Cashiering',
                                                                'scholarship-url' => 'Scholarship',
                                                                'grading-url' => 'Grading',
                                                                'kiosk-url' => 'Kiosk',
                                                                'request-url' => 'Request',
                                                                'setting-url' => 'Settings',
                                                            ];
                                                            $userButtons = json_decode($user->buttons, true) ?? [];
                                                        @endphp

                                                        @foreach($buttons as $value => $label)
                                                            <div class="icheck-success">
                                                                <input type="checkbox" id="{{ $value }}-{{ $user->id }}" name="buttons[]" value="{{ $value }}"
                                                                @if(in_array($value, $userButtons)) checked @endif>
                                                                <label for="{{ $value }}-{{ $user->id }}">{{ $label }}</label>
                                                            </div>
                                                        @endforeach
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
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edituserModal" tabindex="-1" role="dialog" aria-labelledby="edituserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edituserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edituserForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edituserId">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">First Name:</span></label>
                                <input type="text" name="fname" id="edituserfname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter First Name" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Middle Name:</span></label>
                                <input type="text" name="mname" id="editusermname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter Middle Name" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Last Name:</span></label>
                                <input type="text" name="lname" id="edituserlname" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Enter Last Name" class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Ext.:</span></label>
                                <select class="form-control form-control-sm" name="ext" id="edituserext">
                                    <option value="">N/A</option>
                                    <option value="Jr." @if (old('ext') == "Jr.") {{ 'selected' }} @endif>Jr.</option>
                                    <option value="Sr." @if (old('ext') == "Sr.") {{ 'selected' }} @endif>Sr.</option>
                                    <option value="III" @if (old('ext') == "III") {{ 'selected' }} @endif>III</option>
                                    <option value="IV" @if (old('ext') == "IV") {{ 'selected' }} @endif>IV</option>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label><span class="badge badge-secondary">Email</span></label>
                                <input type="text" name="email" id="edituseremail" placeholder="Enter Email" class="form-control form-control-sm">
                            </div>

                            <div class="col-md-4">
                                <label><span class="badge badge-danger">Campus</span></label>
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

                    <div class="form-group"> 
                        <div class="form-row">
                            <div class="col-md-8">
                                <label><span class="badge badge-warning">Department</span></label>
                                <select class="form-control form-control-sm" name="dept" id="edituserdept">
                                    <option disabled selected>Select</option>
                                    <option value="CAS" @if (old('dept') == 'CAS') {{ 'selected' }} @endif>College of Arts and Sciences</option>
                                    <option value="CCS" @if (old('dept') == 'CCS') {{ 'selected' }} @endif>College of Computer Studies</option>
                                    <option value="COTED" @if (old('dept') == 'COTED') {{ 'selected' }} @endif>College of Teacher Education</option>
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
                                <label><span class="badge badge-success">User Level</span></label>
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
                                </select>
                            </div>
                        </div>
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

<div class="modal fade" id="edituserPassModal" tabindex="-1" role="dialog" aria-labelledby="edituserPassModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edituserPassModalLabel">Change User Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edituserPassForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edituserPassId">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Change New Password:</span></label>
                                <input type="text" name="password" id="edituserpass" placeholder="Enter New Password" class="form-control form-control-sm">
                            </div>
                        </div>
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

<div class="modal fade" id="edituserAccessModal" tabindex="-1" role="dialog" aria-labelledby="edituserAccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edituserAccessModalLabel">User Access</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edituserAccessForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edituserAccessId">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Name:</span></label>
                                <input type="text" id="editusername"  class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">User Level:</span></label>
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
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <div class="form-row">
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
                                        'grading-url' => 'Grading',
                                        'kiosk-url' => 'Kiosk',
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edituserSchlyrAccessModal" tabindex="-1" role="dialog" aria-labelledby="edituserSchlyrAccessModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edituserSchlyrAccessModalLabel">Allow User Access for Academic Year</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edituserSchlyrAccessForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edituserSchlyrAccessId">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Name:</span></label>
                                <input type="text" id="editusernameAllow"  class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">User Level:</span></label>
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

                    <div class="form-group"> 
                        <div class="form-row">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edituserDeactModal" tabindex="-1" role="dialog" aria-labelledby="edituserDeactModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="edituserDeactModalLabel">Change User Status</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="edituserDeactForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edituserDeactId">

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Name:</span></label>
                                <input type="text" id="edituserDeactfullname"  class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-row">
                            <div class="col-md-12">
                                <label><span class="badge badge-secondary">Change User Status:</span></label>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var useraccountRoute = "{{ route('getusersRead') }}";
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

@section('script')