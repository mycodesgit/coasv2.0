@extends('layouts.master_admission')

@section('title')
CISS V.1.0 || Add Applicant
@endsection

@section('sideheader')
<h4>Admission</h4>
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
            <li class="breadcrumb-item mt-1">Admission</li>
            <li class="breadcrumb-item active mt-1">Add Applicant</li>
        </ol>

        <p>
            @if(Session::has('success'))
                <div class="alert alert-success" id="alert">{{ Session::get('success')}} {{ Session::get('admission_id')}}</div>
            @elseif (Session::has('fail'))
                <div class="alert alert-danger" id="alert">{{Session::get('fail')}}</div>
            @endif
        </p>

        <div>
            <form method="post" action="{{ route('post-applicant-add') }}" enctype="multipart/form-data" id="admissionApply">
                @csrf

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Applicant Information</h4>
                </div>

                <div class="form-group mt-2">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Admission No.</span></label>
                            <input type="text" class="form-control form-control-sm" name="" placeholder="Auto-generated" readonly>
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Admission Type</span></label>
                            <select class="form-control form-control-sm" name="type">
                                <option value="">Select</option>
                                <option value="1" @if (old('type') == 1) {{ 'selected' }} @endif>New</option>
                                <option value="2" @if (old('type') == 2) {{ 'selected' }} @endif>Returnee</option>
                                <option value="3" @if (old('type') == 3) {{ 'selected' }} @endif>Transferee</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Preffered Campus</span></label>
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
                            <label><span class="badge badge-secondary">Lastname</span></label>
                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()"  name="lastname" value="{{old('lastname')}}">
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Firstname</span></label>
                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()"  name="firstname" value="{{old('firstname')}}">
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Middlename</span></label>
                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()"  name="mname" value="{{old('mname')}}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Ext.</span></label>
                            <select class="form-control form-control-sm" name="ext">
                                <option>N/A</option>
                                <option value="Jr." @if (old('ext') == "Jr.") {{ 'selected' }} @endif>Jr.</option>
                                <option value="Sr." @if (old('ext') == "Sr.") {{ 'selected' }} @endif>Sr.</option>
                                <option value="III" @if (old('ext') == "III") {{ 'selected' }} @endif>III</option>
                                <option value="IV" @if (old('ext') == "IV") {{ 'selected' }} @endif>IV</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Gender</span></label>
                            <select class="form-control form-control-sm" name="gender">
                                <option value="">Select</option>
                                <option value="Male" @if (old('gender') == "Male") {{ 'selected' }} @endif>Male</option>
                                <option value="Female" @if (old('gender') == "Female") {{ 'selected' }} @endif>Female</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Birthdate</span></label>
                            <input type="date" class="form-control form-control-sm" name="bday" id="bday" onchange="calculateAge()">
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Age</span></label>
                            <input type="text" class="form-control form-control-sm" name="age" id="age" readonly>
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Mobile</span></label>
                            <input type="number" class="form-control form-control-sm" name="contact" value="{{old('contact')}}">
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Email Address</span></label>
                            <input type="text" class="form-control form-control-sm" placeholder="e.g john@gmail.com" name="email" value="{{old('email')}}">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-row">
                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Civil Status</span></label>
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
                            <label><span class="badge badge-secondary">Religion</span></label>
                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="religion" value="{{old('religion')}}">
                        </div>

                        <div class="col-md-2">
                            <label><span class="badge badge-secondary">Parent's Monthly Income</span></label>
                            <input type="number" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="monthly_income" value="{{old('monthly_income')}}">
                        </div>

                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">Address</span></label>
                            <input type="text" class="form-control form-control-sm" oninput="var words = this.value.split(' '); for(var i = 0; i < words.length; i++){ words[i] = words[i].substr(0,1).toUpperCase() + words[i].substr(1); } this.value = words.join(' ');" placeholder="Present Address" name="address" value="{{old('address')}}">
                        </div>
                    </div>
                </div>

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>For New Student <span style="font-size: 12pt;color:#ff0000;">(Input for New Applicant only)</span></h4>
                </div>

                <div class="form-group mt-2">
                    <div class="form-row">
                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">Last School Attended</span></label>
                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="lstsch_attended" value="{{old('lstsch_attended')}}">
                        </div>

                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">Strand</span></label>
                            <select class="level form-control form-control-sm" name="strand" style="text-transform: uppercase;">
                                <option value="">Select</option>
                                @foreach ($strand as $strand)
                                <option value="{{ $strand->code }}" @if (old('strand') == "{{ $strand->code }}") {{ 'selected' }} @endif>{{ $strand->strand }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>For Transferee <span style="font-size: 12pt;color:#ff0000;">(Input for Transferees only)</span></h4>
                </div>

                <div class="form-group mt-2">
                    <div class="form-row">
                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">College/University last attended</span></label>
                            <input type="text" class="form-control form-control-sm" oninput="this.value = this.value.toUpperCase()" name="suc_lst_attended" value="{{old('suc_lst_attended')}}">
                        </div>

                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">Course</span></label>
                            <select class="form-control form-control-sm" name="course" style="text-transform: uppercase;">
                                <option value="">Select Course</option>
                                @foreach ($program as $programs)
                                <option value="{{ $programs->code }}" @if (old('course') == "{{ $programs->code }}") {{ 'selected' }} @endif>{{ $programs->program }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="page-header" style="border-bottom: 1px solid #04401f;">
                    <h4>Course Preference</h4>
                </div>

                <div class="form-group mt-2">
                    <div class="form-row">
                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">Course Preference 1</span></label>
                            <select class="form-control form-control-sm" name="preference_1" style="text-transform: uppercase;">
                                <option value="">Select Course Preference</option>
                                @foreach ($program as $programs)
                                <option value="{{ $programs->code }}" @if (old('course') == "{{ $programs->code }}") {{ 'selected' }} @endif>{{ $programs->program }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label><span class="badge badge-secondary">Course Preference 2</span></label>
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

                <div class="form-group mt-2">
                    <div class="form-row">
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
@endsection
