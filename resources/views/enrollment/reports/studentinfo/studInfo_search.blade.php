@extends('layouts.master_enrollment')

@section('title')
CISS V.1.0 || Enrollment
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
                            <li class="breadcrumb-item mt-1">Enrollment</li>
                            <li class="breadcrumb-item active mt-1">Student Information</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Student Information</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="GET" action="{{ route('studInfo_search') }}">
                                            @csrf

                                            <div class="form-group mt-2">
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <label>Campus:</label>
                                                        <select class="form-control form-control-sm" name="campus" id="campus">
                                                            <option value="{{Auth::user()->campus}}">
                                                                @if (Auth::user()->campus == 'MC') Main 
                                                                    @elseif(Auth::user()->campus == 'VC') Victorias 
                                                                    @elseif(Auth::user()->campus == 'SCC') San Carlos 
                                                                    @elseif(Auth::user()->campus == 'HC') Hinigaran 
                                                                    @elseif(Auth::user()->campus == 'MP') Moises Padilla 
                                                                    @elseif(Auth::user()->campus == 'IC') Ilog 
                                                                    @elseif(Auth::user()->campus == 'CA') Candoni 
                                                                    @elseif(Auth::user()->campus == 'CC') Cauayan 
                                                                    @elseif(Auth::user()->campus == 'SC') Sipalay  
                                                                    @elseif(Auth::user()->campus == 'HinC') Hinobaan 
                                                                    @elseif(Auth::user()->campus == 'VE') Valladolid
                                                                @endif
                                                            </option>
                                                            @if(Auth::user()->role == 0)
                                                                <option value="MC">Main</option>
                                                                <option value="VC">Victorias</option>
                                                                <option value="SCC">San Carlos</option>
                                                                <option value="HC">Hinigaran</option>
                                                                <option value="MP">Moises Padilla</option>
                                                                <option value="IC">Ilog</option>
                                                                <option value="CA">Candoni</option>
                                                                <option value="CC">Cauayan</option>
                                                                <option value="SC">Sipalay</option>
                                                                <option value="HinC">Hinobaan</option>
                                                                <option value="VE">Valladolid</option>
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
                                        </form>

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="col-md-12 mt-3">
                                            <div class="table-responsive p-2">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade mt-6" id="viewdatastudModal" role="dialog" aria-labelledby="viewdatastudModalLabel" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewdatastudModalLabel">View Student Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editStudInfoForm">
                    <div class="modal-body">
                        <div class="page-header mt-1" style="border-bottom: 1px solid #04401f;">
                            <h4>Student Information</h4>
                        </div>
                        <input type="hidden" name="id" id="viewdatastudIdprim">
                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label>Student ID No.</label>
                                    <input type="text" class="form-control form-control-sm" name="" id="viewdatastudID" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label>Firstname</label>
                                    <input type="text" name="fname" class="form-control form-control-sm" id="viewdatastudFname">
                                </div>
                                <div class="col-md-2">
                                    <label>Middlename</label>
                                    <input type="text" name="mname" class="form-control form-control-sm" id="viewdatastudMname">
                                </div>
                                <div class="col-md-2">
                                    <label>Lastname</label>
                                    <input type="text" name="lname" class="form-control form-control-sm" id="viewdatastudLname">
                                </div>
                                <div class="col-md-2">
                                    <label>Ext. name</label>
                                    <input type="text" name="" class="form-control form-control-sm" id="viewdatastudExt">
                                </div>
                                <div class="col-md-2">
                                    <label>Gender</label>
                                    <select class="form-control form-control-sm" name="gender" id="viewdatastudGender">
                                        <option disabled selected>Select</option>
                                        @foreach($genderStatuses as $gdrstatus)
                                            <option value="{{ $gdrstatus->genderstat_name }}">
                                                {{ $gdrstatus->genderstat_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label>Region</label>
                                    <select id="region" class="form-control form-control-sm select2bs4">
                                        <option value="">Select Region</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region->region_id }}" data-name="{{ $region->name }}">{{ $region->name }}</option>
                                        @endforeach
                                        <input type="hidden" id="region_name" name="region">
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Province</label>
                                    <select id="province" class="form-control form-control-sm select2bs4">
                                        <option value="">Select Province</option>
                                    </select>
                                    <input type="hidden" id="province_name" name="province">
                                </div>
                                <div class="col-md-6">
                                    <label>City/Municipality</label>
                                    <select id="city" class="form-control form-control-sm select2bs4">
                                        <option value="">Select City</option>
                                    </select>
                                    <input type="hidden" id="city_name" name="city">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label>Barangay</label>
                                    <select id="barangay" class="form-control form-control-sm select2bs4">
                                        <option value="">Select Barangay</option>
                                    </select>
                                    <input type="hidden" id="brgy_name" name="brgy">
                                </div>
                                <div class="col-md-4">
                                    <label>House No. / Block / Purok</label>
                                    <input type="text" name="hnum" id="viewdatastudHnum" class="form-control form-control-sm" placeholder="House No. / Block / Purok">
                                </div>
                                <div class="col-md-2">
                                    <label>Zipcode</label>
                                    <input type="text" name="zcode" id="zipcode" class="form-control form-control-sm" readonly placeholder="Zip Code" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Birth Place</label>
                                    <input type="text" name="pbirth" class="form-control form-control-sm" id="viewdatastudBdayp">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label>Civil Status</label>
                                    <select class="form-control form-control-sm" name="civil_status" id="viewdatastudcivilstat">
                                        <option disabled selected>Select</option>
                                        @foreach($civilStatuses as $status)
                                            <option value="{{ $status->cvlstat_name }}">
                                                {{ $status->cvlstat_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Update Birthday</label>
                                    <input type="date" name="bday" class="form-control form-control-sm" id="viewdatastudBdaynotformat">
                                </div>
                                <div class="col-md-2">
                                    <label><span class="badge badge-warning">Birthday</span></label>
                                    <input type="text" class="form-control form-control-sm" id="viewdatastudBday" readonly>
                                </div>
                                <div class="col-md-2">
                                    <label><span class="badge badge-success">Mobile</span></label>
                                    <input type="text" name="contact" class="form-control form-control-sm" id="viewdatastudMobile">
                                </div>
                                <div class="col-md-2">
                                    <label>Email Address</label>
                                    <input type="text" name="email" class="form-control form-control-sm" id="viewdatastudEmail">
                                </div>
                                <div class="col-md-2">
                                    <label>Religion</label>
                                    <input type="text" name="religion" class="form-control form-control-sm" id="viewdatastudReligion">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control form-control-sm" id="viewdatastudAddress" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                </div>
                            </div>
                        </div>

                        <div class="page-header mt-5" style="border-bottom: 1px solid #04401f;">
                            <h4>Family Information</h4>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label>Father's Name</label>
                                    <input type="text" class="form-control form-control-sm" name="stud_father" id="viewdatastudfather">
                                </div>
                                <div class="col-md-2">
                                    <label>Mother's Name</label>
                                    <input type="text" class="form-control form-control-sm" name="stud_mother" id="viewdatastudmother">
                                </div>
                                <div class="col-md-2">
                                    <label>Guardian's Name</label>
                                    <input type="text" class="form-control form-control-sm" name="stud_guardian" id="viewdatastudguardian">
                                </div>
                                <div class="col-md-2">
                                    <label>Monthly Income</label>
                                    <input type="text" class="form-control form-control-sm" name="monthly_income" id="viewdatastudprntincome">
                                </div>
                                <div class="col-md-2">
                                    <label>Contact Number</label>
                                    <input type="text" class="form-control form-control-sm" name="guardian_contact" id="viewdatastudpcontact">
                                </div>
                            </div>
                        </div>

                        <div class="page-header mt-5" style="border-bottom: 1px solid #04401f;">
                            <h4>Other Information</h4>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>Last School Attended</label>
                                    <input type="text" class="form-control form-control-sm" name="lstsch_attended" id="viewdatastudlstschattended">
                                </div>
                                <div class="col-md-2">
                                    <label>Last S.Y. Attended</label>
                                    <input type="text" class="form-control form-control-sm" name="lst_sch_attended_year" id="viewdatastudlstschattendedyear">
                                </div>
                                <div class="col-md-4">
                                    <label>Last University Attended</label>
                                    <input type="text" class="form-control form-control-sm" name="suc_lst_attended" id="viewdatastudlstsucattnded">
                                </div>
                                <div class="col-md-2">
                                    <label>Date of Admission</label>
                                    <input type="text" class="form-control form-control-sm" name="date_admission" id="viewdatastuddateadmission" readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var studentlistinfoRoute = "{{ route('getstudInfo_search') }}";
        var appidEncryptRoute = "{{ route('idcrypt') }}";
        var studInfoUpdateRoute = "{{ route('studInfoUpdate', ['id' => ':id']) }}";

        var isCampus = '{{ Auth::guard('web')->user()->campus }}';
        var requestedCampus = '{{ request('campus') }}'

        var provincesRoute = '{{ route("getProvinces", "") }}';
        var citiesRoute = '{{ route("getCities", "") }}';
        var barangaysRoute = '{{ route("getBarangays", "") }}';
    </script>
@endsection
