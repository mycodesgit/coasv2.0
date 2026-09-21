@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Add Applicant
@endsection

@section('sideheader')
<h4>Admission</h4>
@endsection

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
                            <li class="breadcrumb-item mt-1">Admission</li>
                            <li class="breadcrumb-item active mt-1">Add Applicant</li>
                        </ol>
                    </div>
                </div>
                <div class="row g-3 mb-3 mt-3">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                    <h4>Add Applicant</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="{{ route('applicantCreate') }}" id="admissionApply">
                                            @csrf

                                            <div class="form-group mt-4">
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label>Admission No.: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-sm" name="" placeholder="Auto-generated" readonly>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Admission Type: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="type" id="admissionType">
                                                            <option value="">Select</option>
                                                            <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>New</option>
                                                            <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Returnee</option>
                                                            <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Transferee</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Preffered Campus: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="campus">
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
                                                                @if (Auth::user()->isAdmin == 0)
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
                                                        <label>Lastname: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()"  name="lname" value="{{old('lname')}}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Firstname: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()"  name="fname" value="{{old('fname')}}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Middlename: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()"  name="mname" value="{{old('mname')}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label>Ext.: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="ext">
                                                            <option>N/A</option>
                                                            <option value="Jr." @if (old('ext') == "Jr.") {{ 'selected' }} @endif>Jr.</option>
                                                            <option value="Sr." @if (old('ext') == "Sr.") {{ 'selected' }} @endif>Sr.</option>
                                                            <option value="III" @if (old('ext') == "III") {{ 'selected' }} @endif>III</option>
                                                            <option value="IV" @if (old('ext') == "IV") {{ 'selected' }} @endif>IV</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Gender: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="gender">
                                                            <option value="">Select</option>
                                                            <option value="Male" @if (old('gender') == "Male") {{ 'selected' }} @endif>Male</option>
                                                            <option value="Female" @if (old('gender') == "Female") {{ 'selected' }} @endif>Female</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Birthdate: <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control form-control-sm" name="bday" id="bday" onchange="calculateAge()">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Age: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-sm" name="age" id="age" readonly>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Mobile: <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control form-control-sm" name="contact" value="{{old('contact')}}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Email Address: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-sm" placeholder="e.g john@gmail.com" name="email" value="{{old('email')}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="row g-3">
                                                    <div class="col-md-2">
                                                        <label>Civil Status: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="civil_status">
                                                            <option disabled selected>Select</option>
                                                            <option value="Single" @if (old('civil_status') == "Single") {{ 'selected' }} @endif>Single</option>
                                                            <option value="Married" @if (old('civil_status') == "Married") {{ 'selected' }} @endif>Married</option>
                                                            <option value="Divorced" @if (old('civil_status') == "Divorced") {{ 'selected' }} @endif>Divorced</option>
                                                            <option value="Widowed" @if (old('civil_status') == "Widowed") {{ 'selected' }} @endif>Widowed</option>
                                                            <option value="Separated" @if (old('civil_status') == "Separated") {{ 'selected' }} @endif>Separated</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Religion: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="religion" value="{{old('religion')}}">
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label>Parent's Monthly Income: <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="monthly_income" value="{{old('monthly_income')}}">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Address: <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control form-control-sm" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Present Address" name="address" value="{{old('address')}}">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="new-returnee-form" id="newReturneeForm" style="display: none;">
                                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                    <h4>For New Student <span style="font-size: 12pt;color:#ff0000;">(Input for New Applicant only)</span></h4>
                                                </div>

                                                <div class="form-group mt-4">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label>Last School Attended: <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="lstsch_attended" value="{{old('lstsch_attended')}}">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Strand: <span class="text-danger">*</span></label>
                                                            <select class="level form-control form-control-sm" name="strand" style="text-transform: uppercase;">
                                                                <option value="">Select</option>
                                                                @foreach ($strand as $strand)
                                                                <option value="{{ $strand->code }}">{{ $strand->strand }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="transferee-form" id="transfereeForm" style="display: none;">
                                                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                    <h4>For Transferee <span style="font-size: 12pt;color:#ff0000;">(Input for Transferees only)</span></h4>
                                                </div>

                                                <div class="form-group mt-4">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label>College/University last attended: <span class="text-danger">*</span></label>
                                                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="suc_lst_attended" value="{{old('suc_lst_attended')}}">
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Course: <span class="text-danger">*</span></label>
                                                            <select class="form-control form-control-sm" name="course" style="text-transform: uppercase;">
                                                                <option value="">Select Course</option>
                                                                @foreach ($program as $programs)
                                                                <option value="{{ $programs->code }}" @if (old('course') == $programs->code) {{ 'selected' }} @endif>{{ $programs->program }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                <h4>Course Preference</h4>
                                            </div>

                                            <div class="form-group mt-4">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label>Course Preference 1: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="preference_1" style="text-transform: uppercase;">
                                                            <option value="">Select Course Preference</option>
                                                            @foreach ($program as $programs)
                                                            <option value="{{ $programs->code }}" @if (old('course') == "{{ $programs->code }}") {{ 'selected' }} @endif>{{ $programs->program }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label>Course Preference 2: <span class="text-danger">*</span></label>
                                                        <select class="form-control form-control-sm" name="preference_2" style="text-transform: uppercase;">
                                                            <option value="">Select Course Preference</option>
                                                            @foreach ($program as $programs)
                                                            <option value="{{ $programs->code }}" @if (old('course') == "{{ $programs->code }}") {{ 'selected' }} @endif>{{ $programs->program }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="page-header" style="border-bottom: 1px solid #04401f;">
                                                <h4>Available Documents</h4>
                                            </div>

                                            <div class="form-group mt-4">
                                                <div class="row g-3">
                                                    <div class="container-fluid">
                                                        <div class="col-md-12">
                                                            <input type="radio" name="r_card" value="Yes"> Yes
                                                            <input type="radio" name="r_card" value="No"> No
                                                            <label>| Report Card</label>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="radio" name="g_moral" value="Yes"> Yes
                                                            <input type="radio" name="g_moral" value="No"> No
                                                            <label>| Certificate of Good Moral</label>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="radio" name="b_cert" value="Yes"> Yes
                                                            <input type="radio" name="b_cert" value="No"> No
                                                            <label>| Birth Certificate</label>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="radio" name="m_cert" value="Yes"> Yes
                                                            <input type="radio" name="m_cert" value="No"> No
                                                            <label>| Medical Certificate</label>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="radio" name="t_record" value="Yes"> Yes
                                                            <input type="radio" name="t_record" value="No"> No
                                                            <label>| Transcript of Record (For transferees)</label>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <input type="radio" name="h_dismissal" value="Yes"> Yes
                                                            <input type="radio" name="h_dismissal" value="No"> No
                                                            <label>| Honorable Dismissal (For transferees)</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-5 mb-3">
                                                <div class="col-md-12 col-6 d-flex justify-content-center">
                                                    <button type="submit" class="btn btn-primary btn-lg">
                                                        <i class="fas fa-check"></i> Apply
                                                    </button>
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
        </div>
    </div>

    <script>
        function calculateAge() {
            var birthday = document.getElementById('bday').value;
            var today = new Date();
            var birthDate = new Date(birthday);
            var age = today.getFullYear() - birthDate.getFullYear();

            if (today.getMonth() < birthDate.getMonth() || (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
                age--;
            }

            document.getElementById('age').value = age;
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var admissionType = document.getElementById('admissionType');
            var newReturneeForm = document.getElementById('newReturneeForm');
            var transfereeForm = document.getElementById('transfereeForm');

            // Show/hide forms based on the initial value
            toggleFormSections(admissionType.value);

            // Add event listener for change event
            admissionType.addEventListener('change', function() {
                toggleFormSections(this.value);
            });

            function toggleFormSections(value) {
                if (value == 1 || value == 2) { // New or Returnee
                    newReturneeForm.style.display = 'block';
                    transfereeForm.style.display = 'none';
                } else if (value == 3) { // Transferee
                    newReturneeForm.style.display = 'none';
                    transfereeForm.style.display = 'block';
                } else { // Hide all if no selection
                    newReturneeForm.style.display = 'none';
                    transfereeForm.style.display = 'none';
                }
            }
        });
    </script>
@endsection
