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
                            <li class="breadcrumb-item active mt-1">Graduate School Student Info</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Graduate School Student Info</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"> 
                                        <form method="GET" action="{{ route('studInfograduated_search') }}">
                                            @csrf

                                            <div class="">
                                                <div class="form-group mt-3 mt-2">
                                                    <div class="row g-3">
                                                        <div class="col-md-2">
                                                            <label>Campus: <span class="text-danger">*</span></label>
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

                                        <div class="page-header mt-3" style="border-bottom: 1px solid #04401f;"></div>

                                        <div class="table-responsive mt-2 p-2">
                                            <table id="studinfoallgrad" class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Student ID</th>
                                                        <th>Gender</th>
                                                        <th>Civil Status</th>
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

    <div class="modal fade mt-6" id="viewdatastudModal" role="dialog" aria-labelledby="viewdatastudModalLabel" aria-hidden="true">
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
                                    <label>Student ID No.:</label>
                                    <input type="text" class="form-control form-control-sm text-bold" name="" id="viewdatastudID" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                </div>
                                <div class="col-md-2">
                                    <label>Firstname:</label>
                                    <input type="text" name="fname" class="form-control form-control-sm" id="viewdatastudFname" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-2">
                                    <label>Middlename:</label>
                                    <input type="text" name="mname" class="form-control form-control-sm" id="viewdatastudMname" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-2">
                                    <label>Lastname:</label>
                                    <input type="text" name="lname" class="form-control form-control-sm" id="viewdatastudLname" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-2">
                                    <label>Ext. name:</label>
                                    <input type="text" name="ext" class="form-control form-control-sm" id="viewdatastudExt" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-2">
                                    <label>Gender:</label>
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
                                <div class="col-md-2">
                                    <label>Civil Status:</label>
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
                                    <label>Update Birthday:</label>
                                    <input type="date" name="bday" class="form-control form-control-sm" id="viewdatastudBdaynotformat">
                                </div>
                                <div class="col-md-2">
                                    <label>Birthday:</label>
                                    <input type="text" class="form-control form-control-sm" id="viewdatastudBday" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                </div>

                                <div class="col-md-4">
                                    <label>Birth Place:</label>
                                    <input type="text" name="pbirth" class="form-control form-control-sm" id="viewdatastudBdayp">
                                </div>
                                <div class="col-md-2">
                                    <label>Mobile:</label>
                                    <input type="text" name="contact" class="form-control form-control-sm" id="viewdatastudMobile">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <label>House No:</label>
                                    <input type="text" name="hnum" class="form-control form-control-sm" id="viewdatastudHnum">
                                </div>
                                <div class="col-md-2">
                                    <label>Street/Barangay:</label>
                                    <input type="text" name="brgy" class="form-control form-control-sm" id="viewdatastudBrgy" oninput="this.value = this.value.toUpperCase()">
                                </div>
                                <div class="col-md-2">
                                    <label>Munipality/City:</label>
                                    <select name="city" class="form-control form-control-sm" id="viewdatastudCity">
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>Province:</label>
                                    <input type="text" name="province" class="form-control form-control-sm" id="viewdatastudProvince">
                                </div>
                                <div class="col-md-2">
                                    <label>Region:</label>
                                    <input type="text" name="region" class="form-control form-control-sm" id="viewdatastudRegion">
                                </div>
                                <div class="col-md-2">
                                    <label>Zip Code:</label>
                                    <input type="text" name="zcode" class="form-control form-control-sm" id="viewdatastudZcode">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>Spouse/Parent/Guardian:</label>
                                    <input type="text" name="spouseparent" class="form-control form-control-sm" id="viewdatastudSpouseParent">
                                </div>
                                <div class="col-md-8">
                                    <label>Address:</label>
                                    <input type="text" name="address" class="form-control form-control-sm" id="viewdatastudAddress" readonly style="background-color: #ddd !important; border: 1px solid #aaa;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save changes</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        var gradstudentlistinfoRoute = "{{ route('getstudInfograduated_search') }}";
        var appidEncryptRoute = "{{ route('idcrypt') }}";
        var studInfoUpdateRoute = "{{ route('studInfoUpdate', ['id' => ':id']) }}";

        var isCampus = '{{ Auth::guard('web')->user()->campus }}';
        var requestedCampus = '{{ request('campus') }}'
    </script>


    <script>
        function formatYear(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value.length >= 4) {
                input.value = value.substring(0, 4) + '-' + value.substring(4, 8);
            } else {
                input.value = value;
            }
        }
    </script>
@endsection
